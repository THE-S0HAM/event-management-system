<?php
require_once 'utils/functions.php';
require_once 'classes/DB.php';
require_once 'classes/Feedback.php';
require_once 'classes/FeedbackTableGateway.php';
require_once 'classes/Event.php';
require_once 'classes/EventTableGateway.php';
require_once 'classes/Client.php';
require_once 'classes/ClientTableGateway.php';
require_once 'classes/Connection.php';

if (!is_logged_in()) {
    header("Location: login_form.php");
}

$connection = Connection::getInstance();
$feedbackGateway = new FeedbackTableGateway($connection);
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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_NUMBER_INT);
    $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING);
    $feedbackDate = date('Y-m-d');
    
    // Validate input
    $errors = array();
    
    if (empty($rating) || $rating < 1 || $rating > 5) {
        $errors['rating'] = "Please select a rating between 1 and 5";
    }
    
    // If no errors, save feedback
    if (empty($errors)) {
        $feedback = new Feedback(null, $eventId, $clientId, $rating, $comments, $feedbackDate);
        $id = $feedbackGateway->insert($feedback);
        
        // Redirect to event page with success message
        header("Location: viewEvent.php?id=$eventId&feedback=success");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Record Feedback - Laxmi Trimbak Lawns</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
        <style>
            .rating {
                display: flex;
                flex-direction: row-reverse;
                justify-content: flex-end;
            }
            .rating input {
                display: none;
            }
            .rating label {
                cursor: pointer;
                font-size: 30px;
                color: #ccc;
                padding: 0 5px;
            }
            .rating input:checked ~ label {
                color: #ffcc00;
            }
            .rating label:hover,
            .rating label:hover ~ label {
                color: #ffcc00;
            }
        </style>
    </head>
    <body>
        <?php require 'utils/header.php'; ?>
        <div class="content">
            <div class="container">
                <div class="col-md-12">
                    <h1>Record Feedback</h1>
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
                            <h3 class="panel-title">Feedback for: <?php echo $event['Title']; ?></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Event:</strong> <?php echo $event['Title']; ?></p>
                                    <p><strong>Date:</strong> <?php echo $event['StartDate']; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Client:</strong> <?php echo $client['FirstName'] . ' ' . $client['LastName']; ?></p>
                                    <p><strong>Email:</strong> <?php echo $client['Email']; ?></p>
                                </div>
                            </div>
                            
                            <form action="createFeedback.php?eventId=<?php echo $eventId; ?>&clientId=<?php echo $clientId; ?>" method="POST">
                                <div class="form-group">
                                    <label>Rating:</label>
                                    <div class="rating">
                                        <input type="radio" id="star5" name="rating" value="5" <?php if (isset($rating) && $rating == 5) echo 'checked'; ?>>
                                        <label for="star5">★</label>
                                        <input type="radio" id="star4" name="rating" value="4" <?php if (isset($rating) && $rating == 4) echo 'checked'; ?>>
                                        <label for="star4">★</label>
                                        <input type="radio" id="star3" name="rating" value="3" <?php if (isset($rating) && $rating == 3) echo 'checked'; ?>>
                                        <label for="star3">★</label>
                                        <input type="radio" id="star2" name="rating" value="2" <?php if (isset($rating) && $rating == 2) echo 'checked'; ?>>
                                        <label for="star2">★</label>
                                        <input type="radio" id="star1" name="rating" value="1" <?php if (isset($rating) && $rating == 1) echo 'checked'; ?>>
                                        <label for="star1">★</label>
                                    </div>
                                    <?php if (isset($errors['rating'])): ?>
                                        <span class="text-danger"><?php echo $errors['rating']; ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="form-group">
                                    <label for="comments">Comments:</label>
                                    <textarea class="form-control" id="comments" name="comments" rows="5"><?php if (isset($comments)) echo $comments; ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                                    <a href="viewEvent.php?id=<?php echo $eventId; ?>" class="btn btn-default">Cancel</a>
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