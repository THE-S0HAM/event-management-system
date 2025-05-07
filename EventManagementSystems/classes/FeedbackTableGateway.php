<?php
require_once 'Feedback.php';

class FeedbackTableGateway {
    private $connect;
    
    public function __construct($c) {
        $this->connect = $c;
    }
    
    public function getFeedback() {
        $sqlQuery = "SELECT f.*, e.Title as EventTitle, c.FirstName, c.LastName 
                    FROM feedback f 
                    JOIN events e ON f.EventID = e.EventID 
                    JOIN clients c ON f.ClientID = c.ClientID 
                    ORDER BY f.FeedbackDate DESC";
        
        $statement = $this->connect->prepare($sqlQuery);
        $status = $statement->execute();
        
        if (!$status) {
            die("Could not retrieve feedback");
        }
        
        return $statement;
    }
    
    public function getFeedbackById($id) {
        $sqlQuery = "SELECT * FROM feedback WHERE FeedbackID = :id";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array("id" => $id);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve feedback");
        }
        
        return $statement;
    }
    
    public function getFeedbackByEventId($eventId) {
        $sqlQuery = "SELECT f.*, c.FirstName, c.LastName 
                    FROM feedback f 
                    JOIN clients c ON f.ClientID = c.ClientID 
                    WHERE f.EventID = :eventId 
                    ORDER BY f.FeedbackDate DESC";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array("eventId" => $eventId);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve feedback for event");
        }
        
        return $statement;
    }
    
    public function getFeedbackByClientId($clientId) {
        $sqlQuery = "SELECT f.*, e.Title as EventTitle 
                    FROM feedback f 
                    JOIN events e ON f.EventID = e.EventID 
                    WHERE f.ClientID = :clientId 
                    ORDER BY f.FeedbackDate DESC";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array("clientId" => $clientId);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve feedback for client");
        }
        
        return $statement;
    }
    
    public function insert($feedback) {
        $sql = "INSERT INTO feedback(EventID, ClientID, Rating, Comments, FeedbackDate) 
                VALUES (:EventID, :ClientID, :Rating, :Comments, :FeedbackDate)";
        
        $statement = $this->connect->prepare($sql);
        $params = array(
            "EventID" => $feedback->getEventID(),
            "ClientID" => $feedback->getClientID(),
            "Rating" => $feedback->getRating(),
            "Comments" => $feedback->getComments(),
            "FeedbackDate" => $feedback->getFeedbackDate()
        );
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not insert feedback");
        }
        
        return $this->connect->lastInsertId();
    }
    
    public function update($feedback) {
        $sql = "UPDATE feedback SET 
                Rating = :Rating, 
                Comments = :Comments 
                WHERE FeedbackID = :id";
        
        $statement = $this->connect->prepare($sql);
        $params = array(
            "Rating" => $feedback->getRating(),
            "Comments" => $feedback->getComments(),
            "id" => $feedback->getId()
        );
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not update feedback");
        }
    }
    
    public function delete($id) {
        $sql = "DELETE FROM feedback WHERE FeedbackID = :id";
        
        $statement = $this->connect->prepare($sql);
        $params = array("id" => $id);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not delete feedback");
        }
    }
    
    public function getAverageRating($eventId = null) {
        if ($eventId) {
            $sqlQuery = "SELECT AVG(Rating) as average FROM feedback WHERE EventID = :eventId";
            $params = array("eventId" => $eventId);
        } else {
            $sqlQuery = "SELECT AVG(Rating) as average FROM feedback";
            $params = array();
        }
        
        $statement = $this->connect->prepare($sqlQuery);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not calculate average rating");
        }
        
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['average'] ? round($result['average'], 1) : 0;
    }
}
?>