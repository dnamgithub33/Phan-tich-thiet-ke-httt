<?php

require_once 'Member.php';
class Laborer extends Member {
    public $team; // string
    public $joinDate; // Date

    // Constructor
    public function __construct($id, $username, $password, $name, $dateOfBirth, $gender, $phone, $address, $citizenIdentification, $role, $note, $team, $joinDate) {
        parent::__construct($id, $username, $password, $name, $dateOfBirth, $gender, $phone, $address, $citizenIdentification, $role, $note);
        $this->team = $team;
        $this->joinDate = $joinDate;
    }
}
?>
