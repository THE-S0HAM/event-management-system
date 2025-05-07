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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $specialInstructions = filter_input(INPUT_POST, 'specialInstructions', FILTER_SANITIZE_STRING);
    
    // Validate form data
    $errors = array();
    
    if (empty($firstName)) {
        $errors['firstName'] = "First name is required";
    }
    
    if (empty($lastName)) {
        $errors['lastName'] = "Last name is required";
    }
    
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }
    
    if (empty($phone)) {
        $errors['phone'] = "Phone number is required";
    }
    
    if (empty($address)) {
        $errors['address'] = "Address is required";
    }
    
    // If no errors, create client
    if (empty($errors)) {
        $client = new Client(null, $firstName, $lastName, $email, $phone, $address, $specialInstructions);
        $id = $gateway->insert($client);
        
        header("Location: viewClients.php?success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Add New Client - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <div class="col-md-12">
                    <h1>Add New Client</h1>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            
            <div class="container">
                <form action="createClient.php" method="POST" class="form-horizontal">
                    <div class="form-group">
                        <label for="firstName" class="col-md-2 control-label">First Name</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php if (isset($firstName)) echo $firstName; ?>">
                            <span class="error">
                                <?php if (isset($errors['firstName'])) echo $errors['firstName']; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="lastName" class="col-md-2 control-label">Last Name</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php if (isset($lastName)) echo $lastName; ?>">
                            <span class="error">
                                <?php if (isset($errors['lastName'])) echo $errors['lastName']; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="col-md-2 control-label">Email</label>
                        <div class="col-md-5">
                            <input type="email" class="form-control" id="email" name="email" value="<?php if (isset($email)) echo $email; ?>">
                            <span class="error">
                                <?php if (isset($errors['email'])) echo $errors['email']; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="col-md-2 control-label">Phone</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php if (isset($phone)) echo $phone; ?>">
                            <span class="error">
                                <?php if (isset($errors['phone'])) echo $errors['phone']; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address" class="col-md-2 control-label">Address</label>
                        <div class="col-md-5">
                            <textarea class="form-control" id="address" name="address" rows="3"><?php if (isset($address)) echo $address; ?></textarea>
                            <span class="error">
                                <?php if (isset($errors['address'])) echo $errors['address']; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="specialInstructions" class="col-md-2 control-label">Special Instructions</label>
                        <div class="col-md-5">
                            <textarea class="form-control" id="specialInstructions" name="specialInstructions" rows="5"><?php if (isset($specialInstructions)) echo $specialInstructions; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <button type="submit" class="btn btn-primary">Save Client</button>
                            <a href="viewClients.php" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>