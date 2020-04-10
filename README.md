# One-Stop Travel Planner by G3T3

Existing travel planners today are not comprehensive and customisable enough. Our solution is a one-stop online travel platform where users can plan their travel routes according to their preferences.

## Getting Started
To get a copy of the project up and running on your local machine for development and testing purposes, download the .zip file provided

### Prerequisites

- An internet connection
- A Facebook Account
- A PayPal Account
- Allow notifications for pages visited

Reason: Our application has been containerised using Docker and is composed and deployed on Heroku Cloud. To test our application, simply follow this link [HerokuLink](https://g3t3-ui.herokuapp.com) 

***Note: You may use the following dummy account for testing purposes***
```
PayPal
- Username: sb-tkny31173509@personal.example.com
- Password: 9-&aVnbs
```

## Main Screens
1. Login page
2. Homepage (Search & Premade Packages)
3. Places Of Interest (POI) page 
4. Summary page for user’s trips
5. Notification Page
6. Payment Page

## Microservices
1. Location search for countries/cities
   * g3t3-esd\app\search_ms\search.py
2. Scheduler
   * g3t3-esd\app\scheduler_ms\scheduler.py
3. Payment 
   * g3t3-esd\app\payment_ms\paymentMS.py
   * g3t3-esd\app\payment_ms\paymentAMQP.py
4. Notifications 
   * g3t3-esd\app\notification_ms\notification.py
5. Places 
   * g3t3-esd\app\places_ms\placesapi.py

![tech_overview](./images_for_md/tech_overview.jpg)

## Running the User Scenarios / UAT
1. Login & Adding Package Recommendations
   - Login
     - Visit https://g3t3-ui.herokuapp.com/ 
     - Click on "Sign in with Facebook" button
     - Click on "Continue as {your_name} " button
     - Click on "Sign in with Facebook" button

   - Adding Package Recommendations
     - Scroll to the bottom of the page
     - Click "View" button on "Australia Melbourne" package
     - Click on "Submit" button
     - Click on "OK" alert button at the top of the page
     - Check that the package has been added at the bottom of the page
  
2. Customising Trips & Viewing all Trips

   * Customising Trips
     * Click on Home in the navigation bar
     * Click on "Country" drop-down list
     * Select "Canada"
     * Click on "City" drop-down list
     * Select "Toronto, Canada"
     * Click on "Submit" button
     * Fill in the "Trip Name" text-box with "Test"
     * Fill in the "Number of days" text-box with "3"
     * Confirm that the City text-box is "Toronto, Canada"
     * Click on "Find Places of Interest" button
     * Wait for the page to load
     * Confirm that the table is populated
       * POI S.Ns
       * Respective Names
       * Respective Addresss
       * Respective Photos
       * Respective Ratings
     * Scroll to the bottom of the page
     * Fill in "POI S.N." text-box with "20"
     * Fill in "DAY CHOSEN" text-box with "1"
     * Click on "Add POI" button
       * Confirm that a notification "POI Added" appears on your screen (for Windows on the bottom-right)
       * You may add more POI's to the respective days
     * Click on "Add Trip" Button
       * Confirm a notification "Trip Added" appears on your screen (for Windows on the bottom-right)
     * Click on "Take me to my trips" button
  
   * Viewing all Trips 
     * Confirm that all trips added are shown upon clicking on the "Take me to my trips" button

3. Route planning
   * *Prerequiste - Step 1 of this UAT*
   * Assuming that Step 1 was done
   * Click on "View Route" for Day 1 of the "Australia, Melbourne" trip
   * Click on "Start" drop-down list
   * Select "Melbourne Meuseum"
   * Click on "End" drop-down list
   * Select "Queen Victoria Market" 
   * Click on "Submit" button
   * Confirm in the text-box under "Submit" button is populated with addresses of POIs
     * Route Segment: 1 11 Nicholson St, Carlton VIC 3053, Australia to 11 Nicholson St, Carlton VIC 3053, Australia 1 m
     * Route Segment: 2 11 Nicholson St, Carlton VIC 3053, Australia to Royal Botanic Gardens Victoria - Melbourne Gardens, Birdwood Ave, South Yarra VIC 3141, Australia 3.7 km
     * Route Segment: 3 Royal Botanic Gardens Victoria - Melbourne Gardens, Birdwood Ave, South Yarra VIC 3141, Australia to Queen Victoria Market, Melbourne VIC 3000, Australia 5.1 km
    * Confirm on the left, the Google Maps has been populated with the addresses
      * Marker B corresponds to 11 Nicholson St, Carlton VIC 3053, Australia
      * Marker C corresponds to Royal Botanic Gardens Victoria - Melbourne Gardens, Birdwood Ave, South Yarra VIC 3141, Australia
      * Marker D corresponds to Queen Victoria Market, Melbourne VIC 3000, Australia
   * To exit the page, go back a webpage

4. Payment & Notification
   * *Prerequiste - Step 1 of this UAT*

   * Payment
     * Click on "View Payment Details"
     * Confirm that "Checkout" table is populated
       * \# : 1
       * Name of Trip : Australia Melbourne
       * Price : $20
       * Quantity : 1
       * Checkout : Paypal button present
     * Click on "PayPal Checkout" button
     * Confirm that PayPal pop-up appears
     * Log in to PayPal using the dummy account provided
       * Username: sb-tkny31173509@personal.example.com
       * Password: 9-&aVnbs
     * Click on "Log In" button
     * Confirm that the price is correct
       * $20.00 SGD == $15.12 USD
     * Select "Balance" radio button.
     * Click on "Pay Now" button
   * Notification
     * Scroll to the bottom of the page
     * Fill in the "Email Address" text-box with your own email address
     * Click on "Submit" button
     * Click on "OK" alert button at the top of the page
     * Wait to receive the email (~ 1 to 2 minutes)
     * Check your Junk Email
     * Confirm that a confirmation email has been sent to you

## Project Directory Structure
*Note: Only the most critical files to our application are explicitly show. The Python files implementing the microservices have been marked with 3 asterisks \*\*\**
```bash
📦g3t3-esd
 ┣ 📂.git
 ┣ 📂.idea
 ┣ 📂app
 ┃ ┣ 📂css
 ┃ ┣ 📂js
 ┃ ┃ ┗ 📜homepage.js
 ┃ ┣ 📂notification_ms
 ┃ ┃ ┗ 📜notification.py ***
 ┃ ┣ 📂package
 ┃ ┃ ┣ 📜package_nonav.php
 ┃ ┃ ┗ 📜package_view.php
 ┃ ┣ 📂payment_ms
 ┃ ┃ ┣ 📂css
 ┃ ┃ ┣ 📂js
 ┃ ┃ ┃ ┗ 📜payment.js
 ┃ ┃ ┣ 📜payment.php
 ┃ ┃ ┣ 📜paymentAMQP.py ***
 ┃ ┃ ┣ 📜paymentdisplay.php
 ┃ ┃ ┗ 📜paymentMS.py ***
 ┃ ┣ 📂places_ms
 ┃ ┃ ┗ 📜placesapi.py ***
 ┃ ┣ 📂scheduler_ms
 ┃ ┃ ┣ 📜add_trip.php
 ┃ ┃ ┣ 📜fetch.php
 ┃ ┃ ┣ 📜google_direction_sg.php
 ┃ ┃ ┣ 📜Logo_small.png
 ┃ ┃ ┣ 📜push.js
 ┃ ┃ ┣ 📜scheduler.py ***
 ┃ ┃ ┗ 📜serviceWorker.js
 ┃ ┣ 📂search_ms
 ┃ ┃ ┣ 📜googlemaps_sample.php
 ┃ ┃ ┣ 📜search.php
 ┃ ┃ ┣ 📜search.py ***
 ┃ ┃ ┗ 📜test_data.json
 ┃ ┣ 📂vendor
 ┃ ┃ ┣ 📂composer
 ┃ ┃ ┣ 📂facebook
 ┣ 📂databases
 ┃ ┣ 📜payment.sql
 ┃ ┣ 📜scheduler.sql
 ┃ ┣ 📜searchDB.sql
 ┃ ┗ 📜searchpage_city_country.xlsx
 ┣ 📂docker_notification
 ┃ ┣ 📜dockerfile
 ┃ ┣ 📜notification.py
 ┃ ┗ 📜requirements.txt
 ┣ 📂docker_payment
 ┃ ┣ 📜Dockerfile
 ┃ ┣ 📜paymentMS.py
 ┃ ┗ 📜requirements.txt
 ┣ 📂docker_paymentAMQP
 ┃ ┣ 📜docker-compose-PROD-build.bat
 ┃ ┣ 📜docker-compose-PROD-start.bat
 ┃ ┣ 📜docker-compose-PROD-stop.bat
 ┃ ┣ 📜docker-compose.yml
 ┃ ┣ 📜Dockerfile
 ┃ ┣ 📜paymentAMQP.py
 ┃ ┗ 📜requirements.txt
 ┣ 📂docker_placesapi
 ┃ ┣ 📜dockerfile
 ┃ ┣ 📜placesapi.py
 ┃ ┗ 📜requirements.txt
 ┣ 📂docker_scheduler
 ┃ ┣ 📜dockerfile
 ┃ ┣ 📜requirements.txt
 ┃ ┗ 📜scheduler.py
 ┣ 📂docker_search
 ┃ ┣ 📜dockerfile
 ┃ ┣ 📜requirements.txt
 ┃ ┗ 📜search.py
 ┣ 📂images_for_md
 ┣ 📜index.php
 ┗ 📜README.md
```
## Essential Python Modules
* flask
* flask_mail
* flask_cors
* requests
* flask_sqlalchemy
* pika
* paypalrestsdk
  
*Other dependencies not listed above may have been downloaded by the respective modules*

## Built With
* Website
  * HTML 
  * CSS - Beautifier
  * JavaScript - Dynamic website
  * Ajax - Asynchronous communication
  * Php - Http Protocol
* Server
  * AWS Cloud used by Heroku
* Database
  * MySQL
  * Posgres in Heroku
* Data
  * JSON
* Messaging-based communication
  * RabbitMQ
* Invocation-based communication
  * HTTP Protocol (POST/GET)
  * Function calls
* Containeriser 
  * Docker
* Composer
  * Heroku
* Documentation
  * Markdown
* 3rd Party APIs
  * Google Places API
  * Google Directions API
  * Facebook Graph API
  * PayPal API
  * SendGrip API

## Authors
| Student     | Email          |
| -------- | -------------- |
| Khoo Khim Boon Ernest | ernest.khoo.2018@sis.smu.edu.sg	 |
| Jaslyn Toh Lixuan | jaslyntoh.2018@sis.smu.edu.sg	 |
| Chua Wilson | wilson.chua.2018@sis.smu.edu.sg |
| Tan Chin Hoong | chtan.2018@sis.smu.edu.sg |
| Tan JiaLe Brennan | brennan.tan.2018@sis.smu.edu.sg |
| Sim Theen Cheng | tcsim.2018@sis.smu.edu.sg |

## Terms of Use
* Google APIs Terms of Service
* Facebook Platform Policy