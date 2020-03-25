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
# our database is called scheduler and table is scheduler

app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/scheduler'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False


db = SQLAlchemy(app)
CORS(app)

class scheduler(db.Model):
    __tablename__ = 'scheduler'

    id = db.Column(db.Integer(), primary_key=True)
    tripID = db.Column(db.Integer())
    facebookID = db.Column(db.String(20), nullable=False)
    placesOfInterest = db.Column(db.JSON, nullable=False)
    startDate = db.Column(db.Date, nullable=False)
    endDate = db.Column(db.Date, nullable=False)
    paymentStatus = db.Column(db.String(10))
    day = db.Column(db.Integer())

    def __init__(self, id, tripID, facebookID, placesOfInterest, startDate, endDate, paymentStatus):
        self.id = id
        self.tripID = tripID
        self.facebookID = facebookID
        self.placesOfInterest = placesOfInterest
        self.startDate = startDate
        self.endDate = endDate
        self.paymentStatus = paymentStatus
        self.day = day

    def json(self):
        return {"id": self.id, "tripID": self.tripID, "facebookID": self.facebookID, "placesOfInterest": self.placesOfInterest, "startDate": self.startDate, "endDate": self.endDate, "paymentStatus": self.paymentStatus, "day":self.day}


#retrieve all trips of one user - GET
@app.route("/scheduler/<string:facebookID>")
def get_all_from_user(facebookID):
    trips = scheduler.query.filter_by(facebookID=facebookID).first()
    if trips:
        return jsonify(trips.json())
    #if facebookID is not found, return this error message 
    return jsonify({'message': "facebook user not found."}), 404

#retrieve trip by trip id - GET
@app.route("/scheduler/<string:tripID>")
def find_by_tripid(tripID):
    trip = scheduler.query.filter_by(tripID=tripID).first()
    if trip:
        return jsonify(trip.json())
    return jsonify({"message": "Trip not found."}), 404

#retrieve trip by tripid, facebookID, day. This function is to get data for google directions
@app.route("/scheduler/<string:tripID>/<string:facebookID>/<string:day>")
def find_for_routing(tripID, facebookID, day):
    trip = scheduler.query.filter_by(tripID=tripID, facebookID=facebookID, day=day)
    if trip:
        return jsonify(trip.json())
    return jsonify({"message": "Trip not found."}), 404

#create trip - POST
@app.route("/scheduler/<string:tripID>", methods=['POST'])
def create_trip(tripID):
    if (scheduler.query.filter_by(tripID=tripID).first()):
        return jsonify({"message": "A trip with tripID '{}' already exists.".format(tripID)}), 400

    data = request.get_json()
    trip = scheduler(tripID, **data)

    try:
        db.session.add(trip)
        db.session.commit()
    except:
        return jsonify({"message": "An error occurred creating the trip."}), 500

    return jsonify(trip.json()), 201

@app.route('/makepayment', methods=['POST'])
def forward_trip():
    if request.is_json:
        triplist = request.get_json()
    else:
        triplist = request.get_data()
        replymessage = json.dumps({"message": "Order should be in JSON", "data": triplist}, default=str)
        return replymessage, 400 # Bad Request

    # triplist=[{
    #                 "name": tripName,
    #                 "sku": "Trip ID: "+tripID,
    #                 "price": price,
    #                 "currency": currency,
    #                 "quantity": quantity}]

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
#function (GET) places of interest from user's selected POI- placesOfInterest {POI: name, address}
#function (GET) start date & end date - calendar startDate, endDate
#Create trip into scheduler database and POST TO UI 



#function to create: SEND (POST)

if __name__ == '__main__':
    app.run(port=5002, debug=True) 