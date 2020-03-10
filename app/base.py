from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS

app = Flask(__name__)

#change link to own database directory
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/book'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
CORS(app)

#Change to own Database
# class Book(db.Model):
#     __tablename__ = 'book'

#     isbn13 = db.Column(db.String(13), primary_key=True)
#     title = db.Column(db.String(64), nullable=False)
#     price = db.Column(db.Float(precision=2), nullable=False)
#     availability = db.Column(db.Integer)

#     def __init__(self, isbn13, title, price, availability):
#         self.isbn13 = isbn13
#         self.title = title
#         self.price = price
#         self.availability = availability

#     def json(self):
#         return {"isbn13": self.isbn13, "title": self.title, "price": self.price, "availability": self.availability}

@app.route("/book")
# def get_all():
#     return jsonify({"books": [book.json() for book in Book.query.all()]})


@app.route("/book/<string:isbn13>")
# def find_by_isbn13(isbn13):
#     book = Book.query.filter_by(isbn13=isbn13).first()
#     if book:
#         return jsonify(book.json())
#     return jsonify({"message": "Book not found."}), 404


@app.route("/book/<string:isbn13>", methods=['POST'])
# def create_book(isbn13):
#     if (Book.query.filter_by(isbn13=isbn13).first()):
#         return jsonify({"message": "A book with isbn13 '{}' already exists.".format(isbn13)}), 400

#     data = request.get_json()
#     book = Book(isbn13, **data)

#     try:
#         db.session.add(book)
#         db.session.commit()
#     except:
#         return jsonify({"message": "An error occurred creating the book."}), 500

#     return jsonify(book.json()), 201


if __name__ == '__main__':
    #port=5000 - location search
    #port=5001 - package reccomendation 
    #port=5002 - scheduler
    #port=5003 - payment
    #port=5004 - notifications
    app.run(port=5000, debug=True) 
    #with app.run it will allow the system call the name without required flask
    #with __name__ == '__main__' it will start flask to listen to request
    # we specify the port to use is 5000 (which is the default port anyway) and set debug to True, 
    # which will provide debugging information and also restart the app automatically if the code is modified while the app is running. 
    # the thing that needs to be change is the PORT 
