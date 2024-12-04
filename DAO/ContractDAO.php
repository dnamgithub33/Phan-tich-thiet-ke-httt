<?php
    require_once "C:/xampp/htdocs/pttk/DAO/DAO.php";
    require_once "C:/xampp/htdocs/pttk/DAO/JobDAO.php";
    require_once 'C:/xampp/htdocs/pttk/Model/Laborer.php';
    require_once 'C:/xampp/htdocs/pttk/Model/Manager.php';
    require_once 'C:/xampp/htdocs/pttk/Model/Contract.php';

    function getListContract($id){
        global $con;
        $contracts= [];
        require_once "C:/xampp/htdocs/pttk/DAO/MemberDAO.php";
        $laborer = searchLaborer($id,2);
        $sql = "SELECT * FROM tblcontract where tblcontract.laborer_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()){
            $jobs = [];
            $manager = searchManager($row['manager_id']);
            if($row['daysign']===null){
                $jobs[] = new Job(null, null, null, null);
            } else {
                $contract_id = $row['contract_id'];
                $sql = "SELECT tbljob.job_id, tbljob.job_name, tbljob.company, tbljob.base_salary 
                    from tbljobincontract 
                    inner join tbljob 
                    on tbljobincontract.job_id = tbljob.job_id 
                    where tbljobincontract.contract_id = $contract_id";
                $result_job = $con->query($sql);
                if ($result_job && $result_job->num_rows > 0){
                    while($row_job = $result_job->fetch_assoc()){
                        $jobs[] = new Job($row_job['job_id'], $row_job['job_name'], $row_job['company'], $row_job['base_salary']);
                    }

                }
            }
            $contract = new Contract($row['contract_id'], $laborer, $manager, $jobs, $row['daysign'], $row['daycreate'], $row['term']);
            $contracts[] = $contract;
        }
        return $contracts;
    }
    function searchContract($id){
        global $con;
        require_once "C:/xampp/htdocs/pttk/DAO/MemberDAO.php";
        
        $sql = "SELECT * FROM tblcontract where tblcontract.contract_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $jobs = [];
        $laborer = searchLaborer($row['laborer_id'],2);
        $manager = searchManager($row['manager_id']);
        if($row['daysign']===null){
            $jobs[] = new Job(null, null, null, null);
        } else {
            $contract_id = $row['contract_id'];
            $sql = "SELECT tbljob.job_id, tbljob.job_name, tbljob.company, tbljob.base_salary 
                from tbljobincontract 
                inner join tbljob 
                on tbljobincontract.job_id = tbljob.job_id 
                where tbljobincontract.contract_id = ?";
            $stmt_job = $con->prepare($sql);
            $stmt_job->bind_param("i", $contract_id);
            $stmt_job->execute();
            $result_job = $stmt_job->get_result();
            if ($result_job && $result_job->num_rows > 0){
                while($row_job = $result_job->fetch_assoc()){
                    $jobs[] = new Job($row_job['job_id'], $row_job['job_name'], $row_job['company'], $row_job['base_salary']);
                }
            }
        }
        $contract = new Contract($row['contract_id'], $laborer, $manager, $jobs, $row['daysign'], $row['daycreate'], $row['term']);

        return $contract;
    }
    function addContract($contract, $con){
        if($contract instanceof Contract){
            $laborer = $contract->laborer;
            $manager = $contract->manager;
            $sql = "INSERT INTO tblcontract(laborer_id, manager_id, daycreate)
                VALUE
                (?, ?, now());";
            $stmt = $con->prepare($sql);
            $stmt->bind_param(
                "ii",
                $laborer->id,
                $manager->id
            );
            if($stmt->execute()){
                header("location: ../View/manager/list_contract.php?id=$laborer->id");
                exit();
            } else{
                header("location: ../View/manager/list_contract.php?id=$laborer->id&err=err");
                exit();
            }
        }
    }
    function updateContract($contract, $con){
        if($contract instanceof Contract){
            $jobs = $contract->job;
            $sql = "UPDATE tblcontract SET daysign=now(), term = ?
                WHERE contract_id=?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param(
                "ii",
                $contract->term,
                $contract->id
            );
            if($stmt->execute()){
                foreach($jobs as $job){
                    $sql = "INSERT INTO tbljobincontract(contract_id, job_id) VALUE (?, ?)";
                    $stmt2 = $con->prepare($sql);
                    $stmt2->bind_param(
                        "ii",
                        $contract->id,
                        $job->id
                    );
                    $stmt2->execute();
                    $stmt2->close();
                }

                header("location: ../View/laborer/list_contract.php");
                exit();
            } else{
                header("location: ../View/laborer/list_contract.php?err=err");
                exit();
            }
        }
    }
    function deleteContract($id){
        global $con;
        $sql = "DELETE FROM tblcontract WHERE contract_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['manager_id'])&& isset($_POST['laborer_id'])){
            $manager_id = $_POST['manager_id'];
            $laborer_id = $_POST['laborer_id'];
            $job = [];
            require_once "C:/xampp/htdocs/pttk/DAO/MemberDAO.php";
            $laborer = searchLaborer($laborer_id,2);
            $manager = searchManager($manager_id);
            $job[] = new Job(null, null, null, null);
            $contract = new Contract(null, $laborer, $manager, $job, null, null, null);
            addContract($contract, $con);
        }

        if(isset($_POST['contract_id'])&&isset($_POST['job_id_1'])&&isset($_POST['term'])){
            $oldcontract = searchContract($_POST['contract_id']);
            $newjob = [];
            $newjob[] = searchJob($_POST['job_id_1']);
            if(isset($_POST['job_id_2'])){
                $newjob[] = searchJob($_POST['job_id_2']);
            }
            $newcontract = new Contract($oldcontract->id, $oldcontract->laborer, $oldcontract->manager, $newjob, null, $oldcontract->daycreate, $_POST['term']);
            updateContract($newcontract, $con);
        }
    }
?>