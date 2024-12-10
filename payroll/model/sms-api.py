import requests
import json

# Define the API URL and credentials
api_url = "https://app.philsms.com/api/v3/sms/send"
api_key = "867|GE04QUiYg1OjwjuAtjLXjuHshvTSaTKOJnjuf0NR"  # Replace with your API key
sender_id = "PhilSMS"  # Replace with your sender ID

# Define the recipient phone number and message
phone_number = "639533180925"
message = "This is a test message from PhilSMS API."

# Prepare the data payload
payload = {
    "recipient": phone_number,
    "sender_id": sender_id,
    "type": "plain",
    "message": message,
}

# Define headers
headers = {
    "Authorization": f"Bearer {api_key}",
    "Content-Type": "application/json",
    "Accept": "application/json",
}

# Send the POST request
response = requests.post(api_url, headers=headers, data=json.dumps(payload))

# Print the response from the API
print(response.status_code)
print(response.json())
