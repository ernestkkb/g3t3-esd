from flask import Flask, jsonify
from flask_cors import CORS
import requests
import urllib
import urllib.request
import json
app = Flask(__name__)
CORS(app)

#define our api key
API_KEY = "AIzaSyCc4-LbdsmzRIHaqyO5fmAg0ew6HuC1eL4"

#definecity (retrieved from add_trip.php)
city = "singapore"

def url_create(city, API_KEY):
    from urllib.parse import urlencode
    mydict = {'query': city}
    POI_URL = "https://maps.googleapis.com/maps/api/place/textsearch/json?"
    url = POI_URL + urlencode(mydict, doseq=True) 
    url = url + "point+of+interest&language=en&key=" + API_KEY
    return url

final_url = url_create(city,API_KEY)
response = urllib.request.urlopen(final_url)
data = json.loads(response.read())
print(data['results'])
