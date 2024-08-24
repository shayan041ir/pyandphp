import pytesseract
from PIL import Image
import sys

# دریافت مسیر تصویر از آرگومان خط فرمان
image_path = sys.argv[1]

# تنظیم مسیر tesseract
pytesseract.pytesseract.tesseract_cmd = "C:\\Program Files\\Tesseract-OCR\\tesseract.exe"

# بارگذاری تصویر
img = Image.open(image_path)

# استخراج متن از تصویر
result = pytesseract.image_to_string(img, lang="eng+fas")

# چاپ متن استخراج شده به عنوان خروجی
print(result)
