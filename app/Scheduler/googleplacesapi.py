#RUN THE FOLLOWING COMMANDS in CMD
#pip install googlemaps
#pip install prettyprint

#VIDEO FOR REFERENCE: https://www.youtube.com/watch?v=kWncnBBxoJ4

#API KEY = AIzaSyCc4-LbdsmzRIHaqyO5fmAg0ew6HuC1eL4
from flask import Flask, render_template, jsonify
import requests
app = Flask(__name__)

#define our api key
API_KEY = "AIzaSyCc4-LbdsmzRIHaqyO5fmAg0ew6HuC1eL4"

# define our Search
#city should be passed in from brennan's calendar IO. 

#city = "new york"
#city_split = city.split()
#search_url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query="
#for split in city_split:
    #search_url += split + "+"
#search_url += "point+of+interest&language=en&key="
#search_url += API_KEY
#print(search_url)


search_url = "https://maps.googleapis.com/maps/api/place/textsearch/json"
details_url = "https://maps.googleapis.com/maps/api/place/details/json"


@app.route("/", methods=["GET"])
def retrieve():
    return render_template("add_trip.php")

@app.route("/sendRequest/<string:city>")
def results(city):
    search_payload = {"key":API_KEY, "city":city}
    searh_req = requests.get(search_url, params=search_payload)
    search_json = search_req.json()

    results = search_json["results"]

    for POI in results:
        place_id = search_json["place_id"]
        name = search_json["name"]
        photos = search_json['photos']
        rating = search_json["rating"]
        details_payload = {key = API_KEY, "place_id":place_id}
        details_resp = requests.get(details_url, params = details_payload)
        details_json = details_resp.json()
        address = details_json["result"]["formatted_address"]

        #return it back to the fking add_trip.php
        #populate the data retrieved into a fking table
    




        

    