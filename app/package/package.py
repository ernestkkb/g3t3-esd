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
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/package'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
CORS(app)

#Change to own Database
class package(db.Model):
    __tablename__ = 'package'

    id = db.Column(db.Integer(), primary_key=True)
    tripName = db.Column(db.String(44))
    placeOfInterest = db.Column(db.JSON, nullable=True)
    day = db.Column(db.Integer())
    tripID = db.Column(db.String(20), nullable=True)

    def __init__(self, id, tripName, placeOfInterest, day, tripID):
        self.id = id
        self.tripName = tripName
        self.placeOfInterest = placeOfInterest
        self.day = day
        self.tripID = tripID

    def json(self):
        return {"tripID": self.tripID, "tripName": self.tripName, "placeOfInterest": self.placeOfInterest,  "day":self.day, "id":self.id}



@app.route("/package")
def get_all():
    all_package = {"package": [package.json() for package in package.query.all()]}
    return jsonify(all_package)

# @app.route("/package/<string:tripID>")
# def find_by_tripid(tripID):
#     trip = package.query.filter_by(tripID=tripID).all()
#     if trip:
#         return jsonify(trip.json())
#     return jsonify({"message": "Trip not found."}), 404

@app.route("/retrieveAll/<string:tripID>")
def retrieveAll(tripID):
    details = package.query.filter_by(tripID=tripID).all()
    detailsToReturn = {"details" : [detail.json() for detail in details]}
    if detailsToReturn:
        print(jsonify(detailsToReturn))
        return jsonify(detailsToReturn)
    return jsonify({"message": "Trip not found."}), 404

@app.route("/addTrip/<string:tripID>")
def addTrip(tripID):
    details = package.query.filter_by(tripID=tripID).all()
    detailsToReturn = {"details" : [detail.json() for detail in details]}
    if detailsToReturn:
        for i in detailsToReturn:
            
        return jsonify(detailsToReturn)
    return jsonify({"message": "Trip not found."}), 404

@app.route("/package/forward/<string:tripID>", methods = ['POST'])
def forward_packageID(tripID):
    all_package1 ={"package": [package.json() for package in package.query.filterby(tripID=tripID).all()]}

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
    app.run(port=5001, debug=True) 
    #with app.run it will allow the system call the name without required flask
    #with __name__ == '__main__' it will start flask to listen to request
    # we specify the port to use is 5000 (which is the default port anyway) and set debug to True, 
    # which will provide debugging information and also restart the app automatically if the code is modified while the app is running. 
    # the thing that needs to be change is the PORT 
