<?php
    function validate_id($id_str) {
        if (ctype_digit($id_str)) {
            return true;
        } else {
            return false;
        }
    }
?>