<?php
require_once 'utils/functions.php';

if (!is_logged_in()) {
    header("Location: login_form.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create Venue - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <h1>Create Venue</h1>
                <p>Please enter the details of the new venue below</p>
                
                <form action="createLocation.php" method="POST" class="form-horizontal">
                    <div class="form-group">
                        <label for="Name" class="col-md-2 control-label">Venue Name</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="Name" name="Name" value="<?php if (isset($formdata['Name'])) echo $formdata['Name']; ?>" />
                            <span id="nameError" class="error">
                                <?php if (isset($errors['Name'])) echo $errors['Name']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Address" class="col-md-2 control-label">Address</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="Address" name="Address" value="<?php if (isset($formdata['Address'])) echo $formdata['Address']; ?>" />
                            <span id="addressError" class="error">
                                <?php if (isset($errors['Address'])) echo $errors['Address']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerFName" class="col-md-2 control-label">Manager First Name</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="ManagerFName" name="ManagerFName" value="<?php if (isset($formdata['ManagerFName'])) echo $formdata['ManagerFName']; ?>" />
                            <span id="managerFNameError" class="error">
                                <?php if (isset($errors['ManagerFName'])) echo $errors['ManagerFName']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerLName" class="col-md-2 control-label">Manager Last Name</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="ManagerLName" name="ManagerLName" value="<?php if (isset($formdata['ManagerLName'])) echo $formdata['ManagerLName']; ?>" />
                            <span id="managerLNameError" class="error">
                                <?php if (isset($errors['ManagerLName'])) echo $errors['ManagerLName']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerEmail" class="col-md-2 control-label">Manager Email</label>
                        <div class="col-md-5">
                            <input type="email" class="form-control" id="ManagerEmail" name="ManagerEmail" value="<?php if (isset($formdata['ManagerEmail'])) echo $formdata['ManagerEmail']; ?>" />
                            <span id="managerEmailError" class="error">
                                <?php if (isset($errors['ManagerEmail'])) echo $errors['ManagerEmail']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerNumber" class="col-md-2 control-label">Manager Number</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="ManagerNumber" name="ManagerNumber" value="<?php if (isset($formdata['ManagerNumber'])) echo $formdata['ManagerNumber']; ?>" />
                            <span id="managerNumberError" class="error">
                                <?php if (isset($errors['ManagerNumber'])) echo $errors['ManagerNumber']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="MaxCapacity" class="col-md-2 control-label">Maximum Capacity</label>
                        <div class="col-md-5">
                            <input type="number" class="form-control" id="MaxCapacity" name="MaxCapacity" value="<?php if (isset($formdata['MaxCapacity'])) echo $formdata['MaxCapacity']; ?>" />
                            <span id="maxCapacityError" class="error">
                                <?php if (isset($errors['MaxCapacity'])) echo $errors['MaxCapacity']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <button type="submit" class="btn btn-primary">Create Venue</button>
                            <a href="viewLocations.php" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>