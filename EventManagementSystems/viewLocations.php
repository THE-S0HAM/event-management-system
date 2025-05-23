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

$statement = $gateway->getLocations();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Venues - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <div class="col-md-12">
                    <h1>Venues</h1>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-12">
                    <?php if (isset($message)) { ?>
                        <div class="alert alert-success">
                            <?php echo $message; ?>
                        </div>
                    <?php } ?>
                    
                    <a class="btn btn-success" href="createLocationForm.php">Create Venue</a>
                    <br><br>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Venue Name</th>
                                <th>Address</th>
                                <th>Manager</th>
                                <th>Max Capacity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $row = $statement->fetch(PDO::FETCH_ASSOC);
                            while ($row) {
                                echo '<tr>';
                                echo '<td>' . $row['Name'] . '</td>';
                                echo '<td>' . $row['Address'] . '</td>';
                                echo '<td>' . $row['ManagerFName'] . ' ' . $row['ManagerLName'] . '</td>';
                                echo '<td>' . $row['MaxCapacity'] . '</td>';
                                echo '<td>'
                                . '<a class="btn btn-info" href="viewLocation.php?id=' . $row['LocationID'] . '">View</a> '
                                . '<a class="btn btn-warning" href="editLocationForm.php?id=' . $row['LocationID'] . '">Edit</a> '
                                . '<a class="btn btn-danger" href="deleteLocation.php?id=' . $row['LocationID'] . '">Delete</a> '
                                . '</td>';
                                echo '</tr>';  
                                
                                $row = $statement->fetch(PDO::FETCH_ASSOC);
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>