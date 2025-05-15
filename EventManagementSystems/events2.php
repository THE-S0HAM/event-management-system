<?php
require_once 'classes/DB.php';
require_once 'classes/Event.php';
require_once 'classes/EventTableGateway.php';
require_once 'classes/Location.php';
require_once 'classes/LocationTableGateway.php';
require_once 'classes/Connection.php';

$connection = Connection::getInstance();
$gateway = new EventTableGateway($connection);
$locationGateway = new LocationTableGateway($connection);

$statement = $gateway->getEvents();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Upcoming Events - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <div class="col-md-12">
                    <h1>Upcoming Events</h1>
                </div>
            </div>
			
            <div class="container">
            <div class="col-md-12">
            <hr>
            </div>
            </div>
			
            <?php
            $count = 0;
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                // Get location details
                $locationStatement = $locationGateway->getLocationsById($row['LocationID']);
                $location = $locationStatement->fetch(PDO::FETCH_ASSOC);
                
                // Format date for display
                $startDate = date_create_from_format('d-M-Y', $row['StartDate']);
                if (!$startDate) {
                    $startDate = date_create($row['StartDate']);
                }
                
                if ($startDate) {
                    $month = date_format($startDate, 'M');
                    $day = date_format($startDate, 'd');
                } else {
                    $month = "N/A";
                    $day = "N/A";
                }
                
                // Determine image to use based on ceremony type
                $image = "images/tradinational-wed.jpg"; // Default image
                
                if (!empty($row['CeremonyType'])) {
                    switch(strtolower($row['CeremonyType'])) {
                        case 'wedding':
                            $image = "images/tradinational-wed.jpg";
                            break;
                        case 'reception':
                            $image = "images/reception.jpg";
                            break;
                        case 'engagement':
                        case 'sakharpuda':
                            $image = "images/sarkharpuda.jpg";
                            break;
                        case 'sangeet':
                        case 'mehendi':
                            $image = "images/sangeet.jpg";
                            break;
                        case 'haldi':
                            $image = "images/sangeet.jpg"; // Using sangeet image for haldi as well
                            break;
                    }
                }
                
                // Add a separator between events
                if ($count > 0) {
                    echo '<div class="container"><div class="col-md-12"><hr></div></div>';
                }
                $count++;
            ?>
            <div class="row">
                <section>
                    <div class="container">
                        <div class="date col-md-1">
                            <span class="month"><?php echo strtoupper($month); ?></span><br>
                            <hr class="line">
                            <span class="day"><?php echo $day; ?></span>
                        </div>
                        <div class="col-md-5">
                            <img src="<?php echo $image; ?>" class="img-responsive">
                        </div>
                        <div class="subcontent col-md-6">
                            <h1 class="title"><?php echo $row['Title']; ?></h1>
                            <p class="location">
                                <?php echo $location['Name']; ?>, Laxmi Trimbak Lawns, Waladgaon, Shrirampur
                            </p>
                            <p class="definition">
                                <?php echo $row['Description']; ?>
                                <?php if (!empty($row['DecorationTheme']) || !empty($row['CateringOption'])): ?>
                                <br><br>
                                <?php if (!empty($row['DecorationTheme'])): ?>
                                    <strong>Decoration Theme:</strong> <?php echo $row['DecorationTheme']; ?><br>
                                <?php endif; ?>
                                <?php if (!empty($row['CateringOption'])): ?>
                                    <strong>Catering:</strong> <?php echo $row['CateringOption']; ?>
                                <?php endif; ?>
                                <?php endif; ?>
                            </p>
                            <hr class="customline2">
                            <button type="button" class="btn btn-default btn-lg" onclick="window.location.href='checkAvailability.php'">
                                Check Availability  <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
            <?php
            }
            
            // If no events found
            if ($count == 0) {
                echo '<div class="row"><div class="container"><div class="col-md-12">';
                echo '<div class="alert alert-info">No upcoming events found.</div>';
                echo '</div></div></div>';
            }
            ?>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>