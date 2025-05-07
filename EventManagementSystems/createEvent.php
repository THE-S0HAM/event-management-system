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

// Create the event
$event = new Event(null, $title, $description, $formattedStartDate, $formattedEndDate, $cost, $locationID, $ceremonyType, $decorationTheme, $cateringOption, $clientID);
$id = $gateway->insert($event);

// Redirect to the event list
header("Location: viewEvents.php");
?>