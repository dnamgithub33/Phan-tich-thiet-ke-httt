<?php
    session_start();

    if (isset($_SESSION['member'])) {
        $member = @unserialize($_SESSION['member']);
        if ($member instanceof Member) {
            if ($member->role != "Manager") {
                die();
            }
        }
    }
    require_once "../base/validation.php";
    if(!isset($_GET['id'])||!validate_id($_GET['id'])){
        header("Location: laborerlist.php");
        exit();
    }

    require_once "C:/xampp/htdocs/pttk/DAO/MemberDAO.php";
    deleteLaborer($_GET['id']);
    header("Location: laborerlist.php");
    exit();
?>