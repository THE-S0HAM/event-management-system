<?php
require_once 'classes/Connection.php';

// Connect to database
$connection = Connection::getInstance();

// Add ImagePath column to events table if it doesn't exist
try {
    $checkColumn = "SHOW COLUMNS FROM `events` LIKE 'ImagePath'";
    $result = $connection->query($checkColumn);
    
    if ($result->rowCount() == 0) {
        $alterEvents = "ALTER TABLE `events` ADD COLUMN `ImagePath` varchar(255) DEFAULT NULL";
        $connection->exec($alterEvents);
        echo "Added ImagePath column to events table.<br>";
    } else {
        echo "ImagePath column already exists in events table.<br>";
    }
} catch (PDOException $e) {
    echo "Error updating events table: " . $e->getMessage() . "<br>";
}

// Add ImagePath column to locations table if it doesn't exist
try {
    $checkColumn = "SHOW COLUMNS FROM `locations` LIKE 'ImagePath'";
    $result = $connection->query($checkColumn);
    
    if ($result->rowCount() == 0) {
        $alterLocations = "ALTER TABLE `locations` ADD COLUMN `ImagePath` varchar(255) DEFAULT NULL";
        $connection->exec($alterLocations);
        echo "Added ImagePath column to locations table.<br>";
    } else {
        echo "ImagePath column already exists in locations table.<br>";
    }
} catch (PDOException $e) {
    echo "Error updating locations table: " . $e->getMessage() . "<br>";
}

// Update existing events with default images
try {
    $updateEvents = "UPDATE `events` SET `ImagePath` = 
                    CASE 
                        WHEN `CeremonyType` = 'Wedding' THEN 'images/tradinational-wed.jpg'
                        WHEN `CeremonyType` = 'Reception' THEN 'images/reception.jpg'
                        WHEN `CeremonyType` IN ('Engagement', 'Sakharpuda') THEN 'images/sarkharpuda.jpg'
                        WHEN `CeremonyType` IN ('Sangeet', 'Mehendi', 'Haldi') THEN 'images/sangeet.jpg'
                        ELSE 'images/tradinational-wed.jpg'
                    END
                    WHERE `ImagePath` IS NULL";
    $connection->exec($updateEvents);
    echo "Updated existing events with default images.<br>";
} catch (PDOException $e) {
    echo "Error updating event images: " . $e->getMessage() . "<br>";
}

// Update existing locations with default images
try {
    $updateLocations = "UPDATE `locations` SET `ImagePath` = 
                       CASE 
                           WHEN `Name` LIKE '%Mangal%' THEN 'images/tradinational-wed.jpg'
                           WHEN `Name` LIKE '%Vivah%' OR `Name` LIKE '%Hall%' THEN 'images/sarkharpuda.jpg'
                           WHEN `Name` LIKE '%Lawn%' THEN 'images/reception.jpg'
                           ELSE 'images/tradinational-wed.jpg'
                       END
                       WHERE `ImagePath` IS NULL";
    $connection->exec($updateLocations);
    echo "Updated existing locations with default images.<br>";
} catch (PDOException $e) {
    echo "Error updating location images: " . $e->getMessage() . "<br>";
}

echo "<br>Database update completed. <a href='index.php'>Return to homepage</a>";
?>