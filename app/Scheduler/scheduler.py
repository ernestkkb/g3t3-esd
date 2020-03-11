from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS

app = Flask(__name__)

#change link to own database directory
# our database is called scheduler and table is scheduler

app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/scheduler'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False


class scheduler(db.Model):
    __tablename__ = 'scheduler'

    tripID = db.Column(db.Integer(4), primary_key=True)
    facebookID = db.Column(db.Varchar(20), nullable=False)
    placesOfInterest = db.Column(db.json, nullable=False)
    startDate = db.Column(db.date, nullable=False)
    endDate = db.Column(db.date, nullable=False))
    paymentStatus = db.Column(db.Varchar(10))

    def __init__(self, tripID, facebookID, placesOfInterest, startDate, endDate, paymentStatus):
        self.tripID = tripID
        self.facebookID = facebookID
        self.placesOfInterest = placesOfInterest
        self.startDate = startDate
        self.endDate = endDate
        self.paymentStatus = paymentStatus

    def json(self):
        return {"tripID": self.tripID, "facebookID": self.facebookID, "placesOfInterest": self.placesOfInterest, "startDate": self.startDate, "endDate": self.endDate, "paymentStatus": self.paymentStatus}

#function to get facebookID (get from session and store in database)
#function to get all trips of a specific fb user (retrieve)
#function (GET) places of interest from user's selected POI- placesOfInterest {POI: name, address}
#function (GET) start date & end date - calendar startDate, endDate
#Store trip into scheduler database and POST TO UI 
#

#function to create: SEND (POST)

db = SQLAlchemy(app)
CORS(app)

if __name__ == '__main__':
    app.run(port=5002, debug=True) 