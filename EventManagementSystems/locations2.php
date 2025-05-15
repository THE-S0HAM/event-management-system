<?php
require_once 'classes/DB.php';
require_once 'classes/Location.php';
require_once 'classes/LocationTableGateway.php';
require_once 'classes/Connection.php';

$connection = Connection::getInstance();
$gateway = new LocationTableGateway($connection);

$statement = $gateway->getLocations();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Our Venues - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <div class="col-md-12">
                    <h1>Our Venues</h1>
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
                // Determine image to use based on venue name
                $image = "images/tradinational-wed.jpg"; // Default image
                $modalId = "venue" . $row['LocationID'];
                
                if (stripos($row['Name'], 'Mangal') !== false) {
                    $image = "images/tradinational-wed.jpg";
                } else if (stripos($row['Name'], 'Vivah') !== false || stripos($row['Name'], 'Hall') !== false) {
                    $image = "images/sarkharpuda.jpg";
                } else if (stripos($row['Name'], 'Lawn') !== false) {
                    $image = "images/reception.jpg";
                }
                
                // Add a separator between venues
                if ($count > 0) {
                    echo '<div class="container"><div class="col-md-12"><hr></div></div>';
                }
                $count++;
            ?>
            <div class="row">
                <section>
                    <div class="container">
                        <div class="col-md-4">
                            <img src="<?php echo $image; ?>" class="img-responsive">
                        </div>
                        <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel"><?php echo strtoupper($row['Name']); ?></h4>
                                    </div>
                                    <div class="row">
                                        <div class="modal-body">
                                            <div class="col-md-6">
                                                <img src="<?php echo $image; ?>" class="img-responsive">
                                            </div>
                                            <div class="modaltext1 col-md-6">
                                                <h5>PERFECT VENUE FOR YOUR SPECIAL OCCASION</h5>
                                                <p>
                                                <?php echo $row['Name']; ?> is located in the heart of Waladgaon, Shrirampur, offering a beautiful setting for your special event.
                                                </p>
                                            </div> 
                                            <div class="modaltext2 col-md-12">
                                                <p>
                                                With a capacity to accommodate up to <?php echo $row['MaxCapacity']; ?> guests, this venue provides the perfect setting for your celebration.
                                                </p>

                                                <p>
                                                Our venue comes equipped with all necessary facilities including excellent sound systems, beautiful decor options, and a comfortable environment for your special day.
                                                </p>
                                                
                                                <p>
                                                <strong>Manager:</strong> <?php echo $row['ManagerFName'] . ' ' . $row['ManagerLName']; ?><br>
                                                <strong>Contact:</strong> <?php echo $row['ManagerEmail']; ?> | <?php echo $row['ManagerNumber']; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-default closebtn" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="subcontent col-md-8">
                            <h1 class="title"><?php echo $row['Name']; ?></h1>
                            <p class="location"><?php echo $row['Address']; ?></p>
                            <p class="definition">
                                A beautiful venue with a capacity of <?php echo $row['MaxCapacity']; ?> guests, perfect for your special occasions.
                                <?php echo $row['Name']; ?> provides an elegant setting with all the necessary amenities to make your event memorable.
                            </p>
                            <hr class="customline3">
                            <button type="button" class="btn btn-default btn-md" data-toggle="modal" data-target="#<?php echo $modalId; ?>">
                                More Details  <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
            <?php
            }
            
            // If no venues found
            if ($count == 0) {
                echo '<div class="row"><div class="container"><div class="col-md-12">';
                echo '<div class="alert alert-info">No venues found.</div>';
                echo '</div></div></div>';
            }
            ?>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>