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


paypalrestsdk.configure({
  "mode": "sandbox", # sandbox or live
  "client_id": "AQTRgxt_BRwy1QJWpfnQxCOindU_V0JdyvcbP0XpV9da2XYk0C9V2VWhMdnFYVZ0RZU8LhDGl_zgDwDA",
  "client_secret": "EJUcPqPO8rFvr9l8mW1bB9EOV9jRJFplhhK1uAyOmsZNoHEuytLiE1k0tegR2Ic0DLdddiYIDpO-1hoY" })

#checkout trip for payment - step 2: invoke paypal API with tripdetails 
@app.route('/makepayment', methods=['POST'])
def payment():
    if request.is_json:
        triplist = request.get_json()
    else:
        triplist = request.get_data()
        
    print(triplist)
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
            hostname = "localhost"
            port = 5672 
            connection = pika.BlockingConnection(pika.ConnectionParameters(host=hostname, port=port))
            channel = connection.channel()
            replymessage = json.dumps({"ID":triplist[0]['sku'] }, default=str)
            exchangename="exchange_topic"
            channel.exchange_declare(exchange=exchangename, exchange_type='topic')
            channel.queue_declare(queue='payment.reply', durable=True) # make sure the queue used by the error handler exist and durable
            channel.queue_bind(exchange=exchangename, queue='payment.reply', routing_key='*.reply') # make sure the queue is bound to the exchange
            channel.basic_publish(exchange=exchangename, routing_key="payment.reply", body=replymessage,
            properties=pika.BasicProperties(delivery_mode = 2)
        )
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
# @app.route("/payment/update/<string:tripID>")
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

if __name__ == '__main__':
    #port=5000 - location search
    #port=5001 - package reccomendation 
    #port=5002 - scheduler
    #port=5003 - payment
    #port=5004 - notifications
    app.run(port=5003, debug=True) 
    #receiveTripDetails() #invoke the consume function 
    #with app.run it will allow the system call the name without required flask
    #with __name__ == '__main__' it will start flask to listen to request
    # we specify the port to use is 5000 (which is the default port anyway) and set debug to True, 
    # which will provide debugging information and also restart the app automatically if the code is modified while the app is running. 
    # the thing that needs to be change is the PORT 
