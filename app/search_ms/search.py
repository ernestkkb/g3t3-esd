from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS

app = Flask(__name__) # initialise a Flask application
app.config['SQLALCHEMY_DATABASE_URI'] =  'mysql+mysqlconnector://root@localhost:3306/searchdb'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False # Disabled as consumes memory, and not required

db = SQLAlchemy(app) # initialise a connection to the db, in the variable db. Use to interact with database
CORS(app)

class Location(db.Model): # Book class inherits from basic database model provided by SQLAlchemy. This also makes SQLAlchemy create a table called book (if we run db.create_all() function), which will be used to store our Book objects
    __tablename__ = 'country_city' # explicitly specify the table name as 'book'

    country = db.Column(db.String(50), nullable=False) # specify attributes. SQLAlchemy will use these as column names in the table.
    city = db.Column(db.String(50), primary_key=True, nullable=False)

    def __init__(self, country, city): # specifying the properties of Location when created
        self.country = country
        self.city = city

    def json(self):
        return {"country": self.country, "city": self.city}

@app.route("/locations")
def get_all():
    return jsonify({"locations": [location.json() for location in Location.query.all()]}) # SQLAlchemy provides a query method to retrive all records of Location

if __name__ == '__main__':
    app.run(port=5000, debug=True)
