<?php

header("Content-Type: application/json");
header("Acess-Control-Allow-Origin: *");
header("Acess-Control-Allow-Methods: POST"); // here is define the request method


//getting the database connection
include_once 'includedFiles.php';


$data = json_decode(file_get_contents("php://input"), true); // collect input parameters and convert into readable format
$fileName = $_FILES['sendimage']['name'];
$tempPath = $_FILES['sendimage']['tmp_name'];
$fileSize = $_FILES['sendimage']['size'];


// other values
$users_id_users = $_POST['userID'];
$reading = $_POST['reading'];
$reading_time = $_POST['reading_time'];
$shift_id_shift = $_POST['shift_id_shift'];
$fuel_id_fuel = $_POST['fuel_id_fuel'];


if (empty($users_id_users)) {
    $errorMSG = json_encode(array("message" => "please provide user ID", "status" => false));
    echo $errorMSG;
} else if (empty($reading)) {
    $errorMSG = json_encode(array("message" => "please provide meter Reading", "status" => false));
    echo $errorMSG;
} else if (empty($reading_time)) {
    $errorMSG = json_encode(array("message" => "please provide meter Reading time", "status" => false));
    echo $errorMSG;
} else if (empty($shift_id_shift)) {
    $errorMSG = json_encode(array("message" => "please provide shift id", "status" => false));
    echo $errorMSG;
}else if (empty($fuel_id_fuel)) {
    $errorMSG = json_encode(array("message" => "please provide fuel id", "status" => false));
    echo $errorMSG;
} else if (empty($fileName)) {
    $errorMSG = json_encode(array("message" => "please select image", "status" => false));

    echo $errorMSG;
} else {
    $upload_path = '../../mobile_uploads/'; // set upload folder path
    if (!file_exists($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    $fileName = stripslashes($fuel_id_fuel . '_meter_') . $fileName;


    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // get image extension

    // valid image extensions
    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

    // allow valid image file formats
    if (in_array($fileExt, $valid_extensions)) {
        //check file not exist our upload folder path
        if (!file_exists($upload_path . $fileName)) {
            // check file size '5MB'
            if ($fileSize > 5000000) {
                $errorMSG = json_encode(array("message" => "Sorry, your file is too large, please upload 5 MB size", "status" => false));
                echo $errorMSG;

            }
        } else {
            $errorMSG = json_encode(array("message" => "Sorry, file already exists check upload folder", "status" => false));
            echo $errorMSG;
        }
    } else {
        $errorMSG = json_encode(array("message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed", "status" => false));
        echo $errorMSG;
    }
}

// if no error caused, continue ....
if (!isset($errorMSG)) {

    $query = mysqli_query($db, 'INSERT INTO `meter_reading`(`reading`, `reading_time`, `shift_idshift`, `users_idusers`, `fuel_idfuel`, `imagepath`) VALUES ("' . $reading . '","' . $reading_time . '","' . $shift_id_shift .'","' . $users_id_users .'","' . $fuel_id_fuel . '","' . $fileName . '")');
    move_uploaded_file($tempPath, $upload_path . $fileName); // move file from system temporary path to our upload folder path

    echo json_encode(array("message" => "Image Uploaded Successfully", "status" => true));
}

?>