from flask import Flask, request
from hezar.models import Model
import os
import MySQLdb

app = Flask(__name__)

db = MySQLdb.connect(
    host="localhost",
    user="root",  
    passwd="",  
    db="pelak"  
)
cursor = db.cursor()

def process_image(image_path):
    model = Model.load("hezarai/crnn-fa-license-plate-recognition")
    plate_text = model.predict(image_path)
    sli = slice(11, -3)
    numberOfPlate = str(plate_text)[sli]
    return numberOfPlate
    # return plate_text

@app.route('/process_image', methods=['POST'])
def process_image_route():
    if 'file' not in request.files:
        return "No file part", 400
    
    file = request.files['file']
    
    if file.filename == '':
        return "No selected file", 400
    
    upload_folder = os.path.join(os.getcwd(), 'upload')
    if not os.path.exists(upload_folder):
        os.makedirs(upload_folder)
    
    temp_path = os.path.join(upload_folder, file.filename)
    file.save(temp_path)
    
    plate_number = process_image(temp_path)
    
    try:
        query = "INSERT INTO pelaks (plak) VALUES (%s)"
        cursor.execute(query, (plate_number,))
        db.commit()
    except Exception as e:
        db.rollback()
        return str(e), 500
    
    return plate_number

if __name__ == '__main__':
    app.run(debug=True)



