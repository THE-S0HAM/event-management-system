<?php
require_once 'utils/functions.php';
require_once 'classes/DB.php';
require_once 'classes/Location.php';
require_once 'classes/LocationTableGateway.php';
require_once 'classes/EventTableGateway.php';
require_once 'classes/Connection.php';

$connection = Connection::getInstance();
$gateway = new LocationTableGateway($connection);
$eventGateway = new EventTableGateway($connection);

$locations = $gateway->getLocations();

$message = '';
$available = null;
$selectedLocation = null;
$startDate = '';
$endDate = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locationID = filter_input(INPUT_POST, 'locationID', FILTER_SANITIZE_NUMBER_INT);
    $startDate = filter_input(INPUT_POST, 'startDate', FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_STRING);
    
    if (!$locationID || !$startDate || !$endDate) {
        $message = 'Please fill in all fields';
    } else {
        $available = $eventGateway->getAvailableDates($locationID, $startDate, $endDate);
        $locationStmt = $gateway->getLocationsById($locationID);
        $selectedLocation = $locationStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($available) {
            $message = 'The venue is available for your selected dates!';
        } else {
            $message = 'Sorry, the venue is not available for your selected dates. Please try different dates.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Check Venue Availability - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <div class="col-md-12">
                    <h1>Check Venue Availability</h1>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-6">
                    <form action="checkAvailability.php" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label for="locationID" class="col-md-3 control-label">Select Venue</label>
                            <div class="col-md-9">
                                <select name="locationID" class="form-control" required>
                                    <option value="">-- Select Venue --</option>
                                    <?php
                                    $locations->execute();
                                    while ($row = $locations->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row['LocationID'] . '"';
                                        if (isset($locationID) && $locationID == $row['LocationID']) {
                                            echo ' selected';
                                        }
                                        echo '>' . $row['Name'] . ' (Capacity: ' . $row['MaxCapacity'] . ')</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="startDate" class="col-md-3 control-label">Start Date</label>
                            <div class="col-md-9">
                                <input type="date" name="startDate" class="form-control" value="<?php echo $startDate; ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="endDate" class="col-md-3 control-label">End Date</label>
                            <div class="col-md-9">
                                <input type="date" name="endDate" class="form-control" value="<?php echo $endDate; ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-primary">Check Availability</button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="col-md-6">
                    <?php if ($message): ?>
                        <div class="panel panel-<?php echo $available ? 'success' : 'danger'; ?>">
                            <div class="panel-heading">
                                <h3 class="panel-title">Availability Result</h3>
                            </div>
                            <div class="panel-body">
                                <p><?php echo $message; ?></p>
                                
                                <?php if ($selectedLocation && $available): ?>
                                    <div class="venue-details">
                                        <h4><?php echo $selectedLocation['Name']; ?></h4>
                                        <p><strong>Address:</strong> <?php echo $selectedLocation['Address']; ?></p>
                                        <p><strong>Maximum Capacity:</strong> <?php echo $selectedLocation['MaxCapacity']; ?> guests</p>
                                        <p><strong>Selected Dates:</strong> <?php echo $startDate; ?> to <?php echo $endDate; ?></p>
                                        
                                        <div class="booking-actions">
                                            <a href="contact.php" class="btn btn-success">Request Booking</a>
                                            <a href="events2.php" class="btn btn-info">View Events</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if (!$available): ?>
                            <div class="alternative-suggestions">
                                <h4>Alternative Options</h4>
                                <ul>
                                    <li>Try different dates</li>
                                    <li>Consider our other venues</li>
                                    <li><a href="contact.php">Contact us</a> for personalized assistance</li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="venue-info">
                            <h3>Our Venues</h3>
                            <div class="venue-list">
                                <div class="venue-item">
                                    <h4>Mangal Karyalay</h4>
                                    <p>Perfect for traditional wedding ceremonies</p>
                                    <p>Capacity: 500 guests</p>
                                </div>
                                <div class="venue-item">
                                    <h4>Vivah Hall</h4>
                                    <p>Ideal for engagement ceremonies and smaller gatherings</p>
                                    <p>Capacity: 300 guests</p>
                                </div>
                                <div class="venue-item">
                                    <h4>Lawn A</h4>
                                    <p>Spacious outdoor venue for grand celebrations</p>
                                    <p>Capacity: 800 guests</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>