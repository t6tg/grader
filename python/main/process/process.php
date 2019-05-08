<?php
//compile
require_once("../../../Database/Database.php");
session_start();
$week = $_POST['week'];
$sql = "SELECT * FROM problem where week='" . $week . "'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_array($query);
$sql_score = "SELECT * FROM student where username=$student";
$query_score = mysqli_query($conn, $sql);
$result_score = mysqli_fetch_array($query);
$score_old = $result_score["$week"];
$score =  $result['score'];
$student = $_SESSION['username'];
$sfile = "$student" . _ . "$week";
$path = "91d9a2124569c9135979c12e3ec464f5/student/$week/";
$type = strrchr($_FILES['file']['name'], ".");
$namefile = $path . $sfile . $type;
$sql_server = "select * from server where id=1";
$result_server = mysqli_query($conn,$sql_server);
$row_server = mysqli_fetch_array($result_server);
if($row_server['server_st'] == 1){
if ($result['week'] != $_POST['week']) {
    echo "<script>alert('ไม่มีข้อนี้อยู่ในระบบ')</script>";
    header("Refresh:0;url=../index.php");
} else {
    if ($result['status'] == 0) {
        echo "<script>alert('ข้อนี้ถูกปิดอยู่')</script>";
        header("Refresh:0;url=../index.php");
    } else {
        if ($result['type'] == 0) {
            if ($type == ".jpg" || $type == ".jpeg") {
                $file_sql = "insert into manual(week,username,file) values('".$week."' , '".$student."' , '".$namefile."')";
                if($conn->query($file_sql) === TRUE){
                move_uploaded_file($_FILES['file']['tmp_name'], $namefile);
                header("Refresh:0;url=../loading.php");
                $add_sql  =   "update student set $week='upload' where username=$student";
                mysqli_query($conn, $add_sql);
                }else{
                    echo "<script>alert('ผิดพลาด')</script>";
                    header("Refresh:0;url=../index.php");
                }
            } else {
                echo "<script>alert('นามสกุลของไฟล์ไม่ถูกต้อง')</script>";
                header("Refresh:0;url=../index.php");
            }
        } else if($result['type'] == 1) {
            if ($type == ".py") {
                move_uploaded_file($_FILES['file']['tmp_name'], $namefile);
                $output = exec("ctc.bat 91d9a2124569c9135979c12e3ec464f5/student/$week/$sfile 91d9a2124569c9135979c12e3ec464f5/input/$week/$week 91d9a2124569c9135979c12e3ec464f5/student/$week/$sfile");
                //end-compile
                $data1 = file_get_contents("91d9a2124569c9135979c12e3ec464f5/student/$week/$sfile.sol");
                $data2 = file_get_contents("91d9a2124569c9135979c12e3ec464f5/ans/$week.sol");
                if ($data1 != $data2) {
                    $add_sql  =   "update student set $week=0 where username=$student";
                    mysqli_query($conn, $add_sql);
                }
                if ($data1 == $data2) {
                    $add_sql  =   "update student set $week=$score  where username=$student";
                    mysqli_query($conn, $add_sql);
                }
                unlink("91d9a2124569c9135979c12e3ec464f5/student/$week/$sfile.sol");
                header("Refresh:0;url=../loading.php");
            } else {
                echo "<script>alert('นามสกุลของไฟล์ไม่ถูกต้อง')</script>";
                header("Refresh:0;url=../index.php");
            }
        }else{
            if ($type == ".py") {
                move_uploaded_file($_FILES['file']['tmp_name'], $namefile);
                //header("Refresh:0;url=../loading.php");
                $add_sql  =   "update student set $week='upload' where username=$student";
                mysqli_query($conn, $add_sql);
            } else {
                echo "<script>alert('นามสกุลของไฟล์ไม่ถูกต้อง')</script>";
                header("Refresh:0;url=../index.php");
            }
        }
            mysqli_close($conn);

    }
}
}else{
    echo "<script>alert('Grader Closed')</script>";
                header("Refresh:0;url=../index.php");
}
