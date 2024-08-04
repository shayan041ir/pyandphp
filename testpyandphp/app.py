from flask import Flask, request, jsonify
import os
from hezar.models import Model

app = Flask(__name__)

# مسیر پوشه آپلود
UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# بررسی وجود پوشه آپلود و ایجاد آن در صورت عدم وجود
if not os.path.exists(UPLOAD_FOLDER):
    os.makedirs(UPLOAD_FOLDER)

def process_image(filepath):
    model = Model.load("hezarai/crnn-fa-license-plate-recognition")
    plate_text = model.predict(filepath)
    return plate_text

@app.route('/upload', methods=['POST'])
def upload_file():
    if 'file' not in request.files:
        return jsonify({'error': 'No file part'}), 400
    
    file = request.files['file']
    
    if file.filename == '':
        return jsonify({'error': 'No selected file'}), 400
    
    if file:
        filename = file.filename
        filepath = os.path.join(app.config['UPLOAD_FOLDER'], filename)
        file.save(filepath)

        # پردازش تصویر
        result_text = process_image(filepath)

        return jsonify({'result': result_text, 'filename': filename})

if __name__ == '__main__':
    app.run(debug=True)