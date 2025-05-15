<?php
require_once 'utils/functions.php';
require_once 'classes/DB.php';
require_once 'classes/Location.php';
require_once 'classes/LocationTableGateway.php';
require_once 'classes/Connection.php';

if (!is_logged_in()) {
    header("Location: login_form.php");
}

$formdata = array();
$errors = array();

$formdata['Name'] = $_POST['Name'];
if (empty($formdata['Name'])) {
    $errors['Name'] = "Venue name is required";
}

$formdata['Address'] = $_POST['Address'];
if (empty($formdata['Address'])) {
    $errors['Address'] = "Address is required";
}

$formdata['ManagerFName'] = $_POST['ManagerFName'];
if (empty($formdata['ManagerFName'])) {
    $errors['ManagerFName'] = "Manager first name is required";
}

$formdata['ManagerLName'] = $_POST['ManagerLName'];
if (empty($formdata['ManagerLName'])) {
    $errors['ManagerLName'] = "Manager last name is required";
}

$formdata['ManagerEmail'] = $_POST['ManagerEmail'];
if (empty($formdata['ManagerEmail'])) {
    $errors['ManagerEmail'] = "Manager email is required";
}
else if (!filter_var($formdata['ManagerEmail'], FILTER_VALIDATE_EMAIL)) {
    $errors['ManagerEmail'] = "Manager email is invalid";
}

$formdata['ManagerNumber'] = $_POST['ManagerNumber'];
if (empty($formdata['ManagerNumber'])) {
    $errors['ManagerNumber'] = "Manager number is required";
}

$formdata['MaxCapacity'] = $_POST['MaxCapacity'];
if (empty($formdata['MaxCapacity'])) {
    $errors['MaxCapacity'] = "Maximum capacity is required";
}
else if (!filter_var($formdata['MaxCapacity'], FILTER_VALIDATE_INT)) {
    $errors['MaxCapacity'] = "Maximum capacity must be a number";
}
else if ($formdata['MaxCapacity'] <= 0) {
    $errors['MaxCapacity'] = "Maximum capacity must be greater than zero";
}

// Handle image upload
$imagePath = null;
if (isset($_FILES['venueImage']) && $_FILES['venueImage']['error'] == 0) {
    $targetDir = "images/";
    $fileName = basename($_FILES["venueImage"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
    // Allow certain file formats
    $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (in_array(strtolower($fileType), $allowTypes)) {
        // Upload file to server
        if (move_uploaded_file($_FILES["venueImage"]["tmp_name"], $targetFilePath)) {
            $imagePath = $targetFilePath;
        }
    }
}

// If no image uploaded, set default image based on venue name
if (!$imagePath) {
    if (stripos($formdata['Name'], 'Mangal') !== false) {
        $imagePath = "images/tradinational-wed.jpg";
    } else if (stripos($formdata['Name'], 'Vivah') !== false || stripos($formdata['Name'], 'Hall') !== false) {
        $imagePath = "images/sarkharpuda.jpg";
    } else if (stripos($formdata['Name'], 'Lawn') !== false) {
        $imagePath = "images/reception.jpg";
    } else {
        $imagePath = "images/tradinational-wed.jpg";
    }
}

if (empty($errors)) {
    $connection = Connection::getInstance();
    $gateway = new LocationTableGateway($connection);

    // First, check if the ImagePath column exists
    try {
        $checkColumn = "SHOW COLUMNS FROM `locations` LIKE 'ImagePath'";
        $result = $connection->query($checkColumn);
        
        if ($result->rowCount() == 0) {
            // Column doesn't exist, add it
            $alterTable = "ALTER TABLE `locations` ADD COLUMN `ImagePath` varchar(255) DEFAULT NULL";
            $connection->exec($alterTable);
        }
        
        // Now insert the location with image path
        $id = $gateway->insert($formdata['Name'], $formdata['Address'], $formdata['ManagerFName'], 
                $formdata['ManagerLName'], $formdata['ManagerEmail'], $formdata['ManagerNumber'], 
                $formdata['MaxCapacity'], $imagePath);
        
    } catch (PDOException $e) {
        // If there's an error with the column check or alter, just insert without image path
        $id = $gateway->insert($formdata['Name'], $formdata['Address'], $formdata['ManagerFName'], 
                $formdata['ManagerLName'], $formdata['ManagerEmail'], $formdata['ManagerNumber'], 
                $formdata['MaxCapacity']);
    }

    header('Location: viewLocations.php?success=Venue created successfully');
}
else {
    require 'createLocationForm.php';
}
?>