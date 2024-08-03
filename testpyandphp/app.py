from flask import Flask, request, jsonify
import os

app = Flask(__name__)

# مسیر پوشه آپلود
UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# بررسی وجود پوشه آپلود و ایجاد آن در صورت عدم وجود
if not os.path.exists(UPLOAD_FOLDER):
    os.makedirs(UPLOAD_FOLDER)

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

        # اینجا می‌توانید پردازش تصویر را انجام دهید
        # به عنوان مثال، فقط نام فایل را برگردانیم
        result = f"File {filename} has been processed."

        return jsonify({'result': result, 'filename': filename})

if __name__ == '__main__':
    app.run(debug=True)

# def text():
#     from hezar.models import Model
#     model = Model.load("hezarai/crnn-fa-license-plate-recognition")
#     plate_text = model.predict("UPLOAD_FOLDER")
#     # print(plate_text)
#     return plate_text
    