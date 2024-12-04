<?php

require_once 'Member.php'; // Assuming Member.php contains the base class Member

class Manager extends Member {
    public $position; // Position of the Manager

    // Constructor
    public function __construct(
        $id, 
        $username, 
        $password, 
        $name, 
        $dateOfBirth, 
        $gender, 
        $phone, 
        $address, 
        $citizenIdentification, 
        $role,
        $note,  
        $position
    ) {
        // Call the parent constructor to initialize Member properties
        parent::__construct($id, $username, $password, $name, $dateOfBirth, $gender, $phone, $address, $citizenIdentification, $role, $note);
        $this->position = $position;
    }

    // Getter for Position
    public function getPosition() {
        return $this->position;
    }

    // Setter for Position
    public function setPosition($position) {
        $this->position = $position;
    }
}
?>
