<?php
    class Job {
        public $id;
        public $name;
        public $company;
        public $basesalary;

        public function __construct($id, $name, $company, $basesalary)
        {
            $this->id = $id;
            $this->name = $name;
            $this->company = $company;
            $this->basesalary = $basesalary;
        }
    }
?>