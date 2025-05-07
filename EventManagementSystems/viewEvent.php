<?php
require_once 'utils/functions.php';
require_once 'classes/DB.php';
require_once 'classes/Event.php';
require_once 'classes/EventTableGateway.php';
require_once 'classes/Location.php';
require_once 'classes/LocationTableGateway.php';
require_once 'classes/Client.php';
require_once 'classes/ClientTableGateway.php';
require_once 'classes/Connection.php';

if (!is_logged_in()) {
    header("Location: login_form.php");
}

$connection = Connection::getInstance();
$eventGateway = new EventTableGateway($connection);
$locationGateway = new LocationTableGateway($connection);
$clientGateway = new ClientTableGateway($connection);

if (!isset($_GET['id'])) {
    die("Illegal request. Event ID is required.");
}

$id = $_GET['id'];

// Get event details
$eventStatement = $eventGateway->getEventsById($id);
if ($eventStatement->rowCount() !== 1) {
    die("Illegal request. Event not found.");
}
$event = $eventStatement->fetch(PDO::FETCH_ASSOC);

// Get location details
$locationStatement = $locationGateway->getLocationsById($event['LocationID']);
$location = $locationStatement->fetch(PDO::FETCH_ASSOC);

// Get client details if available
$client = null;
if (!empty($event['ClientID'])) {
    $clientStatement = $clientGateway->getClientById($event['ClientID']);
    if ($clientStatement->rowCount() === 1) {
        $client = $clientStatement->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Event Details - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <div class="col-md-12">
                    <h1>Event Details</h1>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-8">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $event['Title']; ?></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p><strong>Description:</strong> <?php echo $event['Description']; ?></p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Start Date:</strong> <?php echo $event['StartDate']; ?></p>
                                    <p><strong>End Date:</strong> <?php echo $event['EndDate']; ?></p>
                                    <p><strong>Cost:</strong> ₹<?php echo number_format($event['Cost']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Venue:</strong> <?php echo $location['Name']; ?></p>
                                    <p><strong>Venue Address:</strong> <?php echo $location['Address']; ?></p>
                                    <p><strong>Maximum Capacity:</strong> <?php echo $location['MaxCapacity']; ?> guests</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Ceremony Type:</strong> <?php echo !empty($event['CeremonyType']) ? $event['CeremonyType'] : 'Not specified'; ?></p>
                                    <p><strong>Decoration Theme:</strong> <?php echo !empty($event['DecorationTheme']) ? $event['DecorationTheme'] : 'Not specified'; ?></p>
                                    <p><strong>Catering Option:</strong> <?php echo !empty($event['CateringOption']) ? $event['CateringOption'] : 'Not specified'; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <?php if ($client): ?>
                                        <p><strong>Client:</strong> <a href="viewClient.php?id=<?php echo $client['ClientID']; ?>"><?php echo $client['FirstName'] . ' ' . $client['LastName']; ?></a></p>
                                        <p><strong>Client Email:</strong> <?php echo $client['Email']; ?></p>
                                        <p><strong>Client Phone:</strong> <?php echo $client['Phone']; ?></p>
                                    <?php else: ?>
                                        <p><strong>Client:</strong> Not assigned</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <a href="editEventForm.php?id=<?php echo $event['EventID']; ?>" class="btn btn-warning">Edit</a>
                            <a href="deleteEvent.php?id=<?php echo $event['EventID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                            <a href="viewEvents.php" class="btn btn-default">Back to Events</a>
                            
                            <?php if ($client): ?>
                                <a href="createPayment.php?eventId=<?php echo $event['EventID']; ?>&clientId=<?php echo $client['ClientID']; ?>" class="btn btn-success pull-right">Record Payment</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Venue Manager</h3>
                        </div>
                        <div class="panel-body">
                            <p><strong>Name:</strong> <?php echo $location['ManagerFName'] . ' ' . $location['ManagerLName']; ?></p>
                            <p><strong>Email:</strong> <?php echo $location['ManagerEmail']; ?></p>
                            <p><strong>Phone:</strong> <?php echo $location['ManagerNumber']; ?></p>
                        </div>
                    </div>
                    
                    <?php if ($client): ?>
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Special Instructions</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (!empty($client['SpecialInstructions'])): ?>
                                    <p><?php echo nl2br($client['SpecialInstructions']); ?></p>
                                <?php else: ?>
                                    <p>No special instructions provided.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">Actions</h3>
                        </div>
                        <div class="panel-body">
                            <div class="list-group">
                                <a href="printEvent.php?id=<?php echo $event['EventID']; ?>" class="list-group-item" target="_blank">
                                    <i class="glyphicon glyphicon-print"></i> Print Event Details
                                </a>
                                <a href="sendConfirmation.php?id=<?php echo $event['EventID']; ?>" class="list-group-item">
                                    <i class="glyphicon glyphicon-envelope"></i> Send Confirmation Email
                                </a>
                                <a href="createFeedback.php?eventId=<?php echo $event['EventID']; ?>&clientId=<?php echo $event['ClientID']; ?>" class="list-group-item">
                                    <i class="glyphicon glyphicon-star"></i> Record Feedback
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>