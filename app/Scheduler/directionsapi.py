from flask import Flask, jsonify
from flask_cors import CORS
import requests
import urllib, json

app = Flask(__name__)
CORS(app)

#define our origin, destination, waypoints
API_KEY = "AIzaSyCVh6H9I5mG7Y3nXkLzjRwKogIhhBhjVkw"

#way points should be a dictionary, list form
#waypoints_dict = { 'origin1': ['A','B', 'destination'], 'origin2': ['C','D','destination'] }

def get_waypoints_list(waypoints_dict):
    store = []
    for things in waypoints_dict:
        new_string = ''
        list_of_points = waypoints_dict[things]
        for i in range(len(list_of_points)-1):
            new_string += '%' + list_of_points[i] 
        
        store.append(new_string)
    return store

def url_create(origin, destination, waypoint_string, API_KEY):
    from urllib.parse import urlencode
    mydict = {'origin': origin , 'destination': destination }
    GMAPS_URL = "https://maps.googleapis.com/maps/api/directions/json?"
    url = GMAPS_URL + urlencode(mydict, doseq=True) 
    url = url +  '&waypoints=optimize:true' + waypoint_string + '&key=' + API_KEY
    return url

waypoint_string = get_waypoints_list(waypoints_dict)

url = url_create(origin, destination, waypoint_string, API_KEY)
response = urllib.urlopen(url)
data = json.loads(response.read())
print data


