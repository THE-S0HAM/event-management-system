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

$statement = $gateway->getClients();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Client Management - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <div class="col-md-12">
                    <h1>Client Management</h1>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-12">
                    <?php if (isset($message)): ?>
                        <div class="alert alert-success">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <a href="createClient.php" class="btn btn-success pull-right">
                        <span class="glyphicon glyphicon-plus"></span> Add New Client
                    </a>
                    <br><br>
                    
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>';
                                echo '<td>' . $row['ClientID'] . '</td>';
                                echo '<td>' . $row['FirstName'] . ' ' . $row['LastName'] . '</td>';
                                echo '<td>' . $row['Email'] . '</td>';
                                echo '<td>' . $row['Phone'] . '</td>';
                                echo '<td>' . $row['Address'] . '</td>';
                                echo '<td>'
                                . '<a href="viewClient.php?id=' . $row['ClientID'] . '" class="btn btn-xs btn-info">View</a> '
                                . '<a href="editClient.php?id=' . $row['ClientID'] . '" class="btn btn-xs btn-warning">Edit</a> '
                                . '<a href="deleteClient.php?id=' . $row['ClientID'] . '" class="btn btn-xs btn-danger" onclick="return confirm(\'Are you sure you want to delete this client?\')">Delete</a>'
                                . '</td>';
                                echo '</tr>';
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