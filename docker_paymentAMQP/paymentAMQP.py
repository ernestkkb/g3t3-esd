# from common import *
# importStuff()

from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import update
from flask_cors import CORS
import json
import sys
import os
import random
import datetime
import pika
import paypalrestsdk
import requests
import urllib.parse
from urllib.parse import urlparse

url_str = os.environ.get('CLOUDAMQP_URL', 'amqp://guest:guest@localhost//')
url = urlparse(url_str)
params = pika.ConnectionParameters(host=url.hostname, virtual_host=url.path[1:],
credentials=pika.PlainCredentials(url.username, url.password))

paymentURL = "https://g3t3-payment.herokuapp.com/makepayment"
connection = pika.BlockingConnection(params)
channel = connection.channel()
# set up the exchange if the exchange doesn't exist
exchangename="exchange_topic"
channel.exchange_declare(exchange=exchangename, exchange_type='topic')
def receiveTripDetails():
    print("receiveTripDetails function triggered")


    # prepare a queue for receiving messages
    channelqueue = channel.queue_declare(queue='scheduler', durable=True) # '' indicates a random unique queue name; 'exclusive' indicates the queue is used only by this receiver and will be deleted if the receiver disconnects.
        # If no need durability of the messages, no need durable queues, and can use such temp random queues.
    queue_name = channelqueue.method.queue
    channel.queue_bind(exchange=exchangename, queue=queue_name, routing_key='*.scheduler') # bind the queue to the exchange via the key
        # any routing_key would be matched
    
    # set up a consumer and start to wait for coming messages
    

    channelqueue2 = channel.queue_declare(queue='payment.reply', durable=True) # '' indicates a random unique queue name; 'exclusive' indicates the queue is used only by this receiver and will be deleted if the receiver disconnects.
        # If no need durability of the messages, no need durable queues, and can use such temp random queues.
    queue_name2 = channelqueue2.method.queue
    channel.queue_bind(exchange=exchangename, queue=queue_name2, routing_key='*.reply') # bind the queue to the exchange via the key
    # any routing_key would be matched

    # set up a consumer and start to wait for coming messages
    channel.basic_consume(queue=queue_name2, on_message_callback=reply_callback, auto_ack=True)

    channel.basic_consume(queue=queue_name, on_message_callback=callback, auto_ack=True)
    channel.start_consuming() # an implicit loop waiting to receive messages; it doesn't exit by default. Use Ctrl+C in the command window to terminate it.

def callback(channel, method, properties, body): # required signature for the callback; no return
    print("Received Trip details")
    print(json.loads(body))
    requests.post(paymentURL,json=json.loads(body))
    print() # print a new line feed

    
def reply_callback(channel, method, properties, body): # required signature for the callback; no return
    print("Received a Trip ID")
    string1=json.loads(body)
    forward_tripID(string1["ID"])
    print() # print a new line feed

def forward_tripID(tripID):
    print("forward_tripID function triggered")
    """inform Notification microservice"""
    # default username / password to the borker are both 'guest'
    # hostname = "localhost" # default broker hostname. Web management interface default at http://localhost:15672
    # port = 5672 # default messaging port.
    # connect to the broker and set up a communication channel in the connection
    # connection = pika.BlockingConnection(pika.ConnectionParameters(host=hostname, port=port))
        # Note: various network firew
        # alls, filters, gateways (e.g., SMU VPN on wifi), may hinder the connections;
        # If "pika.exceptions.AMQPConnectionError" happens, may try again after disconnecting the wifi and/or disabling firewalls
    # channel = connection.channel()

    # set up the exchange if the exchange doesn't exist
    exchangename="exchange_topic"
    channel.exchange_declare(exchange=exchangename, exchange_type='topic')

    # prepare the message body content
    message = json.dumps(tripID, default=str) # convert a JSON object to a string

    channel.queue_declare(queue='payment', durable=True) # make sure the queue used by the error handler exist and durable
    channel.queue_bind(exchange=exchangename, queue='payment', routing_key='*.payment') # make sure the queue is bound to the exchange
    channel.basic_publish(exchange=exchangename, routing_key="scheduler.payment", body=message,
        properties=pika.BasicProperties(delivery_mode = 2) # make message persistent within the matching queues until it is received by some receiver (the matching queues have to exist and be durable and bound to the exchange)
    )
    channel.basic_publish(exchange=exchangename, routing_key="notification.payment", body=message,
        properties=pika.BasicProperties(delivery_mode = 2) # make message persistent within the matching queues until it is received by some receiver (the matching queues have to exist and be durable and bound to the exchange)
    )
    print("Trip ID sent to Scheduler & Notification microservice.")
    # close the connection to the broker
    # connection.close()
if __name__ == '__main__':
    #port=5000 - location search
    #port=5001 - package reccomendation 
    #port=5002 - scheduler
    #port=5003 - payment
    #port=5004 - notifications
    # app.run(port=5003, debug=True) 
    receiveTripDetails() #invoke the consume function 
    #with app.run it will allow the system call the name without required flask
    #with __name__ == '__main__' it will start flask to listen to request
    # we specify the port to use is 5000 (which is the default port anyway) and set debug to True, 
    # which will provide debugging information and also restart the app automatically if the code is modified while the app is running. 
    # the thing that needs to be change is the PORT 
