from flask import Flask,request
from flask_mail import Mail, Message
import os,sys
import json
import psycopg2
from os import environ
app = Flask(__name__)

mail_settings = {
    "MAIL_SERVER": 'smtp.gmail.com',
    "MAIL_PORT": 465,
    "MAIL_USE_TLS": False,
    "MAIL_USE_SSL": True,
    "MAIL_USERNAME": 'dtan342@gmail.com',
    "MAIL_PASSWORD": 'Constiislyf3#'
}

@app.route("/notification/email/<string:emailAddress>",methods=["POST"])
def send_email(emailAddress):
    if request.is_json:
        content = request.get_json()
    else:
        content = request.get_data()    
    print(type(content.decode("utf-8")))
    content = json.loads(content.decode("utf-8"))
    print(content)
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
    print(body)
    app.config.update(mail_settings)
    mail = Mail(app)
    with app.app_context():
            msg = Message(subject="Hello",
                        sender=app.config.get("MAIL_USERNAME"),
                        recipients=[emailAddress], # replace with your email for testing
                        body=body)
            mail.send(msg)
    return "YAY"
if __name__ == '__main__':
    port = int(os.environ.get('PORT', 5000))
    app.run(host='0.0.0.0', port=port, debug=False)

