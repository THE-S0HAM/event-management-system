<?php
require_once 'utils/functions.php';
require_once 'classes/DB.php';
require_once 'classes/Location.php';
require_once 'classes/LocationTableGateway.php';
require_once 'classes/Connection.php';

if (!is_logged_in()) {
    header("Location: login_form.php");
}

$connection = Connection::getInstance();
$gateway = new LocationTableGateway($connection);

if (!isset($_GET['id'])) {
    die("Illegal request");
}
$id = $_GET['id'];

$statement = $gateway->getLocationsById($id);
if ($statement->rowCount() !== 1) {
    die("Illegal request");
}
$row = $statement->fetch(PDO::FETCH_ASSOC);

// Get events at this venue
require_once 'classes/EventTableGateway.php';
$eventGateway = new EventTableGateway($connection);
$events = $eventGateway->getEventsByLocationId($id);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>View Venue - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <div class="col-md-12">
                    <h1>Venue Details</h1>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $row['Name']; ?></h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <tr>
                                    <th>Venue Name</th>
                                    <td><?php echo $row['Name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?php echo $row['Address']; ?></td>
                                </tr>
                                <tr>
                                    <th>Manager</th>
                                    <td><?php echo $row['ManagerFName'] . ' ' . $row['ManagerLName']; ?></td>
                                </tr>
                                <tr>
                                    <th>Manager Email</th>
                                    <td><?php echo $row['ManagerEmail']; ?></td>
                                </tr>
                                <tr>
                                    <th>Manager Number</th>
                                    <td><?php echo $row['ManagerNumber']; ?></td>
                                </tr>
                                <tr>
                                    <th>Maximum Capacity</th>
                                    <td><?php echo $row['MaxCapacity']; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <a class="btn btn-warning" href="editLocationForm.php?id=<?php echo $row['LocationID']; ?>">Edit</a>
                            <a class="btn btn-danger" href="deleteLocation.php?id=<?php echo $row['LocationID']; ?>">Delete</a>
                            <a class="btn btn-default" href="viewLocations.php">Back to Venues</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Events at this Venue</h3>
                        </div>
                        <div class="panel-body">
                            <?php if ($events->rowCount() > 0) { ?>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Event Title</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($event = $events->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <tr>
                                                <td><?php echo $event['Title']; ?></td>
                                                <td><?php echo $event['StartDate']; ?></td>
                                                <td><?php echo $event['EndDate']; ?></td>
                                                <td>
                                                    <a class="btn btn-xs btn-info" href="viewEvent.php?id=<?php echo $event['EventID']; ?>">View</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <p>No events scheduled at this venue.</p>
                            <?php } ?>
                        </div>
                        <div class="panel-footer">
                            <a class="btn btn-success" href="createEventForm.php">Create New Event</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>