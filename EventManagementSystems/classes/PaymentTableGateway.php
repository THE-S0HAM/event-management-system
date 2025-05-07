<?php
require_once 'Payment.php';

class PaymentTableGateway {
    private $connect;
    
    public function __construct($c) {
        $this->connect = $c;
    }
    
    public function getPayments() {
        $sqlQuery = "SELECT p.*, e.Title as EventTitle, c.FirstName, c.LastName 
                    FROM payments p 
                    JOIN events e ON p.EventID = e.EventID 
                    JOIN clients c ON p.ClientID = c.ClientID 
                    ORDER BY p.PaymentDate DESC";
        
        $statement = $this->connect->prepare($sqlQuery);
        $status = $statement->execute();
        
        if (!$status) {
            die("Could not retrieve payments");
        }
        
        return $statement;
    }
    
    public function getPaymentById($id) {
        $sqlQuery = "SELECT * FROM payments WHERE PaymentID = :id";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array("id" => $id);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve payment");
        }
        
        return $statement;
    }
    
    public function getPaymentsByEventId($eventId) {
        $sqlQuery = "SELECT p.*, c.FirstName, c.LastName 
                    FROM payments p 
                    JOIN clients c ON p.ClientID = c.ClientID 
                    WHERE p.EventID = :eventId 
                    ORDER BY p.PaymentDate DESC";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array("eventId" => $eventId);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve payments for event");
        }
        
        return $statement;
    }
    
    public function getPaymentsByClientId($clientId) {
        $sqlQuery = "SELECT p.*, e.Title as EventTitle 
                    FROM payments p 
                    JOIN events e ON p.EventID = e.EventID 
                    WHERE p.ClientID = :clientId 
                    ORDER BY p.PaymentDate DESC";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array("clientId" => $clientId);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve payments for client");
        }
        
        return $statement;
    }
    
    public function insert($payment) {
        $sql = "INSERT INTO payments(EventID, ClientID, Amount, PaymentDate, PaymentMethod, PaymentStatus) 
                VALUES (:EventID, :ClientID, :Amount, :PaymentDate, :PaymentMethod, :PaymentStatus)";
        
        $statement = $this->connect->prepare($sql);
        $params = array(
            "EventID" => $payment->getEventID(),
            "ClientID" => $payment->getClientID(),
            "Amount" => $payment->getAmount(),
            "PaymentDate" => $payment->getPaymentDate(),
            "PaymentMethod" => $payment->getPaymentMethod(),
            "PaymentStatus" => $payment->getPaymentStatus()
        );
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not insert payment");
        }
        
        return $this->connect->lastInsertId();
    }
    
    public function update($payment) {
        $sql = "UPDATE payments SET 
                Amount = :Amount, 
                PaymentDate = :PaymentDate, 
                PaymentMethod = :PaymentMethod, 
                PaymentStatus = :PaymentStatus 
                WHERE PaymentID = :id";
        
        $statement = $this->connect->prepare($sql);
        $params = array(
            "Amount" => $payment->getAmount(),
            "PaymentDate" => $payment->getPaymentDate(),
            "PaymentMethod" => $payment->getPaymentMethod(),
            "PaymentStatus" => $payment->getPaymentStatus(),
            "id" => $payment->getId()
        );
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not update payment");
        }
    }
    
    public function delete($id) {
        $sql = "DELETE FROM payments WHERE PaymentID = :id";
        
        $statement = $this->connect->prepare($sql);
        $params = array("id" => $id);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not delete payment");
        }
    }
    
    public function getTotalPaymentsByEvent($eventId) {
        $sqlQuery = "SELECT SUM(Amount) as total FROM payments WHERE EventID = :eventId";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array("eventId" => $eventId);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not calculate total payments");
        }
        
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ? $result['total'] : 0;
    }
    
    public function getPaymentMethods() {
        return array(
            'Cash' => 'Cash',
            'Bank Transfer' => 'Bank Transfer',
            'UPI' => 'UPI',
            'Credit Card' => 'Credit Card',
            'Debit Card' => 'Debit Card',
            'Cheque' => 'Cheque'
        );
    }
    
    public function getPaymentStatuses() {
        return array(
            'Advance' => 'Advance',
            'Partial' => 'Partial',
            'Completed' => 'Completed',
            'Refunded' => 'Refunded'
        );
    }
}
?>