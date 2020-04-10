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

connection = pika.BlockingConnection(params)
channel = connection.channel()

# set up the exchange if the exchange doesn't exist
exchangename="exchange_topic"
channel.exchange_declare(exchange=exchangename, exchange_type='topic')
def receiveTripDetails2():
    print("Received tripID from paymentMS")
    channelqueue = channel.queue_declare(queue='notification.payment', durable=True) # '' indicates a random unique queue name; 'exclusive' indicates the queue is used only by this receiver and will be deleted if the receiver disconnects.
        # If no need durability of the messages, no need durable queues, and can use such temp random queues.
    queue_name = channelqueue.method.queue
    channel.queue_bind(exchange=exchangename, queue=queue_name, routing_key='*.payment') # bind the queue to the exchange via the key
    # any routing_key would be matched
    # set up a consumer and start to wait for coming messages
    channel.basic_consume(queue=queue_name, on_message_callback=callback, auto_ack=True)
    channel.start_consuming() # an implicit loop waiting to receive messages; it doesn't exit by default. Use Ctrl+C in the command window to terminate it.

def callback(channel, method, properties, body): # required signature for the callback; no return
    print("Trip payment is successful for Trip: " + json.loads(body))
    print() # print a new line feed

    updateschedulerDBURL = "https://g3t3-scheduler.herokuapp.com/update/"+body
    try: 
        r=requests.get(updateschedulerDBURL)
    except requests.exceptions.RequestException as e:
        print(e)
    print(r.status_code)
    print(r.text)

if __name__ == '__main__':
    receiveTripDetails2() #invoke the consume function 