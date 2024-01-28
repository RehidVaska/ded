from flask import Flask, request, session
import telegram

app = Flask(__name__)
app.secret_key = 'your_secret_key'
bot_token = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U'
chat_id = '-4104959417'
bot = telegram.Bot(token=bot_token)

@app.route('/checkout')
def index():
    return "Dobrodošli na stranicu!"

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
