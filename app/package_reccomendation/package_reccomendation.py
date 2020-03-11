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

app = Flask(__name__)

#change link to own database directory
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/package_recommendation'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
CORS(app)

#Change to own Database
class Package_recommendation(db.Model):
    __tablename__ = 'package_recommendation'

    packageID = db.Column(db.Integer, primary_key=True)
    city = db.Column(db.String(100), nullable=False)
    placesOfInterest = db.Column(db.JSON, nullable=False)
    price = db.Column(db.Float(precision=2), nullable=False)

    def __init__(self, packageID, city, placesOfInterest, price):
        self.packageID = packageID
        self.city = city
        self.placesOfInterest = placesOfInterest
        self.price = price

    def json(self):
        return {"packageID": self.packageID, "city": self.city, "placesOfInterest": self.placesOfInterest, "price": self.price}

@app.route("/package_recommendation")
def get_all():
    all_package = {"package_recomendation": [package_recommendation.json() for package_recommendation in Package_recommendation.query.all()]}
    return jsonify(all_package)

# @app.route("/payment/<string:tripID>", methods=['POST'])
# def get_trip_payment_details(tripID):
#     if (Payment.query.filter_by(tripID=tripID).first()):
#         return jsonify({"message": "A trip with Trip ID '{}' already exists.".format(tripID)}), 400

#     data = request.get_json()
#     payment = Payment(tripID, **data)

#     try:
#         db.session.add(payment)
#         db.session.commit()
#     except:
#         return jsonify({"message": "An error occurred creating the trip payment."}), 500

#     return jsonify(payment.json()), 201

# @app.route("/payment/update/<string:tripID>")
# def update_trip_status(tripID):
#     payment = Payment.query.filter_by(tripID=tripID).first()
#     if payment:
#         try:
#             payment.paymentStatus = 'paid'
#             db.session.commit()
#             forward_tripID(tripID)
#         except:
#             return jsonify({"message": "An error occurred updating the payment status."}), 500
#     return jsonify({"message": "Payment status updated."}), 201
@app.route("/package_recommendation/forward")
def forward_packageID():
    all_package1 ={"package_recomendation": [package_recommendation.json() for package_recommendation in Package_recommendation.query.all()]}

    """inform Scheduler microservice"""
    # default username / password to the borker are both 'guest'
    hostname = "localhost" # default broker hostname. Web management interface default at http://localhost:15672
    port = 5672 # default messaging port.
    # connect to the broker and set up a communication channel in the connection
    connection = pika.BlockingConnection(pika.ConnectionParameters(host=hostname, port=port))
        # Note: various network firewalls, filters, gateways (e.g., SMU VPN on wifi), may hinder the connections;
        # If "pika.exceptions.AMQPConnectionError" happens, may try again after disconnecting the wifi and/or disabling firewalls
    channel = connection.channel()

    # set up the exchange if the exchange doesn't exist
    exchangename="package_topic"
    channel.exchange_declare(exchange=exchangename, exchange_type='topic')

    # prepare the message body content
    message = json.dumps(all_package1, default=str) # convert a JSON object to a string

    channel.queue_declare(queue='package', durable=True) # make sure the queue used by the error handler exist and durable
    channel.queue_bind(exchange=exchangename, queue='package', routing_key='*.package') # make sure the queue is bound to the exchange
    channel.basic_publish(exchange=exchangename, routing_key="scheduler.package", body=message,
        properties=pika.BasicProperties(delivery_mode = 2) # make message persistent within the matching queues until it is received by some receiver (the matching queues have to exist and be durable and bound to the exchange)
    )
    # channel.basic_publish(exchange=exchangename, routing_key="notification.payment", body=message,
    #     properties=pika.BasicProperties(delivery_mode = 2) # make message persistent within the matching queues until it is received by some receiver (the matching queues have to exist and be durable and bound to the exchange)
    # )
    #print("Package ID sent to Scheduler & Notification microservice.")
    # close the connection to the broker
    connection.close()

if __name__ == '__main__':
    #port=5000 - location search
    #port=5001 - package reccomendation 
    #port=5002 - scheduler
    #port=5003 - payment
    #port=5004 - notifications
    app.run(port=5002, debug=True) 
    #with app.run it will allow the system call the name without required flask
    #with __name__ == '__main__' it will start flask to listen to request
    # we specify the port to use is 5000 (which is the default port anyway) and set debug to True, 
    # which will provide debugging information and also restart the app automatically if the code is modified while the app is running. 
    # the thing that needs to be change is the PORT 
