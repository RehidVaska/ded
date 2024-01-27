import asyncio
import signal
import aiohttp
from telegram.ext import Application
from telegram.ext import MessageHandler, CallbackQueryHandler
from telegram.ext import filters

TELEGRAM_TOKEN = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U'
TELEGRAM_CHAT_ID = '-4151741751'

async def echo(update, context):
    if update.message:
        await update.message.reply_text(update.message.text)
    else:
        print("Primljeno ažuriranje koje ne sadrži poruku.")

async def button_callback(update, context):
    query = update.callback_query
    await query.answer()
    callback_parts = query.data.split('-')
    action = callback_parts[0]
    form_uid = callback_parts[1] if len(callback_parts) > 1 else None
    print(f"Action: {action}, form_uid from Telegram Bot: {form_uid}")
    payload = {'odgovor': action, 'form_uid': form_uid}
    async with aiohttp.ClientSession() as session:
        try:
            async with session.post('http://127.0.0.1:5000/api/telegram-callback', json=payload) as response:
                if response.status == 200:
                    print(f'Korisnik je odgovorio {action} za UID {form_uid}')
                else:
                    print(f'Error posting to Flask app: {await response.text()}')
        except aiohttp.ClientError as e:
            print(f'HTTP request failed: {e}')


def main():
    application = Application.builder().token(TELEGRAM_TOKEN).build()
    application.add_handler(MessageHandler(filters.TEXT, echo))
    application.add_handler(CallbackQueryHandler(button_callback))
    application.run_polling()

if __name__ == '__main__':
    main()