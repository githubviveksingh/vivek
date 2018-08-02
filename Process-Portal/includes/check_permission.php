<?php

$permission = checkPagePermission();
if($permission === false){
    $_SESSION['error'] = "Permission Denied.";
    header("Location: index.php");
    exit();
}
?>
