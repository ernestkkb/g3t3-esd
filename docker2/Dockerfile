FROM python:3
WORKDIR /usr/src/app
COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt
COPY ./paymentMS.py .
COPY ./paymentAMQP.py .
CMD [ "python","./paymentMS.py" ,"./paymentAMQP.py" ]