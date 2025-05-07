<?php
require_once 'utils/functions.php';
require_once 'classes/DB.php';
require_once 'classes/Location.php';
require_once 'classes/LocationTableGateway.php';
require_once 'classes/Client.php';
require_once 'classes/ClientTableGateway.php';
require_once 'classes/EventTableGateway.php';
require_once 'classes/Connection.php';

if (!is_logged_in()) {
    header("Location: login_form.php");
}

$connection = Connection::getInstance();
$locationGateway = new LocationTableGateway($connection);
$clientGateway = new ClientTableGateway($connection);
$eventGateway = new EventTableGateway($connection);

$locations = $locationGateway->getLocations();
$clients = $clientGateway->getClients();
$ceremonyTypes = $eventGateway->getCeremonyTypes();
$decorationThemes = $eventGateway->getDecorationThemes();
$cateringOptions = $eventGateway->getCateringOptions();

// Pre-select client if provided in URL
$selectedClientId = isset($_GET['clientId']) ? $_GET['clientId'] : null;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create Event - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <h1>Create Event</h1>
                <p>Please enter the details of the new event below</p>
                
                <form action="createEvent.php" method="POST" class="form-horizontal">
                    <div class="form-group">
                        <label for="title" class="col-md-2 control-label">Event Title</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Event Title" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="col-md-2 control-label">Description</label>
                        <div class="col-md-5">
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Event Description" required></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="startDate" class="col-md-2 control-label">Start Date</label>
                        <div class="col-md-5">
                            <input type="date" class="form-control" id="startDate" name="startDate" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="endDate" class="col-md-2 control-label">End Date</label>
                        <div class="col-md-5">
                            <input type="date" class="form-control" id="endDate" name="endDate" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="cost" class="col-md-2 control-label">Cost (₹)</label>
                        <div class="col-md-5">
                            <input type="number" class="form-control" id="cost" name="cost" placeholder="Event Cost" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="locationID" class="col-md-2 control-label">Venue</label>
                        <div class="col-md-5">
                            <select class="form-control" id="locationID" name="locationID" required>
                                <option value="">-- Select Venue --</option>
                                <?php
                                $locations->execute();
                                while ($row = $locations->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $row['LocationID'] . '">' . $row['Name'] . ' (Capacity: ' . $row['MaxCapacity'] . ')</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="clientID" class="col-md-2 control-label">Client</label>
                        <div class="col-md-5">
                            <select class="form-control" id="clientID" name="clientID" required>
                                <option value="">-- Select Client --</option>
                                <?php
                                $clients->execute();
                                while ($row = $clients->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $row['ClientID'] . '"';
                                    if ($selectedClientId && $selectedClientId == $row['ClientID']) {
                                        echo ' selected';
                                    }
                                    echo '>' . $row['FirstName'] . ' ' . $row['LastName'] . '</option>';
                                }
                                ?>
                            </select>
                            <p class="help-block"><a href="createClient.php">Add new client</a></p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="ceremonyType" class="col-md-2 control-label">Ceremony Type</label>
                        <div class="col-md-5">
                            <select class="form-control" id="ceremonyType" name="ceremonyType">
                                <option value="">-- Select Ceremony Type --</option>
                                <?php
                                $ceremonyTypes->execute();
                                while ($row = $ceremonyTypes->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $row['CeremonyName'] . '">' . $row['CeremonyName'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="decorationTheme" class="col-md-2 control-label">Decoration Theme</label>
                        <div class="col-md-5">
                            <select class="form-control" id="decorationTheme" name="decorationTheme">
                                <option value="">-- Select Decoration Theme --</option>
                                <?php
                                $decorationThemes->execute();
                                while ($row = $decorationThemes->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $row['ThemeName'] . '">' . $row['ThemeName'] . ' (₹' . number_format($row['BaseCost']) . ')</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="cateringOption" class="col-md-2 control-label">Catering Option</label>
                        <div class="col-md-5">
                            <select class="form-control" id="cateringOption" name="cateringOption">
                                <option value="">-- Select Catering Option --</option>
                                <?php
                                $cateringOptions->execute();
                                while ($row = $cateringOptions->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $row['OptionName'] . '">' . $row['OptionName'] . ' (₹' . $row['CostPerPlate'] . ' per plate)</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <button type="submit" class="btn btn-primary">Create Event</button>
                            <a href="viewEvents.php" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>