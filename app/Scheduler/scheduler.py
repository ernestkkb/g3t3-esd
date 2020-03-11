from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS

app = Flask(__name__)

#change link to own database directory
# our database is called scheduler and table is scheduler

app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/scheduler'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False


db = SQLAlchemy(app)
CORS(app)

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


#retrieve all trips of one user
@app.route("/scheduler/<string:facebookID>")
def get_all_from_user(facebookID):
    trips = scheduler.query.filter_by(facebookID=facebookID).first()
    if trips:
        return jsonify(trips.json())

#retrieve trip by trip id 
@app.route("/scheduler/<string:tripID>")
def find_by_tripid(tripID):
    trip = scheduler.query.filter_by(tripID=tripID).first()
    if trip:
        return jsonify(trip.json())
    return jsonify({"message": "Trip not found."}), 404

#create trip 
@app.route("/scheduler/<string: tripID>", methods=['POST'])
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

#function to get facebookID (get from session and store in database)
#function to get all trips of a specific fb user (retrieve)
#function (GET) places of interest from user's selected POI- placesOfInterest {POI: name, address}
#function (GET) start date & end date - calendar startDate, endDate
#Create trip into scheduler database and POST TO UI 



#function to create: SEND (POST)

if __name__ == '__main__':
    app.run(port=5002, debug=True) 