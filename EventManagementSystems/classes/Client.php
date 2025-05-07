<?php
class Client {
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $address;
    private $specialInstructions;
    
    public function __construct($id, $firstName, $lastName, $email, $phone, $address, $specialInstructions = null) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->specialInstructions = $specialInstructions;
    }
    
    public function getId() { return $this->id; }
    public function getFirstName() { return $this->firstName; }
    public function getLastName() { return $this->lastName; }
    public function getFullName() { return $this->firstName . ' ' . $this->lastName; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }
    public function getAddress() { return $this->address; }
    public function getSpecialInstructions() { return $this->specialInstructions; }
}
?>