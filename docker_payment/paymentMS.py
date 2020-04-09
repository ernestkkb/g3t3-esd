from flask import Flask, request, jsonify, render_template
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
import psycopg2
from os import environ
from urllib.parse import urlparse

app = Flask(__name__)
#change link to own database directory
# app.config['SQLALCHEMY_DATABASE_URI'] = environ.get('dbURL')
app.config['SQLALCHEMY_DATABASE_URI']="postgres://fuzjrjhzokhyqv:b3796b31a69eb86a553055d629b77c39226aeccbdf4f2a32d8345988a0c1cff3@ec2-52-200-119-0.compute-1.amazonaws.com:5432/de91gk7fniercq"
# app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/payment'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
CORS(app)

#Change to own Database
class Payment(db.Model):
    __tablename__ = 'payment_process'
    userid = db.Column(db.String(100), primary_key=True)
    tripid = db.Column(db.String(12), primary_key=True)
    price = db.Column(db.Float(precision=2), nullable=False)
    paymentstatus = db.Column(db.String(10), nullable=False)

    def __init__(self, userid, tripid, price, paymentstatus):
        self.userid = userid
        self.tripid = tripid
        self.price = price
        self.paymentstatus = paymentstatus

    def json(self):
        return {"userID": self.userid, "tripID": self.tripid, "price": self.price, "paymentStatus": self.paymentstatus}


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
        
    # triplist= [{
    #                 "name": "Travel Package A",
    #                 "sku": "fjf847fg",
    #                 "price": "20",
    #                 "currency": "SGD",
    #                 "quantity": 1} ] 
                    
    print("payment function triggered")
    print(triplist)
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
            "return_url": "google.com",
            "cancel_url": "google.com"},
        "transactions": [{
            "item_list": {
                "items": triplist},
            "amount": {
                "total": str(total),
                "currency": "SGD"},
            "description": "This is the payment transaction description."}]})

    if payment.create():
        print('Payment success!')
        status=update_trip_status(triplist[0]["sku"])
        print(status)
        if status[1] == 201: 
            url_str = os.environ.get('CLOUDAMQP_URL', 'amqp://guest:guest@localhost//')
            url = urlparse(url_str)
            params = pika.ConnectionParameters(host=url.hostname, virtual_host=url.path[1:],
            credentials=pika.PlainCredentials(url.username, url.password))
            connection = pika.BlockingConnection(params)
            channel = connection.channel()
            replymessage = json.dumps({"ID":triplist[0]['sku'] }, default=str)
            exchangename="exchange_topic"
            channel.exchange_declare(exchange=exchangename, exchange_type='topic')
            channel.queue_declare(queue='payment.reply', durable=True) # make sure the queue used by the error handler exist and durable
            channel.queue_bind(exchange=exchangename, queue='payment.reply', routing_key='*.reply') # make sure the queue is bound to the exchange
            channel.basic_publish(exchange=exchangename, routing_key="payment.reply", body=replymessage,
            properties=pika.BasicProperties(delivery_mode = 2))
        else:
            return jsonify({"message": "An error occurred updating the trip status."}), 500
            
    else:
        print(payment.error)
    # print(payment.id) printed payment ID, it works. 
    return jsonify({'paymentID' : payment.id})


@app.route('/execute', methods=['POST'])
def execute():
    success = False
    items = request.get_json()
    print(items)
    paymentID = items['paymentID']
    payment = paypalrestsdk.Payment.find(paymentID)
    payerID = items['payerID']
    if payment.execute({"payer_id" : payerID}):
        print('Execute success!')
        success = True
        
    else:
        print(payment.error)
    return jsonify({'success' : success})

#checkout trip for payment - step 3: update payment status in DB to "paid" once payment is successful  
# @app.route("/payment/update/<string:tripID>")
def update_trip_status(tripid):
    print("update_trip_status function triggered")
    payment = Payment.query.filter_by(tripid=tripid).first()
    if payment:
        try:
            payment.paymentstatus = 'paid'
            db.session.commit()
            return ("message: Payment status updated.",201)
        except:
             return ("message: An error occurred updating the payment status.",500)


@app.route("/payment")
def get_all():
    return jsonify({"payment_process": [payment_process.json() for payment_process in Payment.query.all()]})

@app.route("/paymentHistory/<string:userid>")
def paymentHistory(userid):
    return jsonify({"paymentHistory": [payment_process.json() for payment_process in Payment.query.filter_by(userid=userid).all()]})

#Add to cart
@app.route("/payment/<string:tripid>/<string:userid>/<string:price>/<string:paymentstatus>")
def get_trip_payment_details(tripid,userid,price,paymentstatus):
    if (Payment.query.filter_by(tripid=tripid).first()):
        return jsonify({"message": "A trip with Trip ID '{}' already exists.".format(tripid)}), 400

    payment = Payment(userid,tripid,price,paymentstatus)

    try:
        db.session.add(payment)
        db.session.commit()
    except:
        return jsonify({"message": "An error occurred creating the trip payment."}), 500

    return jsonify(payment.json()), 201

@app.route("/deleteAll")
def deleteAll():
    try:
        db.session.query(Payment).delete()
        db.session.commit()
    except:
        db.session.rollback()

        return jsonify({"message": "An error occurred deleting the rows"}), 500
    return jsonify({"message": "rows deleted"}), 500
if __name__ == '__main__':
    #port=5000 - location search
    #port=5001 - package reccomendation 
    #port=5002 - scheduler
    #port=5003 - payment
    #port=5004 - notifications
    port = int(os.environ.get('PORT', 5000))
    app.run(host='0.0.0.0', port=port, debug=False) 
    #receiveTripDetails() #invoke the consume function 
    #with app.run it will allow the system call the name without required flask
    #with __name__ == '__main__' it will start flask to listen to request
    # we specify the port to use is 5000 (which is the default port anyway) and set debug to True, 
    # which will provide debugging information and also restart the app automatically if the code is modified while the app is running. 
    # the thing that needs to be change is the PORT 
