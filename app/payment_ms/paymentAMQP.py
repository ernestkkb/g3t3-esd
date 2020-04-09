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

paymentURL = "https://g3t3-payment.herokuapp.com/makepayment"
#checkout trip for payment - step 1: consume trip details from scheduler MS 
hostname = "https://g3t3-payment.herokuapp.com/" # default host
port = 5672 # default port
# connect to the broker and set up a communication channel in the connection
connection = pika.BlockingConnection(pika.ConnectionParameters(host=hostname, port=port))
channel = connection.channel()

# set up the exchange if the exchange doesn't exist
exchangename="exchange_topic"
channel.exchange_declare(exchange=exchangename, exchange_type='topic')
def receiveTripDetails():
    print("receiveTripDetails function triggered")
    # # prepare a queue for receiving messages
    # channelqueue = channel.queue_declare(queue='scheduler', durable=True) # '' indicates a random unique queue name; 'exclusive' indicates the queue is used only by this receiver and will be deleted if the receiver disconnects.
    #     # If no need durability of the messages, no need durable queues, and can use such temp random queues.
    # queue_name = channelqueue.method.queue
    # channel.queue_bind(exchange=exchangename, queue=queue_name, routing_key='*.scheduler') # bind the queue to the exchange via the key
    #     # any routing_key would be matched
    
    # # set up a consumer and start to wait for coming messages

    channelqueue2 = channel.queue_declare(queue='payment.reply', durable=True) # '' indicates a random unique queue name; 'exclusive' indicates the queue is used only by this receiver and will be deleted if the receiver disconnects.
        # If no need durability of the messages, no need durable queues, and can use such temp random queues.
    queue_name2 = channelqueue2.method.queue
    channel.queue_bind(exchange=exchangename, queue=queue_name2, routing_key='*.reply') # bind the queue to the exchange via the key
    # any routing_key would be matched
    # set up a consumer and start to wait for coming messages
    channel.basic_consume(queue=queue_name2, on_message_callback=reply_callback, auto_ack=True)
    # channel.basic_consume(queue=queue_name, on_message_callback=callback, auto_ack=True)
    channel.start_consuming() # an implicit loop waiting to receive messages; it doesn't exit by default. Use Ctrl+C in the command window to terminate it.

# def callback(channel, method, properties, body): # required signature for the callback; no return
#     print("Received Trip details")
#     print(json.loads(body))
#     requests.post(paymentURL,json=json.loads(body))
#     print() # print a new line feed

    
def reply_callback(channel, method, properties, body): # required signature for the callback; no return
    print("Received a Trip ID")
    string1=json.loads(body)
    forward_tripID(string1["ID"])
    print() # print a new line feed

def forward_tripID(tripID):
    print("forward_tripID function triggered")
    """inform Notification microservice"""

    exchangename="exchange_topic"
    channel.exchange_declare(exchange=exchangename, exchange_type='topic')
    # prepare the message body content
    message = json.dumps(tripID, default=str) # convert a JSON object to a string

    channel.queue_declare(queue='payment', durable=True) # make sure the queue used by the error handler exist and durable
    channel.queue_bind(exchange=exchangename, queue='payment', routing_key='*.payment') # make sure the queue is bound to the exchange
    channel.basic_publish(exchange=exchangename, routing_key="notification.payment", body=message,
        properties=pika.BasicProperties(delivery_mode = 2) # make message persistent within the matching queues until it is received by some receiver (the matching queues have to exist and be durable and bound to the exchange)
    )
    print("Trip ID sent to Notification microservice.")
    # close the connection to the broker
    # connection.close()
if __name__ == '__main__':
    receiveTripDetails() #invoke the consume function 

