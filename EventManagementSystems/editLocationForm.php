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
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Venue - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <h1>Edit Venue</h1>
                <p>Please update the venue details below</p>
                
                <form action="editLocation.php" method="POST" class="form-horizontal">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <div class="form-group">
                        <label for="Name" class="col-md-2 control-label">Venue Name</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="Name" name="Name" value="<?php echo $row['Name']; ?>" />
                            <span id="nameError" class="error">
                                <?php if (isset($errors['Name'])) echo $errors['Name']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Address" class="col-md-2 control-label">Address</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="Address" name="Address" value="<?php echo $row['Address']; ?>" />
                            <span id="addressError" class="error">
                                <?php if (isset($errors['Address'])) echo $errors['Address']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerFName" class="col-md-2 control-label">Manager First Name</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="ManagerFName" name="ManagerFName" value="<?php echo $row['ManagerFName']; ?>" />
                            <span id="managerFNameError" class="error">
                                <?php if (isset($errors['ManagerFName'])) echo $errors['ManagerFName']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerLName" class="col-md-2 control-label">Manager Last Name</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="ManagerLName" name="ManagerLName" value="<?php echo $row['ManagerLName']; ?>" />
                            <span id="managerLNameError" class="error">
                                <?php if (isset($errors['ManagerLName'])) echo $errors['ManagerLName']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerEmail" class="col-md-2 control-label">Manager Email</label>
                        <div class="col-md-5">
                            <input type="email" class="form-control" id="ManagerEmail" name="ManagerEmail" value="<?php echo $row['ManagerEmail']; ?>" />
                            <span id="managerEmailError" class="error">
                                <?php if (isset($errors['ManagerEmail'])) echo $errors['ManagerEmail']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerNumber" class="col-md-2 control-label">Manager Number</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="ManagerNumber" name="ManagerNumber" value="<?php echo $row['ManagerNumber']; ?>" />
                            <span id="managerNumberError" class="error">
                                <?php if (isset($errors['ManagerNumber'])) echo $errors['ManagerNumber']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="MaxCapacity" class="col-md-2 control-label">Maximum Capacity</label>
                        <div class="col-md-5">
                            <input type="number" class="form-control" id="MaxCapacity" name="MaxCapacity" value="<?php echo $row['MaxCapacity']; ?>" />
                            <span id="maxCapacityError" class="error">
                                <?php if (isset($errors['MaxCapacity'])) echo $errors['MaxCapacity']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <button type="submit" class="btn btn-primary">Update Venue</button>
                            <a href="viewLocations.php" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>