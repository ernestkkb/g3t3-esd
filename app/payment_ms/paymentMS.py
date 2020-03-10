from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS

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

@app.route("/payment")
def get_all():
    return jsonify({"payment_process": [payment_process.json() for payment_process in Payment.query.all()]})


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

@app.route("/payment/update/<string:tripID>")
def update_trip_status(tripID):
    payment = Payment.query.filter_by(tripID=tripID).first()
    if payment:
        stmt = Payment.update().\
            where(Payment.tripID==payment.tripID).\
            values(paymentStatus='paid')
        return jsonify(payment.json())
    return jsonify({"message": "Trip not found."}), 404


if __name__ == '__main__':
    #port=5000 - location search
    #port=5001 - package reccomendation 
    #port=5002 - scheduler
    #port=5003 - payment
    #port=5004 - notifications
    app.run(port=5003, debug=True) 
    #with app.run it will allow the system call the name without required flask
    #with __name__ == '__main__' it will start flask to listen to request
    # we specify the port to use is 5000 (which is the default port anyway) and set debug to True, 
    # which will provide debugging information and also restart the app automatically if the code is modified while the app is running. 
    # the thing that needs to be change is the PORT 
