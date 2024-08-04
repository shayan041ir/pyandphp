from flask import Flask, request, jsonify
import os
from hezar.models import Model

app = Flask(__name__)

# مسیر پوشه آپلود
UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

if not os.path.exists(UPLOAD_FOLDER):
    os.makedirs(UPLOAD_FOLDER)

def process_image(filepath):
  model = Model.load("hezarai/crnn-fa-license-plate-recognition")
  plate_text = model.predict(filepath)
  sli=slice(11,-3)
  numberOfPlate=str(plate_text)[sli]
  Pnumbers=['۱','۲','۳','۴','۵','۶','۷','۸','۹','۰']
  Enumbers=['1','2','3','4','5','6','7','8','9','0',]
  numberOfPlateEnglish=""
  Pcount=0
  count=0
  
  for i in numberOfPlate:
    for j in Pnumbers:
        if i==j:
           numberOfPlateEnglish+=str(Enumbers[count])
           break
        count+=1
    Pcount+=1
    if Pcount==3:
        numberOfPlateEnglish+=i
    count=0
  return numberOfPlateEnglish#returns string

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