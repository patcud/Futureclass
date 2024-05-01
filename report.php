<?php

    include('dbconnect.php');

    if (!isset($_SESSION['email'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
    }

    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['email']);
        header('location: login.php');
    }
    
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
    <!--แต่ง css ตามไฟล์ style.css-->
    <link rel="stylesheet" href="style.css">
    <!--เว็บที่ดึง icon มาใช้งาน-->
    <link rel="stylesheet" href="https://unpkg.com/@coreui/icons@2.0.0-beta.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- **** -->
    <title>My COURSE</title>
    
</head>

<body>

    <!-- ส่วนข้อมูลภายในเว็บ -->
    <div class="container-fluid h-100 bg-light">
        <?php include('components/header_admin.php') ?>

        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <div class="row justify-content-md-center" style="margin-top: 4rem !important;">

            <div class="row">
                <!--สร้างtab ย่อยด้านบนเพื่อแสดงreportย่อย-->
                <div class="list-of-re" style="margin-top: 35px;">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="report.php">Request Report</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="report_assessment.php">Assessment Report</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="report_teacher_assessment.php">Teacher Assessment Report</a>
                        </li>
                    </ul>
                </div>
                <div class="row my-2">
                    <h3 style="margin-bottom:0px"> ข้อเรียกร้องที่ถูกรายงานว่าไม่เหมาะสม </h3>
                    <p style="color:#6e6e6e;margin-top:5px;margin-bottom:3px;">[ แสดง request ที่มีผู้ร้องเรียนว่าไม่เหมาะสมโดย Admin สามารถเข้าไปอ่าน request อย่างละเอียดและทำการลบหรือเก็บ request เหล่านั้นได้ ]</p>
                </div>
                <hr style="margin-bottom:0px; padding-bottom:0px;">
            </div>

            <!--create card auto-->
            <!--row-cols-1 : แถวนึงมี1อัน , g-3 : ช่องห่างระหว่างอัน-->
            <div id='requestment_report' class="row row-cols-1 g-3 bg-light"></div>
                <?php
                    $sql2 = "SELECT * FROM request_report WHERE report=1 ORDER BY timestamp DESC";
                    $r = mysqli_query($con, $sql2); 
                    while($a = mysqli_fetch_array($r)) {
                        $rp = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM requestment WHERE requestmentID=$a[rID]"));
                        echo '
                            <div class="col">
                            <div class="card" style="border-radius: 20px;margin-top:3px;margin-bottom:10px;">
                            <div class="card-body">
                            <div class="container-fluid">
                            <div class="row">
                            <div class="col-10 col-sm-11">
                            <div class="contaner">
                            <h4 class="card-title text-danger">
                            '.$rp['topic'].'
                            </h4>
                            <p class="text-muted">
                            '.$rp['timestamp'].'
                            </p>
                            </div>
                            </div>
                            
                            <div class="col-2 col-sm-1">
                            <a href="request_admin.php?id='.$rp['coursesID'].'&rid='.$rp['requestmentID'].'"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" style="position:absolute;top:50%;transform:translateY(-50%)"  fill="black" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg></a>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            ';
                    }
                ?>

        <!-- จบ : ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        </div>
    <!-- จบ : ส่วนข้อมูลภายในเว็บ -->
    </div>

    <!-- **** -->
    <!-- รันแจ้งเตือน -->
    <?php include('components/noti_admin.php') ?>
    <!-- จบ : รันแจ้งเตือน -->
    
</body>

</html>