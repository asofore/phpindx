from flask import Flask, request, jsonify
import requests

app = Flask(__name__)

# إعداد التوكن للتأكيد و الوصول
VERIFY_TOKEN = 'hardex'
ACCESS_TOKEN = '1193598685178284'

@app.route('/webhook', methods=['GET'])
def verify():
    if request.args.get('hub_verify_token') == VERIFY_TOKEN:
        return request.args.get('hub_challenge')
    else:
        return 'Invalid verification token', 403

@app.route('/webhook', methods=['POST'])
def webhook():
    data = request.get_json()
    
    try:
        sender_id = data['entry'][0]['messaging'][0]['sender']['id']
        message_text = data['entry'][0]['messaging'][0].get('message', {}).get('text')
        command = data['entry'][0]['messaging'][0].get('postback', {}).get('payload')
        
        if command:
            if command == 'followus':
                send_message(sender_id, 'Find all social media accounts here: https://shreateh.net/links')
            elif command == 'ytsubscribe':
                send_message(sender_id, 'Subscribe to my youtube and share with your friends: https://youtube.com/shreateh-net')

        elif message_text:
            buttons_text = 'Please choose an option:'
            buttons = [
                {
                    'type': 'postback',
                    'title': 'Follow us',
                    'payload': 'followus'
                },
                {
                    'type': 'postback',
                    'title': 'Subscribe',
                    'payload': 'ytsubscribe'
                }
            ]
            send_message_buttons(sender_id, buttons_text, buttons)

    except Exception as e:
        print(f"Error: {e}")
    
    return jsonify(status='ok')

def send_message(recipient_id, message_text):
    url = f'https://graph.facebook.com/v12.0/me/messages?access_token={ACCESS_TOKEN}'
    message_data = {
        'recipient': {'id': recipient_id},
        'message': {'text': message_text}
    }
    requests.post(url, json=message_data)

def send_message_buttons(recipient_id, message_text, buttons):
    url = f'https://graph.facebook.com/v12.0/me/messages?access_token={ACCESS_TOKEN}'
    message = {
        'attachment': {
            'type': 'template',
            'payload': {
                'template_type': 'button',
                'text': message_text,
                'buttons': buttons
            }
        }
    }
    data = {
        'recipient': {'id': recipient_id},
        'message': message
    }
    requests.post(url, json=data)

if __name__ == '__main__':
    app.run(host="0.0.0.0")  # يمكنك تغيير المنفذ إذا لزم الأمر
