<?php
    session_start();
    if (isset($_SESSION['member'])) {
        $member = @unserialize($_SESSION['member']);
        if ($member instanceof Member) {
            if ($member->role != "Manager") {
                die();
            }
        }
        require_once "../base/validation.php";
        if(isset($_GET['id'])&&isset($_GET['laborerid'])&&validate_id($_GET['id'])&&validate_id($_GET['laborerid'])){
            require_once "../../DAO/ContractDAO.php";
            deleteContract($_GET['id']);
            $laborer_id = $_GET['laborerid'];
            header("Location: list_contract.php?id=$laborer_id");
            exit();
        }
    } else {
        header("Location: ../login.php");
        exit();
    }
?>