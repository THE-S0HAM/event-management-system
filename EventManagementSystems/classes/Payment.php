<?php
class Payment {
    private $id;
    private $eventID;
    private $clientID;
    private $amount;
    private $paymentDate;
    private $paymentMethod;
    private $paymentStatus;
    
    public function __construct($id, $eventID, $clientID, $amount, $paymentDate, $paymentMethod, $paymentStatus) {
        $this->id = $id;
        $this->eventID = $eventID;
        $this->clientID = $clientID;
        $this->amount = $amount;
        $this->paymentDate = $paymentDate;
        $this->paymentMethod = $paymentMethod;
        $this->paymentStatus = $paymentStatus;
    }
    
    public function getId() { return $this->id; }
    public function getEventID() { return $this->eventID; }
    public function getClientID() { return $this->clientID; }
    public function getAmount() { return $this->amount; }
    public function getPaymentDate() { return $this->paymentDate; }
    public function getPaymentMethod() { return $this->paymentMethod; }
    public function getPaymentStatus() { return $this->paymentStatus; }
}
?>