Let me continue with the remaining Python programs:

### 96. Weather App with API (continued)
```python
            print(f"  Humidity: {day['humidity']:.1f}%")
            print(f"  Wind Speed: {day['wind_speed']:.1f} m/s")

def main():
    # Check for API key
    api_key = os.environ.get("OPENWEATHER_API_KEY")
    if not api_key:
        api_key = input("Enter your OpenWeatherMap API key (or set OPENWEATHER_API_KEY environment variable): ")
    
    # Create the weather app
    app = WeatherApp(api_key)
    
    print("===== Weather App =====")
    print("1. Get current weather by city")
    print("2. Get current weather by coordinates")
    print("3. Get 5-day forecast")
    
    choice = input("\nEnter your choice (1-3): ")
    
    try:
        if choice == "1":
            city = input("Enter city name: ")
            country = input("Enter country code (optional, press Enter to skip): ")
            
            if country:
                data = app.get_weather_by_city(city, country)
            else:
                data = app.get_weather_by_city(city)
            
            app.display_weather(data)
        
        elif choice == "2":
            lat = float(input("Enter latitude: "))
            lon = float(input("Enter longitude: "))
            data = app.get_weather_by_coordinates(lat, lon)
            app.display_weather(data)
        
        elif choice == "3":
            city = input("Enter city name: ")
            country = input("Enter country code (optional, press Enter to skip): ")
            
            if country:
                data = app.get_forecast(city, country)
            else:
                data = app.get_forecast(city)
            
            app.display_forecast(data)
        
        else:
            print("Invalid choice")
    
    except Exception as e:
        print(f"An error occurred: {e}")
    
    print("\nNote: This example requires the requests library:")
    print("pip install requests")
    print("\nYou also need an API key from OpenWeatherMap (https://openweathermap.org/api)")

if __name__ == "__main__":
    main()
```
Demonstrates working with weather APIs.

### 97. Telegram Bot
```python
"""
Simple Telegram Bot

This script creates a basic Telegram bot that can respond to 
different commands and messages.

Requirements:
- python-telegram-bot library: pip install python-telegram-bot

You'll need to get a bot token from BotFather on Telegram:
1. Open Telegram and search for @BotFather
2. Send /newbot command and follow instructions to create a new bot
3. BotFather will give you a token for your bot
"""

import logging
from telegram import Update, InlineKeyboardButton, InlineKeyboardMarkup
from telegram.ext import ApplicationBuilder, CommandHandler, MessageHandler, CallbackQueryHandler, ContextTypes, filters

# Enable logging
logging.basicConfig(
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    level=logging.INFO
)

# Bot token (replace with your own token from BotFather)
TOKEN = "YOUR_TELEGRAM_BOT_TOKEN"

async def start(update: Update, context: ContextTypes.DEFAULT_TYPE):
    """Send a message when the command /start is issued."""
    user = update.effective_user
    await update.message.reply_text(
        f"Hello {user.mention_html()}! I'm your bot assistant. "
        f"Use /help to see what I can do.",
        parse_mode='HTML'
    )

async def help_command(update: Update, context: ContextTypes.DEFAULT_TYPE):
    """Send a message when the command /help is issued."""
    help_text = """
Available commands:
/start - Start the bot
/help - Show this help message
/about - About this bot
/menu - Show interactive menu
/echo [text] - Echo back your text
"""
    await update.message.reply_text(help_text)

async def about_command(update: Update, context: ContextTypes.DEFAULT_TYPE):
    """Send information about the bot."""
    about_text = (
        "This is a simple Telegram bot created with Python and python-telegram-bot library.\n\n"
        "It demonstrates basic bot functionality such as command handling, "
        "message responses, and interactive buttons."
    )
    await update.message.reply_text(about_text)

async def echo(update: Update, context: ContextTypes.DEFAULT_TYPE):
    """Echo the user message."""
    # Check if the command has arguments
    if context.args:
        text = ' '.join(context.args)
        await update.message.reply_text(f"Echo: {text}")
    else:
        await update.message.reply_text(
            "Please provide some text after /echo command.\n"
            "Example: /echo Hello, world!"
        )

async def menu_command(update: Update, context: ContextTypes.DEFAULT_TYPE):
    """Display an interactive menu."""
    keyboard = [
        [
            InlineKeyboardButton("Option 1", callback_data='option1'),
            InlineKeyboardButton("Option 2", callback_data='option2')
        ],
        [
            InlineKeyboardButton("Option 3", callback_data='option3'),
            InlineKeyboardButton("Option 4", callback_data='option4')
        ]
    ]
    reply_markup = InlineKeyboardMarkup(keyboard)
    await update.message.reply_text("Please choose an option:", reply_markup=reply_markup)

async def button_callback(update: Update, context: ContextTypes.DEFAULT_TYPE):
    """Handle button presses."""
    query = update.callback_query
    await query.answer()  # Answer callback query to stop loading animation
    
    option_responses = {
        'option1': "You selected Option 1! This could show some information.",
        'option2': "You selected Option 2! This could perform some action.",
        'option3': "You selected Option 3! This could open a submenu.",
        'option4': "You selected Option 4! This could link to external content."
    }
    
    response = option_responses.get(query.data, "Unknown option selected.")
    await query.edit_message_text(text=response)

async def handle_message(update: Update, context: ContextTypes.DEFAULT_TYPE):
    """Handle regular text messages."""
    text = update.message.text.lower()
    
    # Simple responses based on message content
    if "hello" in text or "hi" in text:
        await update.message.reply_text("Hello there! How can I help you?")
    elif "bye" in text or "goodbye" in text:
        await update.message.reply_text("Goodbye! Have a nice day.")
    elif "thanks" in text or "thank you" in text:
        await update.message.reply_text("You're welcome!")
    elif "help" in text:
        await update.message.reply_text("Type /help to see available commands.")
    else:
        await update.message.reply_text(
            "I didn't understand that. Try using a command like /help."
        )

def main():
    """Start the bot."""
    print("Starting bot...")
    
    if TOKEN == "YOUR_TELEGRAM_BOT_TOKEN":
        print("Error: Please replace 'YOUR_TELEGRAM_BOT_TOKEN' with your actual bot token.")
        print("Get a token by talking to @BotFather on Telegram.")
        return
    
    # Create the Application
    application = ApplicationBuilder().token(TOKEN).build()
    
    # Add command handlers
    application.add_handler(CommandHandler("start", start))
    application.add_handler(CommandHandler("help", help_command))
    application.add_handler(CommandHandler("about", about_command))
    application.add_handler(CommandHandler("echo", echo))
    application.add_handler(CommandHandler("menu", menu_command))
    
    # Add callback query handler for button presses
    application.add_handler(CallbackQueryHandler(button_callback))
    
    # Add message handler for text messages
    application.add_handler(MessageHandler(filters.TEXT & ~filters.COMMAND, handle_message))
    
    # Start the Bot
    print("Bot is running! Press Ctrl+C to stop.")
    application.run_polling()

if __name__ == '__main__':
    try:
        main()
    except Exception as e:
        print(f"Error: {e}")
        print("Make sure you have installed python-telegram-bot:")
        print("pip install python-telegram-bot")
```
Demonstrates building a Telegram bot.

### 98. Face Detection with OpenCV
```python
"""
Face Detection using OpenCV

This script demonstrates basic face detection in images and webcam
using OpenCV's pre-trained Haar cascade classifiers.

Requirements:
- OpenCV: pip install opencv-python
"""

import cv2
import sys
import os

def detect_faces_in_image(image_path):
    """Detect and highlight faces in an image file."""
    # Load the input image
    image = cv2.imread(image_path)
    
    if image is None:
        print(f"Error: Could not read image file '{image_path}'")
        return
    
    # Convert to grayscale (required for face detection)
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    
    # Load the pre-trained face detector
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
    
    # Detect faces
    faces = face_cascade.detectMultiScale(
        gray,
        scaleFactor=1.1,
        minNeighbors=5,
        minSize=(30, 30),
        flags=cv2.CASCADE_SCALE_IMAGE
    )
    
    print(f"Found {len(faces)} faces!")
    
    # Draw rectangles around the faces
    for (x, y, w, h) in faces:
        cv2.rectangle(image, (x, y), (x + w, y + h), (0, 255, 0), 2)
    
    # Save the output image
    filename, ext = os.path.splitext(image_path)
    output_path = f"{filename}_faces{ext}"
    cv2.imwrite(output_path, image)
    print(f"Processed image saved to {output_path}")
    
    # Display the result
    cv2.imshow("Face Detection", image)
    cv2.waitKey(0)
    cv2.destroyAllWindows()

def detect_faces_in_webcam():
    """Detect faces in real-time using webcam."""
    # Initialize webcam
    video_capture = cv2.VideoCapture(0)
    
    if not video_capture.isOpened():
        print("Error: Could not open webcam")
        return
    
    # Load the face cascade
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
    
    print("Webcam face detection started. Press 'q' to quit.")
    
    while True:
        # Capture frame-by-frame
        ret, frame = video_capture.read()
        
        if not ret:
            print("Error: Failed to capture frame from webcam")
            break
        
        # Convert to grayscale
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        
        # Detect faces
        faces = face_cascade.detectMultiScale(
            gray,
            scaleFactor=1.1,
            minNeighbors=5,
            minSize=(30, 30),
            flags=cv2.CASCADE_SCALE_IMAGE
        )
        
        # Draw rectangles around faces
        for (x, y, w, h) in faces:
            cv2.rectangle(frame, (x, y), (x + w, y + h), (0, 255, 0), 2)
        
        # Display the resulting frame
        cv2.imshow('Face Detection', frame)
        
        # Exit on 'q' press
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break
    
    # Release resources
    video_capture.release()
    cv2.destroyAllWindows()

def main():
    print("===== Face Detection with OpenCV =====")
    print("1. Detect faces in an image file")
    print("2. Detect faces using webcam")
    
    choice = input("\nEnter your choice (1 or 2): ")
    
    try:
        if choice == "1":
            image_path = input("Enter path to image file: ")
            detect_faces_in_image(image_path)
        
        elif choice == "2":
            detect_faces_in_webcam()
        
        else:
            print("Invalid choice")
    
    except Exception as e:
        print(f"An error occurred: {e}")
    
    print("\nNote: This example requires OpenCV:")
    print("pip install opencv-python")

if __name__ == "__main__":
    main()
```
Demonstrates face detection with OpenCV.

### 99. Text-to-Speech
```python
"""
Text-to-Speech (TTS) with Python

This script demonstrates how to convert text to speech using both
pyttsx3 (offline) and gTTS (Google Text-to-Speech, online) libraries.

Requirements:
- pyttsx3: pip install pyttsx3
- gTTS: pip install gtts
- playsound: pip install playsound
"""

import os
import time
import tempfile
from datetime import datetime

def tts_pyttsx3(text, rate=150, volume=1.0, voice_gender="female"):
    """
    Convert text to speech using pyttsx3 (offline TTS)
    
    Parameters:
    - text: The text to convert to speech
    - rate: Speech rate (words per minute)
    - volume: Volume (0.0 to 1.0)
    - voice_gender: Voice gender preference ("male" or "female")
    """
    try:
        import pyttsx3
        
        # Initialize the TTS engine
        engine = pyttsx3.init()
        
        # Set properties
        engine.setProperty('rate', rate)
        engine.setProperty('volume', volume)
        
        # Select voice based on gender preference
        voices = engine.getProperty('voices')
        selected_voice = None
        
        for voice in voices:
            # Look for a voice matching the preferred gender
            if voice_gender.lower() == "male" and "male" in voice.name.lower():
                selected_voice = voice.id
                break
            elif voice_gender.lower() == "female" and "female" in voice.name.lower():
                selected_voice = voice.id
                break
        
        # If no matching voice found, use the first available voice
        if selected_voice is None and voices:
            selected_voice = voices[0].id
            print(f"No {voice_gender} voice found. Using default voice.")
        
        if selected_voice:
            engine.setProperty('voice', selected_voice)
        
        # Show information
        print("\nUsing pyttsx3 (offline TTS):")
        print(f"Text: {text}")
        print(f"Rate: {rate} words per minute")
        print(f"Volume: {volume}")
        print("Converting text to speech...")
        
        # Convert text to speech
        engine.say(text)
        engine.runAndWait()
        
        print("Speech completed.")
        return True
    
    except ImportError:
        print("Error: pyttsx3 library is not installed.")
        print("Install it using: pip install pyttsx3")
        return False
    
    except Exception as e:
        print(f"An error occurred: {e}")
        return False

def tts_gtts(text, lang="en", slow=False):
    """
    Convert text to speech using gTTS (Google Text-to-Speech, online TTS)
    
    Parameters:
    - text: The text to convert to speech
    - lang: Language code (e.g., "en" for English, "es" for Spanish)
    - slow: Slow speech rate if True
    """
    try:
        from gtts import gTTS
        from playsound import playsound
        
        # Show information
        print("\nUsing gTTS (Google Text-to-Speech, online TTS):")
        print(f"Text: {text}")
        print(f"Language: {lang}")
        print(f"Slow: {slow}")
        print("Converting text to speech...")
        
        # Generate a unique filename in the system's temp directory
        timestamp = datetime.now().strftime("%Y%m%d%H%M%S")
        temp_file = os.path.join(tempfile.gettempdir(), f"tts_{timestamp}.mp3")
        
        # Create gTTS object
        tts = gTTS(text=text, lang=lang, slow=slow)
        
        # Save the speech to a temporary file
        tts.save(temp_file)
        
        # Play the speech
        print("Playing speech...")
        playsound(temp_file)
        
        # Clean up
        try:
            os.remove(temp_file)
        except:
            pass
        
        print("Speech completed.")
        return True
    
    except ImportError:
        print("Error: Required libraries are not installed.")
        print("Install them using: pip install gtts playsound")
        return False
    
    except Exception as e:
        print(f"An error occurred: {e}")
        return False

def main():
    print("===== Text-to-Speech Demo =====")
    print("1. Offline TTS (pyttsx3)")
    print("2. Online TTS (Google Text-to-Speech)")
    
    choice = input("\nEnter your choice (1 or 2): ")
    
    text = input("Enter the text to convert to speech: ")
    
    if not text:
        text = "Hello! This is a text-to-speech demonstration using Python. It can be useful for creating voice assistants, accessibility tools, or adding speech capabilities to your applications."
    
    try:
        if choice == "1":
            # Offline TTS with pyttsx3
            rate = int(input("Enter speech rate (100-200, default 150): ") or "150")
            volume = float(input("Enter volume (0.0-1.0, default 1.0): ") or "1.0")
            gender = input("Enter preferred voice gender (male/female, default female): ") or "female"
            
            tts_pyttsx3(text, rate, volume, gender)
        
        elif choice == "2":
            # Online TTS with gTTS
            languages = {
                "1": {"code": "en", "name": "English"},
                "2": {"code": "es", "name": "Spanish"},
                "3": {"code": "fr", "name": "French"},
                "4": {"code": "de", "name": "German"},
                "5": {"code": "it", "name": "Italian"},
                "6": {"code": "ja", "name": "Japanese"},
                "7": {"code": "ko", "name": "Korean"},
                "8": {"code": "pt", "name": "Portuguese"},
                "9": {"code": "ru", "name": "Russian"},
                "10": {"code": "zh-CN", "name": "Chinese (Simplified)"}
            }
            
            print("\nAvailable languages:")
            for key, lang in languages.items():
                print(f"{key}. {lang['name']}")
            
            lang_choice = input("Enter language number (default 1 for English): ") or "1"
            slow = input("Use slow speech? (y/n, default n): ").lower() == "y"
            
            lang_code = languages.get(lang_choice, {"code": "en"})["code"]
            tts_gtts(text, lang_code, slow)
        
        else:
            print("Invalid choice")
    
    except Exception as e:
        print(f"An error occurred: {e}")
    
    print("\nNote: This example requires specific libraries:")
    print("For offline TTS: pip install pyttsx3")
    print("For online TTS: pip install gtts playsound")

if __name__ == "__main__":
    main()
```
Demonstrates text-to-speech functionality.

### 100. Blockchain Implementation
```python
import hashlib
import json
import time
from datetime import datetime

class Block:
    def __init__(self, index, transactions, timestamp, previous_hash, nonce=0):
        """Initialize a block in the blockchain."""
        self.index = index
        self.transactions = transactions
        self.timestamp = timestamp
        self.previous_hash = previous_hash
        self.nonce = nonce
        self.hash = self.compute_hash()
    
    def compute_hash(self):
        """
        Create a SHA-256 hash of the block.
        The hash is created from the block's index, transactions, timestamp, previous_hash, and nonce.
        """
        block_string = json.dumps({
            "index": self.index,
            "transactions": self.transactions,
            "timestamp": self.timestamp,
            "previous_hash": self.previous_hash,
            "nonce": self.nonce
        }, sort_keys=True)
        
        return hashlib.sha256(block_string.encode()).hexdigest()
    
    def __str__(self):
        """Return a string representation of the block."""
        return (
            f"Block #{self.index}\n"
            f"Hash: {self.hash}\n"
            f"Previous Hash: {self.previous_hash}\n"
            f"Timestamp: {datetime.fromtimestamp(self.timestamp).strftime('%Y-%m-%d %H:%M:%S')}\n"
            f"Transactions: {json.dumps(self.transactions, indent=2)}\n"
            f"Nonce: {self.nonce}\n"
        )

class Blockchain:
    # Difficulty of our PoW algorithm (number of leading zeros in hash)
    difficulty = 2
    
    def __init__(self):
        """Initialize a blockchain."""
        self.chain = []
        self.pending_transactions = []
        
        # Create the genesis block
        self.create_genesis_block()
    
    def create_genesis_block(self):
        """
        Create the first block in the chain (genesis block).
        The genesis block has no previous hash.
        """
        genesis_block = Block(0, [], time.time(), "0")
        genesis_block.hash = genesis_block.compute_hash()
        self.chain.append(genesis_block)
    
    @property
    def last_block(self):
        """Return the last block in the chain."""
        return self.chain[-1]
    
    def proof_of_work(self, block):
        """
        Proof of Work algorithm:
        - Find a number (nonce) such that the hash of the block starts with a certain number of leading zeros.
        """
        block.nonce = 0
        computed_hash = block.compute_hash()
        
        while not computed_hash.startswith('0' * self.difficulty):
            block.nonce += 1
            computed_hash = block.compute_hash()
        
        return computed_hash
    
    def add_block(self, block, proof):
        """
        Add a block to the chain after verification.
        - Verify that the previous_hash field of the block matches the hash of the last block in the chain.
        - Verify that the proof (hash) is valid and meets the difficulty criteria.
        """
        previous_hash = self.last_block.hash
        
        # Check if the previous hash is correct
        if previous_hash != block.previous_hash:
            return False
        
        # Check if the proof is valid
        if not proof.startswith('0' * self.difficulty):
            return False
        
        # If all checks pass, add the block to the chain
        block.hash = proof
        self.chain.append(block)
        return True
    
    def add_transaction(self, sender, recipient, amount):
        """
        Add a new transaction to the list of pending transactions.
        """
        self.pending_transactions.append({
            'sender': sender,
            'recipient': recipient,
            'amount': amount,
            'timestamp': time.time()
        })
    
    def mine_pending_transactions(self, miner_address):
        """
        Take all pending transactions, create a new block, and mine it.
        The miner is rewarded with a transaction added to the next block.
        """
        if not self.pending_transactions:
            return False  # No transactions to mine
        
        # Create a new block with all pending transactions
        block = Block(
            index=len(self.chain),
            transactions=self.pending_transactions,
            timestamp=time.time(),
            previous_hash=self.last_block.hash
        )
        
        # Find the proof of work for this block
        proof = self.proof_of_work(block)
        
        # Add the block to the chain
        added = self.add_block(block, proof)
        
        if added:
            # Reset pending transactions and reward the miner
            self.pending_transactions = [
                {
                    'sender': "BLOCKCHAIN_REWARD",
                    'recipient': miner_address,
                    'amount': 1,  # Mining reward
                    'timestamp': time.time()
                }
            ]
            return block
        
        return None
    
    def is_valid_chain(self):
        """
        Check if the blockchain is valid.
        - The chain should start with the genesis block.
        - Each block's hash should be correctly computed.
        - Each block should reference the previous block's hash.
        """
        for i in range(1, len(self.chain)):
            current = self.chain[i]
            previous = self.chain[i-1]
            
            # Check if the current block has the correct previous hash
            if current.previous_hash != previous.hash:
                return False
            
            # Check if the hash of the current block is correct
            if current.hash != current.compute_hash():
                return False
        
        return True
    
    def get_balance(self, address):
        """Calculate the balance of a given address in the blockchain."""
        balance = 0
        
        # Iterate through all blocks in the chain
        for block in self.chain:
            # Iterate through all transactions in the block
            for transaction in block.transactions:
                if transaction['sender'] == address:
                    balance -= transaction['amount']
                if transaction['recipient'] == address:
                    balance += transaction['amount']
        
        return balance
    
    def print_chain(self):
        """Print the entire blockchain."""
        print("\n===== BLOCKCHAIN =====")
        for block in self.chain:
            print(block)
            print("-" * 50)

def main():
    # Create a blockchain
    blockchain = Blockchain()
    
    print("Welcome to the Simple Blockchain Demo!")
    print("Mining genesis block...")
    
    # Simulate some transactions and mining
    blockchain.add_transaction("Alice", "Bob", 10)
    blockchain.add_transaction("Bob", "Charlie", 5)
    print("Added 2 transactions to the pending transactions pool.")
    
    print("Mining the first block...")
    mined_block = blockchain.mine_pending_transactions("Miner1")
    print(f"Block #{mined_block.index} successfully mined!")
    
    # Add more transactions
    blockchain.add_transaction("Charlie", "Dave", 3)
    blockchain.add_transaction("Alice", "Eve", 7)
    blockchain.add_transaction("Eve", "Bob", 2)
    print("Added 3 more transactions to the pending transactions pool.")
    
    print("Mining the second block...")
    mined_block = blockchain.mine_pending_transactions("Miner1")
    print(f"Block #{mined_block.index} successfully mined!")
    
    # Display the blockchain
    blockchain.print_chain()
    
    # Check balances
    addresses = ["Alice", "Bob", "Charlie", "Dave", "Eve", "Miner1"]
    print("\n===== ACCOUNT BALANCES =====")
    for address in addresses:
        balance = blockchain.get_balance(address)
        print(f"{address}: {balance}")
    
    # Verify the blockchain
    print(f"\nBlockchain is valid: {blockchain.is_valid_chain()}")

if __name__ == "__main__":
    main()
```
Implements a simple blockchain.

## Summary

These 100 Python programs cover a wide range of topics and difficulty levels:

- **Basic Concepts**: Variables, data types, operators, control structures
- **Data Structures**: Lists, dictionaries, sets, tuples
- **Functions and OOP**: Functions, classes, inheritance, encapsulation
- **File Handling**: Reading/writing text, CSV, JSON, XML files
- **Web Development**: Building web apps with Flask and Django
- **Data Analysis**: Pandas, matplotlib for data manipulation and visualization
- **Network Programming**: Sockets, API requests, web scraping
- **Database**: SQLite operations
- **Machine Learning**: Simple neural network, linear regression
- **Multimedia**: Image processing, text-to-speech
- **Games**: Snake, Hangman, Conway's Game of Life
- **Tools**: Working with PDFs, handling command-line arguments, creating progress bars

These programs provide a comprehensive foundation for Python programming, allowing you to build progressively more advanced applications as you master each concept.