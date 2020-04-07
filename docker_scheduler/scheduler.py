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
import psycopg2
from os import environ


app = Flask(__name__)
#change link to own database directory
# our database is called scheduler and table is scheduler
# app.config['SQLALCHEMY_DATABASE_URI'] = environ.get('dbURL')
app.config['SQLALCHEMY_DATABASE_URI']="postgres://icootzgddbwwoz:d855a7dde5783ae534775fe61e19a798ee02e89f5165e2daec37c821afc0751f@ec2-34-204-22-76.compute-1.amazonaws.com:5432/d8nsl50eu81ssv"
# app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/scheduler'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
CORS(app)
class package(db.Model):
    __tablename__ = 'package'

    id = db.Column(db.Integer(), primary_key=True)
    tripname = db.Column(db.String(44))
    placeofinterest = db.Column(db.JSON, nullable=True)
    day = db.Column(db.Integer())
    tripid = db.Column(db.String(20), nullable=True)

    def __init__(self, id, tripname, placeofinterest, day, tripid):
        self.id = id
        self.tripname = tripname
        self.placeofinterest = placeofinterest
        self.day = day
        self.tripid = tripid

    def json(self):
        return {"tripID": self.tripid, "tripName": self.tripname, "placeOfInterest": self.placeofinterest,  "day":self.day, "id":self.id}

class scheduler(db.Model):
    __tablename__ = 'scheduler'

    id = db.Column(db.Integer(), primary_key=True)
    tripname = db.Column(db.String(44))
    facebookid = db.Column(db.String(20), nullable=True)
    placeofinterest = db.Column(db.JSON, nullable=True)
    # startDate = db.Column(db.Date, nullable=True)
    # endDate = db.Column(db.Date, nullable=True)
    paymentstatus = db.Column(db.String(10))
    day = db.Column(db.Integer())
    tripid = db.Column(db.String(20), nullable=True)

    def __init__(self, id, tripname, facebookid, placeofinterest, paymentstatus,day, tripid):
        self.id = id
        self.tripname = tripname
        self.facebookid = facebookid
        self.placeofinterest = placeofinterest
        # self.startDate = startDate
        # self.endDate = endDate
        self.paymentstatus = paymentstatus
        self.day = day
        self.tripid = tripid

    def json(self):
        return {"tripID": self.tripid, "tripName": self.tripname, "facebookID": self.facebookid, "placeOfInterest": self.placeofinterest, "paymentStatus": self.paymentstatus, "day":self.day, "id":self.id}

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
    port = int(os.environ.get('PORT', 5000))
    app.run(host='0.0.0.0',port=port, debug=False) 