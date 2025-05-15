<?php
require_once 'utils/functions.php';
require_once 'classes/DB.php';
require_once 'classes/Event.php';
require_once 'classes/EventTableGateway.php';
require_once 'classes/Connection.php';

if (!is_logged_in()) {
    header("Location: login_form.php");
}

if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['startDate']) || 
    !isset($_POST['endDate']) || !isset($_POST['cost']) || !isset($_POST['locationID']) || 
    !isset($_POST['clientID'])) {
    die("Invalid request. Please submit the form again.");
}

$title = $_POST['title'];
$description = $_POST['description'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$cost = $_POST['cost'];
$locationID = $_POST['locationID'];
$clientID = $_POST['clientID'];
$ceremonyType = isset($_POST['ceremonyType']) ? $_POST['ceremonyType'] : null;
$decorationTheme = isset($_POST['decorationTheme']) ? $_POST['decorationTheme'] : null;
$cateringOption = isset($_POST['cateringOption']) ? $_POST['cateringOption'] : null;

// Format dates for display
$formattedStartDate = date('d-M-Y', strtotime($startDate));
$formattedEndDate = date('d-M-Y', strtotime($endDate));

$connection = Connection::getInstance();
$gateway = new EventTableGateway($connection);

// Check if the venue is available for the selected dates
$isAvailable = $gateway->getAvailableDates($locationID, $formattedStartDate, $formattedEndDate);

if (!$isAvailable) {
    // Venue is not available, redirect back with error
    header("Location: createEventForm.php?error=venue_not_available");
    exit;
}

// Handle image upload
$imagePath = null;
if (isset($_FILES['eventImage']) && $_FILES['eventImage']['error'] == 0) {
    $targetDir = "images/";
    $fileName = basename($_FILES["eventImage"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
    // Allow certain file formats
    $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (in_array(strtolower($fileType), $allowTypes)) {
        // Upload file to server
        if (move_uploaded_file($_FILES["eventImage"]["tmp_name"], $targetFilePath)) {
            $imagePath = $targetFilePath;
        }
    }
}

// If no image uploaded, set default image based on ceremony type
if (!$imagePath) {
    if ($ceremonyType) {
        switch(strtolower($ceremonyType)) {
            case 'wedding':
                $imagePath = "images/tradinational-wed.jpg";
                break;
            case 'reception':
                $imagePath = "images/reception.jpg";
                break;
            case 'engagement':
            case 'sakharpuda':
                $imagePath = "images/sarkharpuda.jpg";
                break;
            case 'sangeet':
            case 'mehendi':
            case 'haldi':
                $imagePath = "images/sangeet.jpg";
                break;
            default:
                $imagePath = "images/tradinational-wed.jpg";
        }
    } else {
        $imagePath = "images/tradinational-wed.jpg";
    }
}

// Create the event
$event = new Event(null, $title, $description, $formattedStartDate, $formattedEndDate, $cost, $locationID, $ceremonyType, $decorationTheme, $cateringOption, $clientID);

// First, check if the ImagePath column exists
try {
    $checkColumn = "SHOW COLUMNS FROM `events` LIKE 'ImagePath'";
    $result = $connection->query($checkColumn);
    
    if ($result->rowCount() == 0) {
        // Column doesn't exist, add it
        $alterTable = "ALTER TABLE `events` ADD COLUMN `ImagePath` varchar(255) DEFAULT NULL";
        $connection->exec($alterTable);
    }
    
    // Now insert the event with image path
    $id = $gateway->insert($event, $imagePath);
    
} catch (PDOException $e) {
    // If there's an error with the column check or alter, just insert without image path
    $id = $gateway->insert($event);
}

// Redirect to the event list
header("Location: viewEvents.php");
?>