<?php

// Require các lớp Member, Laborer, Manager
require_once 'Member.php';
require_once 'Laborer.php';
require_once 'Manager.php';
require_once 'Job.php';

class Contract {
    public $id; // ID của hợp đồng
    public $laborer; // Lớp Laborer
    public $manager; // Lớp Manager
    public $job =[];
    public $term;
    public $daysign; // Ngày ký
    public $daycreate;

    // Constructor
    public function __construct($id, Laborer $laborer, Manager $manager,array $job, $daysign, $daycreate, $term) {
        $this->id = $id;
        $this->laborer = $laborer;
        $this->manager = $manager;
        $this->job = $job;
        $this->daysign = $daysign;
        $this->daycreate = $daycreate;
        $this->term = $term;
    }

    // Getter cho id
    public function getId() {
        return $this->id;
    }

    // Setter cho id
    public function setId($id) {
        $this->id = $id;
    }

    // Getter cho laborer
    public function getLaborer() {
        return $this->laborer;
    }

    // Setter cho laborer
    public function setLaborer(Laborer $laborer) {
        $this->laborer = $laborer;
    }

    // Getter cho manager
    public function getManager() {
        return $this->manager;
    }

    // Setter cho manager
    public function setManager(Manager $manager) {
        $this->manager = $manager;
    }

    // Getter cho daysign
    public function getdaysign() {
        return $this->daysign;
    }

    // Setter cho daysign
    public function setdaysign($daysign) {
        $this->daysign = $daysign;
    }
}

?>
