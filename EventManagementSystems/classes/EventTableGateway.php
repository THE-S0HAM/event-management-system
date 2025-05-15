<?php
require_once 'Event.php';

class EventTableGateway {

    private $connect;
    
    public function __construct($c) {
        $this->connect = $c;
    }
    
    public function getEvents() {
        // execute a query to get all events
        $sqlQuery = "SELECT e.*, l.name, c.FirstName, c.LastName " .
                    "FROM events e " .
                    "LEFT JOIN locations l ON e.locationID = l.locationID " .
                    "LEFT JOIN clients c ON e.clientID = c.clientID";
        
        $statement = $this->connect->prepare($sqlQuery);
        $status = $statement->execute();
        
        if (!$status) {
            die("Could not retrieve event details");
        }
        
        return $statement;
    }
    
    public function getEventsByLocationId($id) {
        // execute a query to get all events
        $sqlQuery = "SELECT e.*, l.name, c.FirstName, c.LastName " .
                    "FROM events e " .
                    "LEFT JOIN locations l ON e.locationID = l.locationID " .
                    "LEFT JOIN clients c ON e.clientID = c.clientID " .
                    "WHERE e.locationID=:locationId";
        
        $params = array(
            "locationId" => $id
        );
        $statement = $this->connect->prepare($sqlQuery);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve event details");
        }
        
        return $statement;
    }
    
    public function getEventsById($id) {
        // execute a query to get an event with the specified id
        $sqlQuery = "SELECT * FROM events WHERE eventID = :id";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array(
            "id" => $id
        );
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve Event ID");
        }
        
        return $statement;
    }
    
    public function getAvailableDates($locationID, $startDate, $endDate) {
        // Check if the location is available for the given dates
        $sqlQuery = "SELECT COUNT(*) as count FROM events 
                    WHERE locationID = :locationID 
                    AND ((StartDate BETWEEN :startDate AND :endDate) 
                    OR (EndDate BETWEEN :startDate AND :endDate)
                    OR (:startDate BETWEEN StartDate AND EndDate))";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array(
            "locationID" => $locationID,
            "startDate" => $startDate,
            "endDate" => $endDate
        );
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not check availability");
        }
        
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['count'] == 0; // Returns true if available
    }
    
    public function insert($p, $imagePath = null) {
        // Check if ImagePath column exists
        try {
            $checkColumn = "SHOW COLUMNS FROM `events` LIKE 'ImagePath'";
            $result = $this->connect->query($checkColumn);
            
            if ($result->rowCount() == 0) {
                // Column doesn't exist, use old query without ImagePath
                $sql = "INSERT INTO events(Title, Description, StartDate, EndDate, Cost, LocationID, CeremonyType, DecorationTheme, CateringOption, ClientID) " .
                        "VALUES (:Title, :Description, :StartDate, :EndDate, :Cost, :LocationID, :CeremonyType, :DecorationTheme, :CateringOption, :ClientID)";
                
                $statement = $this->connect->prepare($sql);
                $params = array(
                    "Title"           => $p->getTitle(),
                    "Description"     => $p->getDescription(),            
                    "StartDate"       => $p->getStartDate(),
                    "EndDate"         => $p->getEndDate(),
                    "Cost"            => $p->getCost(),
                    "LocationID"      => $p->getLocationID(),
                    "CeremonyType"    => $p->getCeremonyType(),
                    "DecorationTheme" => $p->getDecorationTheme(),
                    "CateringOption"  => $p->getCateringOption(),
                    "ClientID"        => $p->getClientID()
                );
            } else {
                // Column exists, use new query with ImagePath
                $sql = "INSERT INTO events(Title, Description, StartDate, EndDate, Cost, LocationID, CeremonyType, DecorationTheme, CateringOption, ClientID, ImagePath) " .
                        "VALUES (:Title, :Description, :StartDate, :EndDate, :Cost, :LocationID, :CeremonyType, :DecorationTheme, :CateringOption, :ClientID, :ImagePath)";
                
                $statement = $this->connect->prepare($sql);
                $params = array(
                    "Title"           => $p->getTitle(),
                    "Description"     => $p->getDescription(),            
                    "StartDate"       => $p->getStartDate(),
                    "EndDate"         => $p->getEndDate(),
                    "Cost"            => $p->getCost(),
                    "LocationID"      => $p->getLocationID(),
                    "CeremonyType"    => $p->getCeremonyType(),
                    "DecorationTheme" => $p->getDecorationTheme(),
                    "CateringOption"  => $p->getCateringOption(),
                    "ClientID"        => $p->getClientID(),
                    "ImagePath"       => $imagePath
                );
            }
            
            $status = $statement->execute($params);
            
            if (!$status) {
                die("Could not insert event");
            }
            
            $id = $this->connect->lastInsertId();
            
            return $id;
        } catch (PDOException $e) {
            // If there's an error, try without the ImagePath column
            $sql = "INSERT INTO events(Title, Description, StartDate, EndDate, Cost, LocationID, CeremonyType, DecorationTheme, CateringOption, ClientID) " .
                    "VALUES (:Title, :Description, :StartDate, :EndDate, :Cost, :LocationID, :CeremonyType, :DecorationTheme, :CateringOption, :ClientID)";
            
            $statement = $this->connect->prepare($sql);
            $params = array(
                "Title"           => $p->getTitle(),
                "Description"     => $p->getDescription(),            
                "StartDate"       => $p->getStartDate(),
                "EndDate"         => $p->getEndDate(),
                "Cost"            => $p->getCost(),
                "LocationID"      => $p->getLocationID(),
                "CeremonyType"    => $p->getCeremonyType(),
                "DecorationTheme" => $p->getDecorationTheme(),
                "CateringOption"  => $p->getCateringOption(),
                "ClientID"        => $p->getClientID()
            );
            
            $status = $statement->execute($params);
            
            if (!$status) {
                die("Could not insert event");
            }
            
            $id = $this->connect->lastInsertId();
            
            return $id;
        }
    }

    public function update($p, $imagePath = null) {
        try {
            // Check if ImagePath column exists
            $checkColumn = "SHOW COLUMNS FROM `events` LIKE 'ImagePath'";
            $result = $this->connect->query($checkColumn);
            
            if ($result->rowCount() == 0) {
                // Column doesn't exist, use old query without ImagePath
                $sql = "UPDATE events SET " .
                        "Title = :Title, " . 
                        "Description = :Description, " .                
                        "StartDate = :StartDate, " .
                        "EndDate = :EndDate, " .
                        "Cost = :Cost, " .
                        "LocationID = :LocationID, " .
                        "CeremonyType = :CeremonyType, " .
                        "DecorationTheme = :DecorationTheme, " .
                        "CateringOption = :CateringOption, " .
                        "ClientID = :ClientID " .
                        "WHERE eventID = :id";
                
                $statement = $this->connect->prepare($sql);
                $params = array(
                    "Title"          => $p->getTitle(),
                    "Description"    => $p->getDescription(),            
                    "StartDate"      => $p->getStartDate(),
                    "EndDate"        => $p->getEndDate(),
                    "Cost"           => $p->getCost(),
                    "LocationID"     => $p->getLocationID(),
                    "CeremonyType"   => $p->getCeremonyType(),
                    "DecorationTheme" => $p->getDecorationTheme(),
                    "CateringOption" => $p->getCateringOption(),
                    "ClientID"       => $p->getClientID(),
                    "id"             => $p->getId()
                );
            } else {
                // Column exists, use new query with ImagePath
                $sql = "UPDATE events SET " .
                        "Title = :Title, " . 
                        "Description = :Description, " .                
                        "StartDate = :StartDate, " .
                        "EndDate = :EndDate, " .
                        "Cost = :Cost, " .
                        "LocationID = :LocationID, " .
                        "CeremonyType = :CeremonyType, " .
                        "DecorationTheme = :DecorationTheme, " .
                        "CateringOption = :CateringOption, " .
                        "ClientID = :ClientID";
                
                // Add image path to update if provided
                if ($imagePath !== null) {
                    $sql .= ", ImagePath = :ImagePath";
                }
                
                $sql .= " WHERE eventID = :id";
                
                $statement = $this->connect->prepare($sql);
                $params = array(
                    "Title"          => $p->getTitle(),
                    "Description"    => $p->getDescription(),            
                    "StartDate"      => $p->getStartDate(),
                    "EndDate"        => $p->getEndDate(),
                    "Cost"           => $p->getCost(),
                    "LocationID"     => $p->getLocationID(),
                    "CeremonyType"   => $p->getCeremonyType(),
                    "DecorationTheme" => $p->getDecorationTheme(),
                    "CateringOption" => $p->getCateringOption(),
                    "ClientID"       => $p->getClientID(),
                    "id"             => $p->getId()
                );
                
                // Add image path to params if provided
                if ($imagePath !== null) {
                    $params["ImagePath"] = $imagePath;
                }
            }
            
            $status = $statement->execute($params);
            
            if (!$status) {
                die("Could not update event details");
            }
        } catch (PDOException $e) {
            // If there's an error, try without the ImagePath column
            $sql = "UPDATE events SET " .
                    "Title = :Title, " . 
                    "Description = :Description, " .                
                    "StartDate = :StartDate, " .
                    "EndDate = :EndDate, " .
                    "Cost = :Cost, " .
                    "LocationID = :LocationID, " .
                    "CeremonyType = :CeremonyType, " .
                    "DecorationTheme = :DecorationTheme, " .
                    "CateringOption = :CateringOption, " .
                    "ClientID = :ClientID " .
                    "WHERE eventID = :id";
            
            $statement = $this->connect->prepare($sql);
            $params = array(
                "Title"          => $p->getTitle(),
                "Description"    => $p->getDescription(),            
                "StartDate"      => $p->getStartDate(),
                "EndDate"        => $p->getEndDate(),
                "Cost"           => $p->getCost(),
                "LocationID"     => $p->getLocationID(),
                "CeremonyType"   => $p->getCeremonyType(),
                "DecorationTheme" => $p->getDecorationTheme(),
                "CateringOption" => $p->getCateringOption(),
                "ClientID"       => $p->getClientID(),
                "id"             => $p->getId()
            );
            
            $status = $statement->execute($params);
            
            if (!$status) {
                die("Could not update event details");
            }
        }
    }
    
    public function delete($id) {
        $sql = "DELETE FROM events WHERE eventID = :id";
        
        $statement = $this->connect->prepare($sql);
        $params = array(
            "id" => $id
        );
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not delete event");
        }
    }    

    public function getCeremonyTypes() {
        $sqlQuery = "SELECT * FROM ceremony_types ORDER BY CeremonyName";
        $statement = $this->connect->prepare($sqlQuery);
        $status = $statement->execute();
        
        if (!$status) {
            die("Could not retrieve ceremony types");
        }
        
        return $statement;
    }
    
    public function getDecorationThemes() {
        $sqlQuery = "SELECT * FROM decoration_themes ORDER BY ThemeName";
        $statement = $this->connect->prepare($sqlQuery);
        $status = $statement->execute();
        
        if (!$status) {
            die("Could not retrieve decoration themes");
        }
        
        return $statement;
    }
    
    public function getCateringOptions() {
        $sqlQuery = "SELECT * FROM catering_options ORDER BY OptionName";
        $statement = $this->connect->prepare($sqlQuery);
        $status = $statement->execute();
        
        if (!$status) {
            die("Could not retrieve catering options");
        }
        
        return $statement;
    }
}