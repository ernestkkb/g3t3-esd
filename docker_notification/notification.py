from flask import Flask,request, render_template, redirect, url_for, flash
from flask_mail import Mail, Message
import os,sys
import json
import psycopg2
from os import environ
import urllib.parse
from urllib.parse import urlparse
import pika 

app = Flask(__name__)
app.config['SECRET_KEY'] = 'top-secret!'
app.config['MAIL_SERVER'] = 'smtp.sendgrid.net'
app.config['MAIL_PORT'] = 587
app.config['MAIL_USE_TLS'] = True
app.config['MAIL_USERNAME'] = 'apikey'
app.config['MAIL_PASSWORD'] = "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"
app.config['MAIL_DEFAULT_SENDER'] = "w4schoolteam@gmail.com"
mail = Mail(app)

@app.route("/notification/email/<string:emailAddress>",methods=["POST"])
def sendemail(emailAddress):
    if request.is_json:
        content = request.get_json()
    else:
        content = request.get_data()  

    content = json.loads(content.decode("utf-8"))
    body = "Day, POIs \n"
    dictionaryOfStuff = {}
    for i in content['details']:
        if i['day'] not in dictionaryOfStuff:
            dictionaryOfStuff[i['day']] = ""
        dictionaryOfStuff[i['day']] += i['placeOfInterest']['name'] + ">"
    for i in dictionaryOfStuff:
        dictionaryOfStuff[i] = dictionaryOfStuff[i][:-1]
    for i in dictionaryOfStuff:
        body+= str(i) +":" + dictionaryOfStuff[i] +"\n"

    msg = Message('Thanks for travelling with us!', recipients=[emailAddress])
    msg.body = ("G3T3 one Stop Travel Application")
    msg.html = (body)
    mail.send(msg)
    with app.app_context():
        msg = Message(subject="Thank you for travelling with us!",
                    recipients=[emailAddress], 
                    body=body)
    return "YAY"

if __name__ == '__main__':
    port = int(os.environ.get('PORT', 5000))
    app.run(host='0.0.0.0', port=port, debug=False)






