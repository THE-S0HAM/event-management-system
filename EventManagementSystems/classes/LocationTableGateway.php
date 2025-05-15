<?php
require_once 'Location.php';

class LocationTableGateway {

    private $connect;
    
    public function __construct($c) {
        $this->connect = $c;
    }
    
    public function getLocations() {
        // execute a query to get all locations
        $sqlQuery = "SELECT * FROM locations";
        
        $statement = $this->connect->prepare($sqlQuery);
        $status = $statement->execute();
        
        if (!$status) {
            die("Could not retrieve locations");
        }
        
        return $statement;
    }
    
    public function getLocationsById($id) {
        // execute a query to get location with the specified id
        $sqlQuery = "SELECT * FROM locations WHERE locationID = :id";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array(
            "id" => $id
        );
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve location");
        }
        
        return $statement;
    }
    
    public function insert($n, $a, $mfn, $mln, $me, $mn, $mc, $imagePath = null) {
        try {
            // Check if ImagePath column exists
            $checkColumn = "SHOW COLUMNS FROM `locations` LIKE 'ImagePath'";
            $result = $this->connect->query($checkColumn);
            
            if ($result->rowCount() == 0) {
                // Column doesn't exist, use old query without ImagePath
                $sqlInsert = "INSERT INTO locations (name, address, managerFName, managerLName, managerEmail, managerNumber, maxCapacity) "
                        . "VALUES (:name, :address, :managerFName, :managerLName, :managerEmail, :managerNumber, :maxCapacity)";
                
                $statement = $this->connect->prepare($sqlInsert);
                $params = array(
                    "name" => $n,
                    "address" => $a,
                    "managerFName" => $mfn,
                    "managerLName" => $mln,
                    "managerEmail" => $me,
                    "managerNumber" => $mn,
                    "maxCapacity" => $mc
                );
            } else {
                // Column exists, use new query with ImagePath
                $sqlInsert = "INSERT INTO locations (name, address, managerFName, managerLName, managerEmail, managerNumber, maxCapacity, ImagePath) "
                        . "VALUES (:name, :address, :managerFName, :managerLName, :managerEmail, :managerNumber, :maxCapacity, :imagePath)";
                
                $statement = $this->connect->prepare($sqlInsert);
                $params = array(
                    "name" => $n,
                    "address" => $a,
                    "managerFName" => $mfn,
                    "managerLName" => $mln,
                    "managerEmail" => $me,
                    "managerNumber" => $mn,
                    "maxCapacity" => $mc,
                    "imagePath" => $imagePath
                );
            }
            
            $status = $statement->execute($params);
            
            if (!$status) {
                die("Could not insert location");
            }
            
            $id = $this->connect->lastInsertId();
            
            return $id;
        } catch (PDOException $e) {
            // If there's an error, try without the ImagePath column
            $sqlInsert = "INSERT INTO locations (name, address, managerFName, managerLName, managerEmail, managerNumber, maxCapacity) "
                    . "VALUES (:name, :address, :managerFName, :managerLName, :managerEmail, :managerNumber, :maxCapacity)";
            
            $statement = $this->connect->prepare($sqlInsert);
            $params = array(
                "name" => $n,
                "address" => $a,
                "managerFName" => $mfn,
                "managerLName" => $mln,
                "managerEmail" => $me,
                "managerNumber" => $mn,
                "maxCapacity" => $mc
            );
            
            $status = $statement->execute($params);
            
            if (!$status) {
                die("Could not insert location");
            }
            
            $id = $this->connect->lastInsertId();
            
            return $id;
        }
    }
    
    public function update($id, $n, $a, $mfn, $mln, $me, $mn, $mc, $imagePath = null) {
        try {
            // Check if ImagePath column exists
            $checkColumn = "SHOW COLUMNS FROM `locations` LIKE 'ImagePath'";
            $result = $this->connect->query($checkColumn);
            
            if ($result->rowCount() == 0) {
                // Column doesn't exist, use old query without ImagePath
                $sql = "UPDATE locations "
                        . "SET name = :name, "
                        . "address = :address, "
                        . "managerFName = :managerFName, "
                        . "managerLName = :managerLName, "
                        . "managerEmail = :managerEmail, "
                        . "managerNumber = :managerNumber, "
                        . "maxCapacity = :maxCapacity "
                        . "WHERE locationID = :id";
                
                $statement = $this->connect->prepare($sql);
                $params = array(
                    "name" => $n,
                    "address" => $a,
                    "managerFName" => $mfn,
                    "managerLName" => $mln,
                    "managerEmail" => $me,
                    "managerNumber" => $mn,
                    "maxCapacity" => $mc,
                    "id" => $id
                );
            } else {
                // Column exists, use new query with ImagePath
                $sql = "UPDATE locations "
                        . "SET name = :name, "
                        . "address = :address, "
                        . "managerFName = :managerFName, "
                        . "managerLName = :managerLName, "
                        . "managerEmail = :managerEmail, "
                        . "managerNumber = :managerNumber, "
                        . "maxCapacity = :maxCapacity";
                
                // Add image path to update if provided
                if ($imagePath !== null) {
                    $sql .= ", ImagePath = :imagePath";
                }
                
                $sql .= " WHERE locationID = :id";
                
                $statement = $this->connect->prepare($sql);
                $params = array(
                    "name" => $n,
                    "address" => $a,
                    "managerFName" => $mfn,
                    "managerLName" => $mln,
                    "managerEmail" => $me,
                    "managerNumber" => $mn,
                    "maxCapacity" => $mc,
                    "id" => $id
                );
                
                // Add image path to params if provided
                if ($imagePath !== null) {
                    $params["imagePath"] = $imagePath;
                }
            }
            
            $status = $statement->execute($params);
            
            if (!$status) {
                die("Could not update location");
            }
        } catch (PDOException $e) {
            // If there's an error, try without the ImagePath column
            $sql = "UPDATE locations "
                    . "SET name = :name, "
                    . "address = :address, "
                    . "managerFName = :managerFName, "
                    . "managerLName = :managerLName, "
                    . "managerEmail = :managerEmail, "
                    . "managerNumber = :managerNumber, "
                    . "maxCapacity = :maxCapacity "
                    . "WHERE locationID = :id";
            
            $statement = $this->connect->prepare($sql);
            $params = array(
                "name" => $n,
                "address" => $a,
                "managerFName" => $mfn,
                "managerLName" => $mln,
                "managerEmail" => $me,
                "managerNumber" => $mn,
                "maxCapacity" => $mc,
                "id" => $id
            );
            
            $status = $statement->execute($params);
            
            if (!$status) {
                die("Could not update location");
            }
        }
    }
    
    public function delete($id) {
        // execute a query to delete a location
        $sql = "DELETE FROM locations WHERE locationID = :id";
        
        $statement = $this->connect->prepare($sql);
        $params = array(
            "id" => $id
        );
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not delete location");
        }
    }
}