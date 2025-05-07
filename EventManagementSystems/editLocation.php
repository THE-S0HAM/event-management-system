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

$formdata['id'] = $_POST['id'];

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

if (empty($errors)) {
    $connection = Connection::getInstance();
    $gateway = new LocationTableGateway($connection);

    $id = $gateway->update($formdata['id'], $formdata['Name'], $formdata['Address'], $formdata['ManagerFName'], 
            $formdata['ManagerLName'], $formdata['ManagerEmail'], $formdata['ManagerNumber'], 
            $formdata['MaxCapacity']);

    header('Location: viewLocations.php?success=Venue updated successfully');
}
else {
    require 'editLocationForm.php';
}
?>