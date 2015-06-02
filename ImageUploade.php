<?php

// Get image string posted from Android App
$base = $_REQUEST['image'];
// Get file name posted from Android App
$filePath = "images";
$filename = $_REQUEST['filename'];
//get user data
$type = $_REQUEST['imageType'];
// Decode Image
$typeImage = "";
$binary = base64_decode($base);
header('Content-Type: bitmap; charset=utf-8');
// Images will be saved under 'www/imgupload/uplodedimages' folder
switch ($type) {
    case 1:
        $typeImage = "profilePic";
        break;
    case 2:

        break;
    case 3:

        break;
}
$filePath = $filePath . '/' . $typeImage;
if (!file_exists($filePath)) {
    mkdir($filePath, 0777);
}
$file = fopen($filePath . '/' . $filename, 'wb');
// Create File
fwrite($file, $binary);
fclose($file);
echo 'Image upload complete, Please check your php file directory';
?>