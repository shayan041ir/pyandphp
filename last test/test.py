from flask import Flask, request, jsonify, send_from_directory
from hezar.models import Model
import os

app = Flask(__name__)

def process_image():
    model = Model.load("hezarai/crnn-fa-license-plate-recognition")
    plate_text = model.predict("C:\\xampp\\htdocs\\last test\\upload\\test1.jpg")
    # print(plate_text) 
    sli = slice(11, -3)
    numberOfPlate = str(plate_text)[sli]
    return numberOfPlate 

# result_text = process_image()
# print(result_text)

@app.route('/upload')
def upload_file():
    
    # if request.method == 'GET':
    #     return str("get\n")
    #     return jsonify({'message': 'This endpoint only supports POST requests'}), 400
    # elif request.method == 'POST':
    #     return str("post ...\n")
    if 'file' not in request.files:
        return jsonify({'error': 'No file part'}), 400

    file = request.files['file']

    if file.filename == '':
        return jsonify({'error': 'No selected file'}), 400

    if file:
        filename = file.filename
        filepath = os.path.join(app.config["C:\\xampp\\htdocs\\last test\\upload\\test1.jpg"], filename)
        file.save(filepath)


        result_text = process_image(filepath)

        return jsonify({'result': result_text, 'filename': filename})

