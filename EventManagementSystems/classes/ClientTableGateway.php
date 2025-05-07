<?php
require_once 'Client.php';

class ClientTableGateway {
    private $connect;
    
    public function __construct($c) {
        $this->connect = $c;
    }
    
    public function getClients() {
        // execute a query to get all clients
        $sqlQuery = "SELECT * FROM clients ORDER BY LastName, FirstName";
        
        $statement = $this->connect->prepare($sqlQuery);
        $status = $statement->execute();
        
        if (!$status) {
            die("Could not retrieve client details");
        }
        
        return $statement;
    }
    
    public function getClientById($id) {
        // execute a query to get a client with the specified id
        $sqlQuery = "SELECT * FROM clients WHERE ClientID = :id";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array(
            "id" => $id
        );
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve Client ID");
        }
        
        return $statement;
    }
    
    public function insert($client) {
        $sql = "INSERT INTO clients(FirstName, LastName, Email, Phone, Address, SpecialInstructions) " .
                "VALUES (:FirstName, :LastName, :Email, :Phone, :Address, :SpecialInstructions)";
        
        $statement = $this->connect->prepare($sql);
        $params = array(
            "FirstName" => $client->getFirstName(),
            "LastName" => $client->getLastName(),
            "Email" => $client->getEmail(),
            "Phone" => $client->getPhone(),
            "Address" => $client->getAddress(),
            "SpecialInstructions" => $client->getSpecialInstructions()
        );
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not insert client");
        }
        
        $id = $this->connect->lastInsertId();
        
        return $id;
    }
    
    public function update($client) {
        $sql = "UPDATE clients SET " .
                "FirstName = :FirstName, " .
                "LastName = :LastName, " .
                "Email = :Email, " .
                "Phone = :Phone, " .
                "Address = :Address, " .
                "SpecialInstructions = :SpecialInstructions " .
                "WHERE ClientID = :id";
        
        $statement = $this->connect->prepare($sql);
        $params = array(
            "FirstName" => $client->getFirstName(),
            "LastName" => $client->getLastName(),
            "Email" => $client->getEmail(),
            "Phone" => $client->getPhone(),
            "Address" => $client->getAddress(),
            "SpecialInstructions" => $client->getSpecialInstructions(),
            "id" => $client->getId()
        );
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not update client details");
        }
    }
    
    public function delete($id) {
        // First check if client has any events
        $sqlCheck = "SELECT COUNT(*) as count FROM events WHERE ClientID = :id";
        $checkStatement = $this->connect->prepare($sqlCheck);
        $checkParams = array("id" => $id);
        $checkStatus = $checkStatement->execute($checkParams);
        
        if (!$checkStatus) {
            die("Could not check client's events");
        }
        
        $result = $checkStatement->fetch(PDO::FETCH_ASSOC);
        if ($result['count'] > 0) {
            return false; // Client has events, cannot delete
        }
        
        // If no events, proceed with deletion
        $sql = "DELETE FROM clients WHERE ClientID = :id";
        $statement = $this->connect->prepare($sql);
        $params = array("id" => $id);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not delete client");
        }
        
        return true;
    }
    
    public function getClientEvents($id) {
        $sqlQuery = "SELECT e.*, l.Name as LocationName 
                    FROM events e 
                    JOIN locations l ON e.LocationID = l.LocationID 
                    WHERE e.ClientID = :id 
                    ORDER BY e.StartDate DESC";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array("id" => $id);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve client events");
        }
        
        return $statement;
    }
    
    public function getClientPayments($id) {
        $sqlQuery = "SELECT p.*, e.Title as EventTitle 
                    FROM payments p 
                    JOIN events e ON p.EventID = e.EventID 
                    WHERE p.ClientID = :id 
                    ORDER BY p.PaymentDate DESC";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array("id" => $id);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve client payments");
        }
        
        return $statement;
    }
}
?>