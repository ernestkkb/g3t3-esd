from flask import Flask,request
from flask_mail import Mail, Message
import os,sys

app = Flask(__name__)

mail_settings = {
    "MAIL_SERVER": 'smtp.gmail.com',
    "MAIL_PORT": 465,
    "MAIL_USE_TLS": False,
    "MAIL_USE_SSL": True,
    "MAIL_USERNAME": 'dtan342@gmail.com',
    "MAIL_PASSWORD": 'Constiislyf3#'
}

@app.route("/notification/email", methods = ['POST'])
def send_email():
    data = request.get_json(force = True)
    print(data)
    print(type(data))
    email = data['email']
    app.config.update(mail_settings)
    mail = Mail(app)
    with app.app_context():
            msg = Message(subject="Hello",
                        sender=app.config.get("MAIL_USERNAME"),
                        recipients=[email], # replace with your email for testing
                        body="Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum")
            mail.send(msg)
    return data["email"]
if __name__ == '__main__':
    app.run(port=5004, debug=True)

