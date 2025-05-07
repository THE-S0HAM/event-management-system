<?php
class Event {
    private $title;
    private $description;    
    private $startDate;
    private $endDate;
    private $cost;
    private $locationID;
    private $ceremonyType;
    private $decorationTheme;
    private $cateringOption;
    private $clientID;
    
    public function __construct($id, $title, $description, $sDate, $eDate, $cost, $locID, $ceremonyType = null, $decorationTheme = null, $cateringOption = null, $clientID = null) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->startDate = $sDate;
        $this->endDate = $eDate;
        $this->cost = $cost;
        $this->locationID = $locID;
        $this->ceremonyType = $ceremonyType;
        $this->decorationTheme = $decorationTheme;
        $this->cateringOption = $cateringOption;
        $this->clientID = $clientID;
    }
    
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getStartDate() { return $this->startDate; }
    public function getEndDate() { return $this->endDate; }
    public function getCost() { return $this->cost; }
    public function getLocationID() { return $this->locationID; }
    public function getCeremonyType() { return $this->ceremonyType; }
    public function getDecorationTheme() { return $this->decorationTheme; }
    public function getCateringOption() { return $this->cateringOption; }
    public function getClientID() { return $this->clientID; }
}
?>