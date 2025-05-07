<?php
class Feedback {
    private $id;
    private $eventID;
    private $clientID;
    private $rating;
    private $comments;
    private $feedbackDate;
    
    public function __construct($id, $eventID, $clientID, $rating, $comments, $feedbackDate) {
        $this->id = $id;
        $this->eventID = $eventID;
        $this->clientID = $clientID;
        $this->rating = $rating;
        $this->comments = $comments;
        $this->feedbackDate = $feedbackDate;
    }
    
    public function getId() { return $this->id; }
    public function getEventID() { return $this->eventID; }
    public function getClientID() { return $this->clientID; }
    public function getRating() { return $this->rating; }
    public function getComments() { return $this->comments; }
    public function getFeedbackDate() { return $this->feedbackDate; }
}
?>