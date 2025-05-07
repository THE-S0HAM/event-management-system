<?php
require_once 'utils/functions.php';
require_once 'classes/DB.php';
require_once 'classes/Client.php';
require_once 'classes/ClientTableGateway.php';
require_once 'classes/Connection.php';

if (!is_logged_in()) {
    header("Location: login_form.php");
}

$connection = Connection::getInstance();
$gateway = new ClientTableGateway($connection);

if (!isset($_GET['id'])) {
    die("Illegal request. Client ID is required.");
}

$id = $_GET['id'];

$statement = $gateway->getClientById($id);
if ($statement->rowCount() !== 1) {
    die("Illegal request. Client not found.");
}

$row = $statement->fetch(PDO::FETCH_ASSOC);

// Get client events
$clientEvents = $gateway->getClientEvents($id);

// Get client payments
$clientPayments = $gateway->getClientPayments($id);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Client Details - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <div class="col-md-12">
                    <h1>Client Details</h1>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <tr>
                                    <th>Client ID</th>
                                    <td><?php echo $row['ClientID']; ?></td>
                                </tr>
                                <tr>
                                    <th>First Name</th>
                                    <td><?php echo $row['FirstName']; ?></td>
                                </tr>
                                <tr>
                                    <th>Last Name</th>
                                    <td><?php echo $row['LastName']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $row['Email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><?php echo $row['Phone']; ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?php echo $row['Address']; ?></td>
                                </tr>
                                <tr>
                                    <th>Special Instructions</th>
                                    <td><?php echo nl2br($row['SpecialInstructions']); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <a href="editClient.php?id=<?php echo $row['ClientID']; ?>" class="btn btn-warning">Edit</a>
                            <a href="deleteClient.php?id=<?php echo $row['ClientID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this client?')">Delete</a>
                            <a href="viewClients.php" class="btn btn-default">Back to Clients</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Client Events</h3>
                        </div>
                        <div class="panel-body">
                            <?php if ($clientEvents->rowCount() > 0): ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Event Title</th>
                                            <th>Date</th>
                                            <th>Venue</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($event = $clientEvents->fetch(PDO::FETCH_ASSOC)): ?>
                                            <tr>
                                                <td><?php echo $event['Title']; ?></td>
                                                <td><?php echo $event['StartDate']; ?></td>
                                                <td><?php echo $event['LocationName']; ?></td>
                                                <td>
                                                    <a href="viewEvent.php?id=<?php echo $event['EventID']; ?>" class="btn btn-xs btn-info">View</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No events found for this client.</p>
                                <a href="createEventForm.php" class="btn btn-success">Create New Event</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">Payment History</h3>
                        </div>
                        <div class="panel-body">
                            <?php if ($clientPayments->rowCount() > 0): ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Event</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($payment = $clientPayments->fetch(PDO::FETCH_ASSOC)): ?>
                                            <tr>
                                                <td><?php echo $payment['EventTitle']; ?></td>
                                                <td>₹<?php echo number_format($payment['Amount']); ?></td>
                                                <td><?php echo $payment['PaymentDate']; ?></td>
                                                <td>
                                                    <span class="label label-<?php echo ($payment['PaymentStatus'] == 'Completed') ? 'success' : 'warning'; ?>">
                                                        <?php echo $payment['PaymentStatus']; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No payment records found for this client.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>