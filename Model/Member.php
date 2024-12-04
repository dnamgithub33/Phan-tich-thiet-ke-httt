<?php

class Member {
    public $id; // int
    public $username; // string
    public $password; // string (MD5 encrypted)
    public $name; // string
    public $dateOfBirth; // Date
    public $gender; // string
    public $phone; // string
    public $address; // string
    public $citizenIdentification; // string
    public $note; // string
    public $role; // string

    // Constructor
    public function __construct($id, $username, $password, $name, $dateOfBirth, $gender, $phone, $address, $citizenIdentification, $role, $note) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
        $this->dateOfBirth = $dateOfBirth;
        $this->gender = $gender;
        $this->phone = $phone;
        $this->address = $address;
        $this->citizenIdentification = $citizenIdentification;
        $this->role = $role;
        $this->note = $note;
    }
}
?>
