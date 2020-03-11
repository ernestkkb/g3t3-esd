from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS

app = Flask(__name__)

#change link to own database directory
# our database is called scheduler and table is scheduler

app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/scheduler'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

#function (GET) places of interest from user's selected POI- placesOfInterest {POI: name, address}
#function (GET) start date & end date - calendar startDate, endDate
#Store trip into scheduler database and POST TO UI 
#

#function to create: SEND (POST)

db = SQLAlchemy(app)
CORS(app)




if __name__ == '__main__':
    app.run(port=5002, debug=True) 