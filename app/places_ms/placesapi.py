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
# city = "singapore"

@app.route("/city/<string:city>")
def processAndGetItems(city):
    final_url = full_POI_url(city,API_KEY)
    response = urllib.request.urlopen(final_url)
    data = json.loads(response.read())

    #post this results data either back to add_trip.php / to fetch.html
    #retrieve place_ids to get the full info out 
    full_list = data['results']
    place_ids= []
    for each_place in full_list:
        place_ids.append(each_place['place_id'])

    ALL = []
    for pid in place_ids:
        details_list = []
        full_ans = filtered_POI_url(pid, API_KEY)
        response = urllib.request.urlopen(full_ans)
        data = json.loads(response.read())
        results = data['result']
        formatted_address = results['formatted_address']
        name = results['name']
        if 'photos' in results:
            maxwidth = 400
            photos_full = results['photos']
            photos_links = []
            for info in photos_full:
                photo_ref = info['photo_reference']
                link = photos_url(API_KEY, photo_ref, maxwidth)
                photos_links.append(link)
        rating = results['rating']
        details_list.append(name)
        details_list.append(formatted_address)
        details_list.append(photos_links[0])
        details_list.append(rating)
        ALL.append(details_list)
    return json.dumps(ALL)

def full_POI_url(city, API_KEY):
    from urllib.parse import urlencode
    mydict = {'query': city}
    POI_URL = "https://maps.googleapis.com/maps/api/place/textsearch/json?"
    url = POI_URL + urlencode(mydict, doseq=True) 
    url = url + "+point+of+interest&language=en&key=" + API_KEY
    return url

def filtered_POI_url(place_id, API_KEY):
    from urllib.parse import urlencode
    fields = "name,formatted_address,photos,rating"
    mydict = {'place_id': place_id}
    POI_URL = "https://maps.googleapis.com/maps/api/place/details/json?"
    url = POI_URL + urlencode(mydict, doseq=True) 
    url = url + "&fields=" + fields + '&key=' + API_KEY
    return url

def photos_url(API_KEY, photo_reference, maxwidth):
    from urllib.parse import urlencode
    mydict = {'maxwidth': maxwidth, 'photoreference':photo_reference, 'key':API_KEY}
    photos_URL = "https://maps.googleapis.com/maps/api/place/photo?"
    url = photos_URL + urlencode(mydict, doseq=True) 
    return url


if __name__ == "__main__":
    app.run(port='5008', debug=True)