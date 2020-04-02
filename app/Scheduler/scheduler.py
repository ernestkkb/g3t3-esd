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
import requests


app = Flask(__name__)

#change link to own database directory
# our database is called scheduler and table is scheduler

app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/scheduler'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False


db = SQLAlchemy(app)
CORS(app)
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

class scheduler(db.Model):
    __tablename__ = 'scheduler'

    id = db.Column(db.Integer(), primary_key=True)
    tripName = db.Column(db.String(44))
    facebookID = db.Column(db.String(20), nullable=True)
    placeOfInterest = db.Column(db.JSON, nullable=True)
    # startDate = db.Column(db.Date, nullable=True)
    # endDate = db.Column(db.Date, nullable=True)
    paymentStatus = db.Column(db.String(10))
    day = db.Column(db.Integer())
    tripID = db.Column(db.String(20), nullable=True)

    def __init__(self, id, tripName, facebookID, placeOfInterest, paymentStatus,day, tripID):
        self.id = id
        self.tripName = tripName
        self.facebookID = facebookID
        self.placeOfInterest = placeOfInterest
        # self.startDate = startDate
        # self.endDate = endDate
        self.paymentStatus = paymentStatus
        self.day = day
        self.tripID = tripID

    def json(self):
        return {"tripID": self.tripID, "tripName": self.tripName, "facebookID": self.facebookID, "placeOfInterest": self.placeOfInterest, "paymentStatus": self.paymentStatus, "day":self.day, "id":self.id}



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

@app.route("/retrieveAllTripID/<string:tripID>")
def retrieveAllTripID(tripID):
    details = package.query.filter_by(tripID=tripID).all()
    detailsToReturn = {"details" : [detail.json() for detail in details]}
    if detailsToReturn:
        print(jsonify(detailsToReturn))
        return jsonify(detailsToReturn)
    return jsonify({"message": "Trip not found."}), 404

@app.route("/addTrip/<string:tripID>/<string:userID>")
def addTrip(tripID,userID):
    details = package.query.filter_by(tripID=tripID).all()
    detailsToReturn = {"details" : [detail.json() for detail in details]}
    if detailsToReturn:
        for i in detailsToReturn['details']:
            i['facebookID'] = userID
            i['paymentStatus'] = "unpaid"
            add_POI_for_preplanned(i)
        return jsonify(detailsToReturn,userID)
    return jsonify({"message": "Trip not found."}), 404
def add_POI_for_preplanned(data):
    #data = {"id":"5", "tripName": "testing","facebookID":"1", "placeOfInterest":{}, "startDate": "2020-03-12", "endDate":"2020-03-15","paymentStatus":"paid", "day":day} # details of book must be sent in body of the request in JSON format. get_json() retrieves the data from the request received.

    addnewpoi = scheduler(**data)

    try:
        db.session.add(addnewpoi) # db.session provided by SQLAlchemy. 
        db.session.commit()
    except:
        return jsonify({"message": "An error occurred adding the POI."}), 500 # return JSON with HTTP status code 500 - INTERNAL SERVER ERROR if an exception occurs
    
    return jsonify(addnewpoi.json()), 201 # if no errors, return JSON representation of book with HTTP status cde 201 - CREATED

# @app.route("/package/forward/<string:tripID>", methods = ['POST'])
# def forward_packageID(tripID):
#     all_package1 ={"package": [package.json() for package in package.query.filterby(tripID=tripID).all()]}

#     """inform Scheduler microservice"""
#     # default username / password to the borker are both 'guest'
#     hostname = "localhost" # default broker hostname. Web management interface default at http://localhost:15672
#     port = 5672 # default messaging port.
#     # connect to the broker and set up a communication channel in the connection
#     connection = pika.BlockingConnection(pika.ConnectionParameters(host=hostname, port=port))
#         # Note: various network firewalls, filters, gateways (e.g., SMU VPN on wifi), may hinder the connections;
#         # If "pika.exceptions.AMQPConnectionError" happens, may try again after disconnecting the wifi and/or disabling firewalls
#     channel = connection.channel()

#     # set up the exchange if the exchange doesn't exist
#     exchangename="package_topic"
#     channel.exchange_declare(exchange=exchangename, exchange_type='topic')

#     # prepare the message body content
#     message = json.dumps(all_package1, default=str) # convert a JSON object to a string

#     channel.queue_declare(queue='package', durable=True) # make sure the queue used by the error handler exist and durable
#     channel.queue_bind(exchange=exchangename, queue='package', routing_key='*.package') # make sure the queue is bound to the exchange
#     channel.basic_publish(exchange=exchangename, routing_key="scheduler.package", body=message,
#         properties=pika.BasicProperties(delivery_mode = 2) # make message persistent within the matching queues until it is received by some receiver (the matching queues have to exist and be durable and bound to the exchange)
#     )
#     # channel.basic_publish(exchange=exchangename, routing_key="notification.payment", body=message,
#     #     properties=pika.BasicProperties(delivery_mode = 2) # make message persistent within the matching queues until it is received by some receiver (the matching queues have to exist and be durable and bound to the exchange)
#     # )
#     #print("Package ID sent to Scheduler & Notification microservice.")
#     # close the connection to the broker
#     connection.close()

#retrieve trip by tripID, facebookID, day. This function is to get data for google directions
@app.route("/scheduler/<string:tripID>/<string:facebookID>/<string:day>")
def find_for_routing(tripID, facebookID, day):
    trips = scheduler.query.filter_by(tripID=tripID, facebookID=facebookID, day=day).all()
    tripToReturn = {"trips" : [trip.json() for trip in trips]}
    if trips:
        return jsonify(tripToReturn)
    return jsonify({"message": "Trip not found."}), 404

@app.route("/retrieveAll/<string:facebookID>")
def retrieveAll(facebookID):
    details = scheduler.query.filter_by(facebookID=facebookID).all()
    print(details)
    detailsToReturn = {"details" : [detail.json() for detail in details]}
    print(detailsToReturn)
    if detailsToReturn:
        print(jsonify(detailsToReturn))
        return jsonify(detailsToReturn)
    return jsonify({"message": "Trip not found."}), 404

#add poi to db
@app.route("/addPOI", methods= ['POST']) # specify HTTP methods when necessary
def add_POI():
    #data = {"id":"5", "tripName": "testing","facebookID":"1", "placeOfInterest":{}, "startDate": "2020-03-12", "endDate":"2020-03-15","paymentStatus":"paid", "day":day} # details of book must be sent in body of the request in JSON format. get_json() retrieves the data from the request received.
    data = request.get_json()
    print(data)
    addnewpoi = scheduler(**data)

    try:
        db.session.add(addnewpoi) # db.session provided by SQLAlchemy. 
        db.session.commit()
    except:
        return jsonify({"message": "An error occurred adding the POI."}), 500 # return JSON with HTTP status code 500 - INTERNAL SERVER ERROR if an exception occurs
    
    return jsonify(addnewpoi.json()), 201 # if no errors, return JSON representation of book with HTTP status cde 201 - CREATED


@app.route('/makepayment', methods=['POST'])
def forward_trip():
    if request.is_json:
        triplist = request.get_json()
    else:
        triplist = request.get_data()
        replymessage = json.dumps({"message": "Order should be in JSON", "data": triplist}, default=str)
        return replymessage, 400 # Bad Request

    # triplist=[{
    #     "name": tripName,
    #     "sku": "Trip ID: " + tripID,
    #     "price": 20,
    #     "currency": "SGD",
    #     "quantity": 1}]

    """inform Payment microservice"""
    # default username / password to the broker are both 'guest'
    hostname = "localhost" # default broker hostname. Web management interface default at http://localhost:15672
    port = 5672 # default messaging port.
    # connect to the broker and set up a communication channel in the connection
    connection = pika.BlockingConnection(pika.ConnectionParameters(host=hostname, port=port))
        # Note: various network firewalls, filters, gateways (e.g., SMU VPN on wifi), may hinder the connections;
        # If "pika.exceptions.AMQPConnectionError" happens, may try again after disconnecting the wifi and/or disabling firewalls
    channel = connection.channel()

    # set up the exchange if the exchange doesn't exist
    exchangename="exchange_topic"
    channel.exchange_declare(exchange=exchangename, exchange_type='topic')

    # prepare the message body content
    message = json.dumps(triplist, default=str) # convert a JSON object to a string

    channel.queue_declare(queue='scheduler', durable=True) # make sure the queue used by the error handler exist and durable
    channel.queue_bind(exchange=exchangename, queue='scheduler', routing_key='*.scheduler') # make sure the queue is bound to the exchange
    channel.basic_publish(exchange=exchangename, routing_key="payment.scheduler", body=message,
        properties=pika.BasicProperties(delivery_mode = 2) # make message persistent within the matching queues until it is received by some receiver (the matching queues have to exist and be durable and bound to the exchange)
    )
    print("Trip details sent to Payment microservice.")
    # close the connection to the broker
    connection.close()
    return ""




#function to get facebookID (get from session and store in database)
#function to get all trips of a specific fb user (retrieve)
#function (GET) places of interest from user's selected POI- placeOfInterest {POI: name, address}
#function (GET) start date & end date - calendar startDate, endDate
#Create trip into scheduler database and POST TO UI 



#function to create: SEND (POST)

if __name__ == '__main__':
    app.run(port=5002, debug=True) 