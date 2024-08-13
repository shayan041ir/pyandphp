from hezar.models import Model

model = Model.load("hezarai/crnn-fa-license-plate-recognition")
plate_text = model.predict("./upload/img/nemayedakheli/shayantest.jpg")
print(plate_text)  # Persian text of mixed numbers and characters might not show correctly in the console
