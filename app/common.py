
def importStuff():
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