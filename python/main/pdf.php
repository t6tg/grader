<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
require_once("../../Database/Database.php");
if ($_SESSION['username'] == "") {
    echo "<script>alert('Please Login !!')</script>";
    header("Refresh:0 , url=../logout.php");
    exit();
}
if ($_SESSION['status'] != "user") {
    echo "<script>alert('This page for user only!')</script>";
    header("Refresh:0 , url=../logout.php");
    exit();
}
$pdf = $_GET['file'];
$pdf_path = "437175ba4191210ee004e1d937494d09/$pdf";
$sql_file = "select * from file where filename='".$pdf."'";
$result_file = mysqli_query($conn, $sql_file);
$row_file = mysqli_fetch_array($result_file);
if($row_file['status'] == 0){
    header("Location:index.php");
}
if(empty($_GET['file'])){
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF</title>
</head>

<body>
    <object data="<?php echo $pdf_path?>" type="application/pdf" style="min-height:100vh;width:100%"></object>
</body>

</html>