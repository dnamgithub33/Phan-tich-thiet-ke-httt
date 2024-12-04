<?php
    require_once "C:/xampp/htdocs/pttk/DAO/DAO.php";
    require_once "C:/xampp/htdocs/pttk/Model/Job.php";
    function getListJob() {
        global $con;
        $jobs = [];
        $sql = "SELECT * FROM tbljob";
        $result = $con->query($sql);
        while ($row = $result->fetch_assoc()){
            $job = new Job($row['job_id'], $row['job_name'], $row['company'], $row['base_salary']);
            $jobs[] = $job;
        }
        return $jobs;
    }
    function searchJob($id){
        global $con;
        $sql = "SELECT * FROM tbljob WHERE tbljob.job_id = $id";
        $result = $con->query($sql);
        $row = $result->fetch_assoc();
        $job = new Job($row['job_id'], $row['job_name'], $row['company'], $row['base_salary']);
        return $job;
    }
?>