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

    if ($_SESSION['userlevel'] != "student") {
        header('location: userlevel.php');
    }

    if (!isset($_SESSION['userid'])) {
        $_SESSION['msg'] = "User ID Error";
        header('location: error.php');
    }

    if (!isset( $_GET['id'])) {
        $_SESSION['msg'] = "Courses ID Error";
        header('location: error.php');;
    }
    
    $id = $_SESSION['userid'];
    $coursesID = $_GET['id'];
    $sql = "SELECT *
            FROM courses 
            LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
            WHERE coursesID = $coursesID";
    $result = mysqli_fetch_array(mysqli_query($con,$sql));

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
    <div class="container-fluid bg-light">  
        <?php include('components/header_student.php') ?>
                                                    
        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <div class="row flex-nowrap w-100" style="margin-top: 5rem !important;">
            <?php include('components/sidebar_student.php') ?>

            <!-- ส่วนข้อมูลด้านข้างเมนูย่อย -->
            <div class="col-11 col-xs-11 col-md-10" style="margin-top: 20px;">
                <?php if (time() < $_SESSION['startassess'] || time() > $_SESSION['endassess']) { ?>
                    <h3>ระบบยังไม่เปิดให้ทำการประเมิน</h3>
                    <hr>
                <?php } else if ($result['sectiontype'] == "รวม section"){ ?>
                <!-- ถ้าใหญ่กว่าmdเรียงแถวละ 4 การ์ด ถ้าเล็กกว่าsmเรียงแถวละ 2 การ์ด ถ้าเล็กกว่าสุดเรียงแถวละการ์ด -->
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3 bg-light">            
                    <?php
                        $sql = "SELECT * FROM teacher_course WHERE coursesID=$coursesID ORDER BY teachersID ASC";
                        $r = mysqli_query($con, $sql); 
                        while($a = mysqli_fetch_array($r)) {
                            $tid = $a['teachersID'];
                            $ts = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM teachers WHERE teachersID=$tid"));
                            $teachername = $ts['title']." ".$ts['fname']." ".$ts['lname'];
                            echo '
                                <a href="assessment_instructor_student.php?id='.$coursesID.'&tid='.$tid.' "style="text-decoration:none; color: #000;">
                                <div class="col h-100">
                                <div class="card h-100" style="border-radius: 20px;display:flex;flex-direction:column;justify-content:flex-start;align-items:center">
                                <img id="myImg" src="read_pic.php?id='.$tid.'" class="card-img-top w-75" alt="..." style="border-radius:50%; height: 150px; width: 150px">
                                <div class="card-body">
                                <h5 class="card-title text-secondary">
                                '.$teachername.'
                                </h5>
                                </div>
                                </div>
                                </div>
                                </a>
                                ';
                        }
                    ?>
                </div>
                <?php } else if ($result['sectiontype'] == "แยก section"){ ?>
                    <h3>เลือกประเมินอาจารย์ประจำรายวิชา</h3>
                <hr>
                <!-- ถ้าใหญ่กว่าmdเรียงแถวละ 4 การ์ด ถ้าเล็กกว่าsmเรียงแถวละ 2 การ์ด ถ้าเล็กกว่าสุดเรียงแถวละการ์ด -->
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3 bg-light">            
                    <?php 
                        $sql = "SELECT section FROM student_course WHERE coursesID=$coursesID AND studentsID=$id";
                        $sec = mysqli_fetch_array(mysqli_query($con, $sql));
                        $sql2 = "SELECT * FROM teacher_course WHERE coursesID=$coursesID AND section = $sec[0] ORDER BY teachersID ASC";
                        $r = mysqli_query($con, $sql2);
                        while($a = mysqli_fetch_array($r)) {
                            $tid = $a['teachersID'];
                            $ts = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM teachers WHERE teachersID=$tid"));
                            $teachername = $ts['title']." ".$ts['fname']." ".$ts['lname'];
                            echo '
                                <a href="assessment_instructor_student.php?id='.$coursesID.'&tid='.$tid.' "style="text-decoration:none; color: #000;">
                                <div class="col h-100">
                                <div class="card h-100" style="border-radius: 20px;display:flex;flex-direction:column;justify-content:flex-start;align-items:center">
                                <img id="myImg" src="read_pic.php?id='.$tid.'" class="card-img-top h-75 w-75" alt="..." style="border-radius:50%; height: 150px; width: 150px">
                                <div class="card-body">
                                <h5 class="card-title text-secondary">
                                '.$teachername.'
                                </h5>
                                </div>
                                </div>
                                </div>
                                </a>
                                ';
                        }
                    ?>
                </div>
                <?php } ?>
            <!-- จบ : ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
            </div>
        <!-- จบ : ส่วนข้อมูลภายในเว็บ -->
    </div>
           
    <!-- **** -->
    <!-- รันแจ้งเตือน -->
    <?php include('components/noti_student.php') ?>
    <!-- จบ : รันแจ้งเตือน -->
                            
</body>
</html>