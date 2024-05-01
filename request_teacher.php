<?php

    include('dbconnect.php');
    //session_start();

    if (!isset($_SESSION['email'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
    }

    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['email']);
        header('location: login.php');
    }
    
    if ($_SESSION['userlevel'] != "teacher") {
        header('location: userlevel.php');
    }

    if (!isset($_SESSION['userid'])){
        $_SESSION['msg'] = "User ID Error";
        header('location: error.php');
    }
    
    if (!isset($_GET['id'])){
        $_SESSION['msg'] = "Course ID Error";
        header('location: error.php');
    }

    $coursesID = $_GET['id'];
    $sql = "SELECT *
            FROM courses
            LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
            WHERE coursesID=$coursesID";
    $result = mysqli_fetch_array(mysqli_query($con,$sql));
    $coursename = $result['nameEN'];
    $id = $_SESSION['userid'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--ส่วนที่ดึง bootstrap มาใช้งาน-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <!-- ส่วนที่นำ logo มาใส่เมื่อเปิด tabใหม่-->
    <link rel="shortcut icon" href="./images/full-logo-b.png" type="image/x-icon">
    <!--เว็บที่ดึง icon มาใช้งาน-->
    <link rel="stylesheet" href="https://unpkg.com/@coreui/icons@2.0.0-beta.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- **** -->
    <!-- แต่ง css ตามไฟล์ style.css -->
    <link rel="stylesheet" href="style.css">
    
    <!-- **** -->
    <title>Subject</title>
  
</head>

<body>

    <!-- ส่วนข้อมูลภายในเว็บ -->
    <div class="container-fluid h-100 bg-light">
        <?php include('components/header_teacher.php') ?>
                                            
        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <div class="row flex-nowrap w-100" style="margin-top: 5rem !important;">
            <?php include('components/sidebar_teacher.php') ?>

            <!-- ส่วนข้อมูลด้านข้างเมนูย่อย -->
            <div class="col-11 col-xs-11 col-md-10 bg-light" style="margin-top: 20px;">
                <h3> Request </h3>
                <p style="color:#6e6e6e">[ Request เป็นหน้าแสดงข้อเรียกร้องที่นิสิตสร้างขึ้น อาจารย์สามารถเลือกดูรายละเอียดและตอบข้อเรียกร้องแต่ละข้อที่นิสิตเรียกร้องได้ ]</p>
                <hr>
                <!--create card auto-->
                <!--row-cols-1 : แถวนึงมี1อัน , g-3 : ช่องห่างระหว่างอัน-->
                <div id='requestment_card' class="row row-cols-1 g-1 bg-light"></div>
                <?php 
                    $sql2 = "SELECT * FROM requestment WHERE coursesID=$coursesID AND status=0 ORDER BY timestamp DESC";
                    $r = mysqli_query($con, $sql2);
                    while($a = mysqli_fetch_array($r)) {
                        echo '
                            <div class="col">
                            <div class="card" style="border-radius: 20px;margin-top:5px;margin-bottom:10px;">
                            <div class="card-body">
                            <div class="container">
                            <div class="row">
                            <div class="col-10 col-sm-11">
                            <div class="container">
                            <h4 class="card-title text-danger">
                            <a href="request_view.php?id='.$a['requestmentID'].'" style="text-decoration:none;color:red;">
                            '.$a['topic'].'
                            </a>
                            </h4>
                            <p class="text-muted">
                            '.$a['timestamp'].'
                            </p>
                            </div>
                            </div>
                            <div class="col-2 col-sm-1">
                            <a href="request_view.php?id='.$a['requestmentID'].'"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" style="position:absolute;top:50%;transform:translateY(-50%)" fill="black" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg></a>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            ';
                        }
                ?>
                <!-- เพิ่ม 1 -->
                
                    
            <!-- จบ : ส่วนข้อมูลด้านข้างเมนูย่อย -->
            </div>
        
        <!-- จบ : ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        </div>
    <!-- จบ : ส่วนข้อมูลภายในเว็บ -->
    </div>

    <!-- **** -->
    <!-- รันแจ้งเตือน -->
    <?php include('components/noti_teacher.php') ?>
    <!-- จบ : รันแจ้งเตือน -->

</body>

</html>