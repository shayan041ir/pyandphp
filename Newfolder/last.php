<?php
// مسیر فایل تصویر پلاک خودرو
$imagePath = 'path\to\car_plate_image.jpg';

// استفاده از ImageMagick برای بهبود تصویر (اختیاری)
$im = new Imagick($imagePath);
$im->setImageType(Imagick::IMGTYPE_GRAYSCALE);
$im->brightnessContrastImage(0, 30);
$im->writeImage('processed_image.jpg');

// اجرای Tesseract برای تشخیص متن از تصویر
$output = [];
$retval = 0;

exec("tesseract processed_image.jpg stdout -l eng", $output, $retval);

if ($retval == 0) {
    // چاپ نتیجه تشخیص
    $text = implode("\n", $output);
    echo "متن استخراج شده از تصویر: " . $text;
} else {
    echo "خطایی رخ داده است.";
}
?>
