<?php
include("includes/check_session.php");

if(isset($_GET['file'])){
    $file = $_GET['file'];
    if(strpos($file, "..") === false){
        $ds = DIRECTORY_SEPARATOR;
        $storeFolder = $_SERVER["DOCUMENT_ROOT"].$ds."sample";
        $filePath=$storeFolder.$ds.$file;
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);

    }else{
        header("Location: index.php");
        exit();
    }

}else{
    header("Location: index.php");
    exit();
}
?>
