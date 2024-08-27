from flask import Flask, request, jsonify
import os
import pytesseract
from PIL import Image
import logging
import shutil

app = Flask(__name__)

# Upload folder path
UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# Check and create upload folder if it does not exist
if not os.path.exists(UPLOAD_FOLDER):
    os.makedirs(UPLOAD_FOLDER)

# Configure logging
logging.basicConfig(filename='app.log', level=logging.DEBUG,
                    format='%(asctime)s %(levelname)s %(name)s %(message)s')
logger = logging.getLogger(__name__)

def process_image(filepath):
    try:
        img = Image.open(filepath)
        text = pytesseract.image_to_string(img, lang='eng+fas')
        return text.strip()
    except Exception as e:
        logger.error(f"Error processing image: {e}")
        return None

@app.route('/lastocr', methods=['POST'])
def upload_file():
    if 'file' not in request.files:
        return jsonify({"error": "No file part"}), 400

    file = request.files['file']
    if file.filename == '':
        return jsonify({"error": "No selected file"}), 400

    if file:
        try:
            filepath = os.path.join(app.config['UPLOAD_FOLDER'], file.filename)
            file.save(filepath)
            logger.info(f"File uploaded: {file.filename}")

            # Process the image
            extracted_text = process_image(filepath)
            if extracted_text:
                return jsonify({"result": extracted_text, "filename": file.filename}), 200
            else:
                return jsonify({"error": "Could not extract text from image"}), 500

        except Exception as e:
            logger.error(f"File upload failed: {e}")
            return jsonify({"error": "File processing failed"}), 500

if __name__ == '__main__':
    # Bind to 0.0.0.0 to make the server accessible externally on the local network
    app.run(debug=True, host='0.0.0.0',port='5000')
