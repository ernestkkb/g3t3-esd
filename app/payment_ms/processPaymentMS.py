# from flask import Flask, request, jsonify
# from flask_sqlalchemy import SQLAlchemy
# from sqlalchemy import update
# from flask_cors import CORS
# import json
# import sys
# import os
# import random
# import datetime
# import pika
# import paypalrestsdk

# def receiveTripDetails():
#     print("test1")
#     hostname = "localhost" # default host
#     port = 5672 # default port
#     # connect to the broker and set up a communication channel in the connection
#     connection = pika.BlockingConnection(pika.ConnectionParameters(host=hostname, port=port))
#     channel = connection.channel()

#     # set up the exchange if the exchange doesn't exist
#     exchangename="exchange_topic"
#     channel.exchange_declare(exchange=exchangename, exchange_type='topic')

#     # prepare a queue for receiving messages
#     channelqueue = channel.queue_declare(queue='scheduler', durable=True) # '' indicates a random unique queue name; 'exclusive' indicates the queue is used only by this receiver and will be deleted if the receiver disconnects.
#         # If no need durability of the messages, no need durable queues, and can use such temp random queues.
#     queue_name = channelqueue.method.queue
#     channel.queue_bind(exchange=exchangename, queue=queue_name, routing_key='*.scheduler') # bind the queue to the exchange via the key
#         # any routing_key would be matched

#     # set up a consumer and start to wait for coming messages
#     channel.basic_consume(queue=queue_name, on_message_callback=callback, auto_ack=True)
#     channel.start_consuming() # an implicit loop waiting to receive messages; it doesn't exit by default. Use Ctrl+C in the command window to terminate it.

# def callback(channel, method, properties, body): # required signature for the callback; no return
#     print("Received an Trip details by ")
#     get_trip_payment_details(body["tripID"])
#     payment(json.loads(body))
#     print() # print a new line feed

# def get_trip_payment_details(tripID):
#     if (Payment.query.filter_by(tripID=tripID).first()):
#         return jsonify({"message": "A trip with Trip ID '{}' already exists.".format(tripID)}), 400

#     data = request.get_json()
#     payment = Payment(tripID, **data)

#     try:
#         db.session.add(payment)
#         db.session.commit()
#     except:
#         return jsonify({"message": "An error occurred creating the trip payment."}), 500

#     return jsonify(payment.json()), 201

# def payment(triplist):
#     total=0
#     for dict1 in triplist:
#         for key in dict1:
#             if key=="price":
#                 total+=float(dict1["price"])*int(dict1["quantity"])
    
#     payment = paypalrestsdk.Payment({
#         "intent": "sale",
#         "payer": {
#             "payment_method": "paypal"},
#         "redirect_urls": {
#             "return_url": "http://localhost:5003/payment/execute",
#             "cancel_url": "http://localhost:5003/"},
#         "transactions": [{
#             "item_list": {
#                 "items": triplist},
#             "amount": {
#                 "total": str(total),
#                 "currency": "USD"},
#             "description": "This is the payment transaction description."}]})

#     if payment.create():
#         print('Payment success!')
#     else:
#         print(payment.error)


#     serviceURL = "http://localhost:5003/payment/"
#     return jsonify({'paymentID' : payment.id})

