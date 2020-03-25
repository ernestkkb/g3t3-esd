# from common import *
# importStuff()

from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import update
from flask_cors import CORS
import json
import sys
import os
import random
import datetime
import pika
import paypalrestsdk

app = Flask(__name__)
app.app_context()
#change link to own database directory
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/payment'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
CORS(app)

#Change to own Database
class Payment(db.Model):
    __tablename__ = 'payment_process'

    tripID = db.Column(db.Integer, primary_key=True)
    price = db.Column(db.Float(precision=2), nullable=False)
    paymentStatus = db.Column(db.String(10), nullable=False)

    def __init__(self, tripID, price, paymentStatus):
        self.tripID = tripID
        self.price = price
        self.paymentStatus = paymentStatus

    def json(self):
        return {"tripID": self.tripID, "price": self.price, "paymentStatus": self.paymentStatus}

#checkout trip for payment - step 1: consume trip details from scheduler MS 
@app.route('/receiveTrip', methods=['POST'])
def receiveTripDetails():
    print("receiveTripDetails function triggered")
    hostname = "localhost" # default host
    port = 5672 # default port
    # connect to the broker and set up a communication channel in the connection
    connection = pika.BlockingConnection(pika.ConnectionParameters(host=hostname, port=port))
    channel = connection.channel()

    # set up the exchange if the exchange doesn't exist
    exchangename="exchange_topic"
    channel.exchange_declare(exchange=exchangename, exchange_type='topic')

    # prepare a queue for receiving messages
    channelqueue = channel.queue_declare(queue='scheduler', durable=True) # '' indicates a random unique queue name; 'exclusive' indicates the queue is used only by this receiver and will be deleted if the receiver disconnects.
        # If no need durability of the messages, no need durable queues, and can use such temp random queues.
    queue_name = channelqueue.method.queue
    channel.queue_bind(exchange=exchangename, queue=queue_name, routing_key='*.scheduler') # bind the queue to the exchange via the key
        # any routing_key would be matched

    # set up a consumer and start to wait for coming messages
    channel.basic_consume(queue=queue_name, on_message_callback=callback, auto_ack=True)
    channel.start_consuming() # an implicit loop waiting to receive messages; it doesn't exit by default. Use Ctrl+C in the command window to terminate it.

def callback(channel, method, properties, body): # required signature for the callback; no return
    print("Received an Trip details")
    #print(json.loads(body))
    payment(json.loads(body))
    print() # print a new line feed

paypalrestsdk.configure({
  "mode": "sandbox", # sandbox or live
  "client_id": "AQTRgxt_BRwy1QJWpfnQxCOindU_V0JdyvcbP0XpV9da2XYk0C9V2VWhMdnFYVZ0RZU8LhDGl_zgDwDA",
  "client_secret": "EJUcPqPO8rFvr9l8mW1bB9EOV9jRJFplhhK1uAyOmsZNoHEuytLiE1k0tegR2Ic0DLdddiYIDpO-1hoY" })

#checkout trip for payment - step 2: invoke paypal API with tripdetails 
@app.route('/makepayment', methods=['POST'])
def payment(triplist):
    print("payment function triggered")
    total=0
    for dict1 in triplist:
        for key in dict1:
            if key=="price":
                total+=float(dict1["price"])*int(dict1["quantity"])
    
    payment = paypalrestsdk.Payment({
        "intent": "sale",
        "payer": {
            "payment_method": "paypal"},
        "redirect_urls": {
            "return_url": "http://localhost:5003/payment/execute",
            "cancel_url": "http://localhost:5003/"},
        "transactions": [{
            "item_list": {
                "items": triplist},
            "amount": {
                "total": str(total),
                "currency": "USD"},
            "description": "This is the payment transaction description."}]})

    if payment.create():
        print('Payment success!')
        status=update_trip_status(triplist[0]["sku"])
        if status[1] == 201: 
            forward_tripID(triplist[0]["sku"])
        else:
            return jsonify({"message": "An error occurred updating the trip status."}), 500
            
    else:
        print(payment.error)

    return jsonify({'paymentID' : payment.id})


@app.route('/execute', methods=['POST'])
def execute():
    success = False
    payment = paypalrestsdk.Payment.find(request.form['paymentID'])

    if payment.execute({'payer_id' : request.form['payerID']}):
        print('Execute success!')
        success = True
    else:
        print(payment.error)

    return jsonify({'success' : success})

#checkout trip for payment - step 3: update payment status in DB to "paid" once payment is successful  
@app.route("/payment/update/<string:tripID>")
def update_trip_status(tripID):
    print("update_trip_status function triggered")
    payment = Payment.query.filter_by(tripID=tripID).first()
    if payment:
        try:
            payment.paymentStatus = 'paid'
            db.session.commit()
            return ("message: Payment status updated.",201)
        except:
             return ("message: An error occurred updating the payment status.",500)


#checkout trip for payment - step 4: Inform notification MS and scheduler MS upon completion
@app.route("/payment/forward/<string:tripID>")
def forward_tripID(tripID):
    print("forward_tripID function triggered")
    """inform Scheduler & Notification microservice"""
    # default username / password to the borker are both 'guest'
    hostname = "localhost" # default broker hostname. Web management interface default at http://localhost:15672
    port = 5672 # default messaging port.
    # connect to the broker and set up a communication channel in the connection
    connection = pika.BlockingConnection(pika.ConnectionParameters(host=hostname, port=port))
        # Note: various network firew
        # alls, filters, gateways (e.g., SMU VPN on wifi), may hinder the connections;
        # If "pika.exceptions.AMQPConnectionError" happens, may try again after disconnecting the wifi and/or disabling firewalls
    channel = connection.channel()

    # set up the exchange if the exchange doesn't exist
    exchangename="exchange_topic"
    channel.exchange_declare(exchange=exchangename, exchange_type='topic')

    # prepare the message body content
    message = json.dumps(tripID, default=str) # convert a JSON object to a string

    channel.queue_declare(queue='payment', durable=True) # make sure the queue used by the error handler exist and durable
    channel.queue_bind(exchange=exchangename, queue='payment', routing_key='*.payment') # make sure the queue is bound to the exchange
    channel.basic_publish(exchange=exchangename, routing_key="scheduler.payment", body=message,
        properties=pika.BasicProperties(delivery_mode = 2) # make message persistent within the matching queues until it is received by some receiver (the matching queues have to exist and be durable and bound to the exchange)
    )
    channel.basic_publish(exchange=exchangename, routing_key="notification.payment", body=message,
        properties=pika.BasicProperties(delivery_mode = 2) # make message persistent within the matching queues until it is received by some receiver (the matching queues have to exist and be durable and bound to the exchange)
    )
    print("Trip ID sent to Scheduler & Notification microservice.")
    # close the connection to the broker
    connection.close()

@app.route("/payment")
def get_all():
    return jsonify({"payment_process": [payment_process.json() for payment_process in Payment.query.all()]})

#Add to cart
@app.route("/payment/<string:tripID>", methods=['POST'])
def get_trip_payment_details(tripID):
    if (Payment.query.filter_by(tripID=tripID).first()):
        return jsonify({"message": "A trip with Trip ID '{}' already exists.".format(tripID)}), 400

    data = request.get_json()
    payment = Payment(tripID, **data)

    try:
        db.session.add(payment)
        db.session.commit()
    except:
        return jsonify({"message": "An error occurred creating the trip payment."}), 500

    return jsonify(payment.json()), 201
############################################################################## PAYPAL API ################################################################################

if __name__ == '__main__':
    #port=5000 - location search
    #port=5001 - package reccomendation 
    #port=5002 - scheduler
    #port=5003 - payment
    #port=5004 - notifications
    # app.run(port=5003, debug=True) 
    receiveTripDetails() #invoke the consume function 
    #with app.run it will allow the system call the name without required flask
    #with __name__ == '__main__' it will start flask to listen to request
    # we specify the port to use is 5000 (which is the default port anyway) and set debug to True, 
    # which will provide debugging information and also restart the app automatically if the code is modified while the app is running. 
    # the thing that needs to be change is the PORT 
