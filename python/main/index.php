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
$sql_server_st = "select * from server where id=1";
$result_server = mysqli_query($conn, $sql_server_st);
$row_server = mysqli_fetch_array($result_server);
$sql = "select * from problem where status=1";
$result = mysqli_query($conn, $sql);
$sql_student = "select * from student where username='" . $_SESSION['username'] . "'";
$result_student = mysqli_query($conn, $sql_student);
$sql_file = "select * from file where status=1";
$result_file = mysqli_query($conn, $sql_file);
$sql_ban = "select * from student where username='" . $_SESSION['username'] . "'";
$result_ban = mysqli_query($conn, $sql_student);
$row_ban = mysqli_fetch_array($result_ban);
if ($row_ban['ban'] == 0 && $row_server['ban'] == 1) {
    echo "<script>alert('ban !!!')</script>";
    header("Refresh:0,url=../logout.php");
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Student</title>
    <meta charset="utf-8">
    <link rel="icon" href="../../img/cnrlogo.png">
    <link rel="stylesheet" href="style/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <ul>
        <li><a class="active" href="#">User : <?php echo $_SESSION['name']; ?></a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="score.php">Score</a></li>
        <li style="float:right"><a href="../logout.php">Logout</a></li>
    </ul>
</body>
<div class="container">
    <p><b>Problem :
            <?php
            while ($row_file = mysqli_fetch_array($result_file)) { ?>
                <?php
                $file =  $row_file['filename'];
                echo "<a name='filename' href='pdf.php?file=" . $file . "'>" . $file . "</a>";
                ?>
            <?php } ?></b>
        <br><br>
        <?php $i = 1; ?>
        <?php while ($row = mysqli_fetch_array($result)) { ?>
            <?php $week = $row['week']; ?>
            <?php $score = $row['score']; ?>
            <?php if ($row['type'] == "1") {
                $type = "( Auto )";
            } else if($row['type'] == "0"){
                $type = "( Manual )";
            }else{
                $type = "( Manual Error )";
            }?>
            <form action="process/process.php" method="post" enctype="multipart/form-data">
                <div class="card">
                    <div style="margin-left: 20px;margin-top:20px;"> <b><?php echo $week . $type; ?>
                            <?php $sql_student = "select * from student where username='" . $_SESSION['username'] . "' and $week=$week";
                            $result_student = mysqli_query($conn, $sql_student); ?>
                            <?php while ($row_student = mysqli_fetch_array($result_student)) { ?>
                            </b> <?php if ($row_student[$week] == "") {
                                        echo "";
                                    } else if ($row_student[$week] == "0") {
                                        echo "( Uploaded )  <br> <b>You score: </b>  คำตอบไม่ถูกต้อง ";
                                    } else if ($row_student[$week] == "upload") {
                                        echo "( Uploaded )  <br> <b>You score: </b>  0.0 ";
                                    } else {
                                        echo "( Uploaded )  <br> <b>You score: </b>  $row_student[$week] ";
                                    }
                                    ?><br>
                            <input type="hidden" name="week" value="<?php echo $week; ?>"><?php } ?><p></p>
                        <input type="file" name="file" required>
                        <input style="flaot:right;" type="submit" name="submit" value="submit">
                    </div>
                </div>
            </form><br>
        <?php } ?>
        <br><br>
</div>
<center>
    <p style="font-size:13px">
        ***หมายเหตุ : ( Manual ) อัพไฟล์ได้เฉพาะนามสกุล .jpg หรือ .jpeg เท่านั้น ยกเว้นเหตุจำเป็นครูจะเปิดให้ลงไฟล์ .py ได้ <br>
        ( Auto ) อัพได้เฉพาะไฟล์ .py เท่านั้น<br>
        ( Manual Error ) อาจเกิดปัญหาเรื่องการตรวจครูจจะเปิดระบบตรวจด้วย Manual แต่ แบบนี้จะส่งได้เฉพาะไฟล์ .py เท่านั้น<br>
        ( Uploaded ) แสดงว่า upload ไฟล์สำเร็จให้หากคะแนนขึ้นแสดงว่าทำข้อนั้นถูก<br>
        ข้อที่เป็น ( Manual ) เครื่องจะไม่ตรวจแต่ครูจะเข้าไปตรวจและให้คะแนนภายหลัง<br>
        อย่าส่งไฟล์ซ้ำกันหลายๆครั้ง อาจทำให้คะแนนช่องอื่นๆหายได้<br><br>
    </p>
</center>
<?php mysqli_close($conn); ?>

</html>