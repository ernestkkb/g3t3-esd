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

@app.route("/retrieveAllTripID/<string:tripID>")
def retrieveAllTripID(tripID):
    details = package.query.filter_by(tripID=tripID).all()
    detailsToReturn = {"details" : [detail.json() for detail in details]}
    if detailsToReturn:
        print(jsonify(detailsToReturn))
        return jsonify(detailsToReturn)
    return jsonify({"message": "Trip not found."}), 404



@app.route("/retrieveByTripID/<string:tripID>/<string:facebookID>")
def retrieveByTripID(tripID,facebookID):
    details = scheduler.query.filter_by(tripID=tripID,facebookID=facebookID).order_by(scheduler.day).all()
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

if __name__ == '__main__':
    app.run(port=5002, debug=True) 