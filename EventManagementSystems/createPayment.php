<?php
require_once 'utils/functions.php';
require_once 'classes/DB.php';
require_once 'classes/Payment.php';
require_once 'classes/PaymentTableGateway.php';
require_once 'classes/Event.php';
require_once 'classes/EventTableGateway.php';
require_once 'classes/Client.php';
require_once 'classes/ClientTableGateway.php';
require_once 'classes/Connection.php';

if (!is_logged_in()) {
    header("Location: login_form.php");
}

$connection = Connection::getInstance();
$paymentGateway = new PaymentTableGateway($connection);
$eventGateway = new EventTableGateway($connection);
$clientGateway = new ClientTableGateway($connection);

// Check if event ID and client ID are provided
if (!isset($_GET['eventId']) || !isset($_GET['clientId'])) {
    die("Invalid request. Event ID and Client ID are required.");
}

$eventId = $_GET['eventId'];
$clientId = $_GET['clientId'];

// Get event details
$eventStatement = $eventGateway->getEventsById($eventId);
if ($eventStatement->rowCount() !== 1) {
    die("Event not found.");
}
$event = $eventStatement->fetch(PDO::FETCH_ASSOC);

// Get client details
$clientStatement = $clientGateway->getClientById($clientId);
if ($clientStatement->rowCount() !== 1) {
    die("Client not found.");
}
$client = $clientStatement->fetch(PDO::FETCH_ASSOC);

// Get payment methods and statuses
$paymentMethods = $paymentGateway->getPaymentMethods();
$paymentStatuses = $paymentGateway->getPaymentStatuses();

// Get total payments made so far
$totalPaid = $paymentGateway->getTotalPaymentsByEvent($eventId);
$remainingAmount = $event['Cost'] - $totalPaid;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT);
    $paymentDate = filter_input(INPUT_POST, 'paymentDate', FILTER_SANITIZE_STRING);
    $paymentMethod = filter_input(INPUT_POST, 'paymentMethod', FILTER_SANITIZE_STRING);
    $paymentStatus = filter_input(INPUT_POST, 'paymentStatus', FILTER_SANITIZE_STRING);
    
    // Validate input
    $errors = array();
    
    if (empty($amount) || $amount <= 0) {
        $errors['amount'] = "Please enter a valid amount";
    }
    
    if (empty($paymentDate)) {
        $errors['paymentDate'] = "Payment date is required";
    }
    
    if (empty($paymentMethod)) {
        $errors['paymentMethod'] = "Please select a payment method";
    }
    
    if (empty($paymentStatus)) {
        $errors['paymentStatus'] = "Please select a payment status";
    }
    
    // If no errors, save payment
    if (empty($errors)) {
        $payment = new Payment(null, $eventId, $clientId, $amount, $paymentDate, $paymentMethod, $paymentStatus);
        $id = $paymentGateway->insert($payment);
        
        // Redirect to event page with success message
        header("Location: viewEvent.php?id=$eventId&payment=success");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Record Payment - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <div class="col-md-12">
                    <h1>Record Payment</h1>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Payment for: <?php echo $event['Title']; ?></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Event:</strong> <?php echo $event['Title']; ?></p>
                                    <p><strong>Date:</strong> <?php echo $event['StartDate']; ?></p>
                                    <p><strong>Total Cost:</strong> ₹<?php echo number_format($event['Cost']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Client:</strong> <?php echo $client['FirstName'] . ' ' . $client['LastName']; ?></p>
                                    <p><strong>Amount Paid:</strong> ₹<?php echo number_format($totalPaid); ?></p>
                                    <p><strong>Remaining Amount:</strong> ₹<?php echo number_format($remainingAmount); ?></p>
                                </div>
                            </div>
                            
                            <form action="createPayment.php?eventId=<?php echo $eventId; ?>&clientId=<?php echo $clientId; ?>" method="POST" class="form-horizontal">
                                <div class="form-group">
                                    <label for="amount" class="col-md-3 control-label">Amount (₹)</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" id="amount" name="amount" value="<?php echo isset($amount) ? $amount : $remainingAmount; ?>" required>
                                        <?php if (isset($errors['amount'])): ?>
                                            <span class="text-danger"><?php echo $errors['amount']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="paymentDate" class="col-md-3 control-label">Payment Date</label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control" id="paymentDate" name="paymentDate" value="<?php echo isset($paymentDate) ? $paymentDate : date('Y-m-d'); ?>" required>
                                        <?php if (isset($errors['paymentDate'])): ?>
                                            <span class="text-danger"><?php echo $errors['paymentDate']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="paymentMethod" class="col-md-3 control-label">Payment Method</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="paymentMethod" name="paymentMethod" required>
                                            <option value="">-- Select Payment Method --</option>
                                            <?php foreach ($paymentMethods as $key => $value): ?>
                                                <option value="<?php echo $key; ?>" <?php if (isset($paymentMethod) && $paymentMethod == $key) echo 'selected'; ?>><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['paymentMethod'])): ?>
                                            <span class="text-danger"><?php echo $errors['paymentMethod']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="paymentStatus" class="col-md-3 control-label">Payment Status</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="paymentStatus" name="paymentStatus" required>
                                            <option value="">-- Select Payment Status --</option>
                                            <?php foreach ($paymentStatuses as $key => $value): ?>
                                                <option value="<?php echo $key; ?>" 
                                                    <?php 
                                                    if (isset($paymentStatus) && $paymentStatus == $key) {
                                                        echo 'selected';
                                                    } else if (!isset($paymentStatus)) {
                                                        if ($totalPaid == 0 && $key == 'Advance') echo 'selected';
                                                        else if ($totalPaid > 0 && $totalPaid < $event['Cost'] && $key == 'Partial') echo 'selected';
                                                        else if ($totalPaid + $remainingAmount >= $event['Cost'] && $key == 'Completed') echo 'selected';
                                                    }
                                                    ?>
                                                ><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['paymentStatus'])): ?>
                                            <span class="text-danger"><?php echo $errors['paymentStatus']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn btn-primary">Record Payment</button>
                                        <a href="viewEvent.php?id=<?php echo $eventId; ?>" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>