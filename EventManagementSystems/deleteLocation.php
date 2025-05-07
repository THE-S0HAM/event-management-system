<?php
require_once 'utils/functions.php';
require_once 'classes/DB.php';
require_once 'classes/Location.php';
require_once 'classes/LocationTableGateway.php';
require_once 'classes/Connection.php';

if (!is_logged_in()) {
    header("Location: login_form.php");
}

if (!isset($_GET['id'])) {
    die("Illegal request");
}
$id = $_GET['id'];

$connection = Connection::getInstance();
$gateway = new LocationTableGateway($connection);

// Check if there are events using this venue
require_once 'classes/EventTableGateway.php';
$eventGateway = new EventTableGateway($connection);
$events = $eventGateway->getEventsByLocationId($id);

if ($events->rowCount() > 0) {
    header('Location: viewLocations.php?error=Cannot delete venue because it has events scheduled');
}
else {
    $gateway->delete($id);
    header('Location: viewLocations.php?success=Venue deleted successfully');
}
?>