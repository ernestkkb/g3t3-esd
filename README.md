# g3t3-esd One-Stop Travel Planner
![use_of_docker](./images_for_md/front_cover.jpg)

## Click [here](https://github.com/ernestkkb/g3t3-esd.git) to view our Github repository

| Student     | Email          |
| -------- | -------------- |
| Khoo Khim Boon Ernest | ernest.khoo.2018@sis.smu.edu.sg	 |
| Jaslyn Toh Lixuan | jaslyntoh.2018@sis.smu.edu.sg	 |
| Chua Wilson | wilson.chua.2018@sis.smu.edu.sg |
| Tan Chin Hoong | chtan.2018@sis.smu.edu.sg |
| Tan JiaLe Brennan | brennan.tan.2018@sis.smu.edu.sg |
| Sim Theen Cheng | tcsim.2018@sis.smu.edu.sg |

## About 
Problem: Existing travel planners are not comprehensive & customisable enough

Our Solution: One-stop online travel platform where users can plan their travel route according to their preferences

![app_flow](./images_for_md/app_flow.jpg)

## Main Screens
1. Login page
2. Homepage (Search & Premade
3. Packages)
4. POI page 
5. Summary page for user’s trips
6. Notification Page
7. Payment Page

## Microservices and file location
1. Location search for countries/cities 
2. Scheduler
3. Payment
4. Notifications 
5. Places

![tech_overview](./images_for_md/tech_overview.jpg)

## User Scenarios 
1. login + add package
2. customise trip + view trips
3. route planning 
4. payment + notification

## Running our application
* Simply follow this link [HerokuLink](https://g3t3-ui.herokuapp.com)

**The following Python modules are used within our project**
* flask
* flask_mail
* flask_cors
* requests
* flask_sqlalchemy
* pika
* paypalrestsdk

## Beyond the Labs

### 1. Implementation of 3rd Party APIs
* Facebook Graph API
* Google Places API
* Google Directions API
* Paypal API


### 2. Docker
![use_of_docker](./images_for_md/use_of_docker.jpg)

* Docker was used to containerise the microservices with the relevant 

Shared requirements.txt across containers
```
blinker==1.4
certifi==2019.11.28
cffi==1.14.0
chardet==3.0.4
click==7.1.1
cryptography==2.8
dnspython==1.16.0
Flask==1.1.1
Flask-Cors==3.0.8
Flask-Mail==0.9.1
Flask-SQLAlchemy==2.4.1
idna==2.9
itsdangerous==1.1.0
Jinja2==2.11.1
joblib==0.14.1
MarkupSafe==1.1.1
mysql-connector-python==8.0.19
numpy==1.18.2
paypalrestsdk==1.13.1
pika==1.1.0
protobuf==3.6.1
psycopg2==2.8.5
pycparser==2.20
pyOpenSSL==19.1.0
requests==2.23.0
scikit-learn==0.22.2.post1
scipy==1.4.1
six==1.14.0
sklearn==0.0
SQLAlchemy==1.3.14
urllib3==1.25.8
Werkzeug==1.0.0
```
### 3. Heroku

# ERNEST HELP HERE








