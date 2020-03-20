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

def full_POI_url(city, API_KEY):
    from urllib.parse import urlencode
    mydict = {'query': city}
    POI_URL = "https://maps.googleapis.com/maps/api/place/textsearch/json?"
    url = POI_URL + urlencode(mydict, doseq=True) 
    url = url + "point+of+interest&language=en&key=" + API_KEY
    return url

def filtered_POI_url(place_id, API_KEY):
    from urllib.parse import urlencode
    fields = "name,formatted_address,geometry,rating,photos[]"
    mydict = {'place_id': place_id}
    POI_URL = "//maps.googleapis.com/maps/api/place/details/json?"
    url = POI_URL + urlencode(mydict, doseq=True) 
    url = url + "&fields=" + fields + '&key=' + API_KEY
    return url

final_url = full_POI_url(city,API_KEY)
response = urllib.request.urlopen(final_url)
data = json.loads(response.read())

#post this results data either back to add_trip.php / to fetch.html
#retrieve place_ids to get the full info out 
full_list = data['results']
place_ids= []
for each_place in full_list:
    place_ids.append(each_place['id'])

details_list = []
for pid in place_ids:
    full_ans = filtered_POI_url(pid, API_KEY)
    response = urllib.request.urlopen(final_url)
    data = json.loads(response.read())
    
    



