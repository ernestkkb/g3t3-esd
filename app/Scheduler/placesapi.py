from flask import Flask, jsonify
from flask_cors import CORS
import requests

app = Flask(__name__)
CORS(app)

#define our api key
API_KEY = "AIzaSyCc4-LbdsmzRIHaqyO5fmAg0ew6HuC1eL4"

#retrieved from add_trip.php
city = "singapore"
cityplus =  city.replace(" ", "+")
search_url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=" + cityplus + "point+of+interest&language=en&key=" + API_KEY
print(search_url)

@app.route('/placesapi')
def get_all_POI():
    POI = requests.get(search_url)
    return POI.json()

if __name__ == "__main__":
    app.run(debug=True)