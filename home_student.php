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
    
    $id = $_SESSION['userid'];
    $sql = "SELECT coursesID FROM student_course WHERE studentsID = $id AND status = 1 ORDER BY coursesID ASC";
    $r = mysqli_query($con, $sql);
    
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
        <?php include('components/header_student.php') ?>

        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <div class="row justify-content-md-center w-100" style="margin-top: 5rem !important;">
            <?php if (isset($_SESSION['msg'])) { ?>
                <h3 style="color:red"> <?php echo $_SESSION['msg']; unset($_SESSION['msg']);} ?> </h3>
                
                <!--<div class="alert alert-warning alert-dismissible fade show" role="alert">-->


            <!-- ถ้าใหญ่กว่าmdเรียงแถวละ 4 การ์ด ถ้าเล็กกว่าsmเรียงแถวละ 2 การ์ด ถ้าเล็กกว่าสุดเรียงแถวละการ์ด -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3 bg-light">            
                <?php 
                    while($a = mysqli_fetch_array($r)) {
                        $sid = $a['coursesID'];
                        $sql = "SELECT *
                                FROM courses
                                LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
                                WHERE coursesID = $sid"; 
                        $rsub = mysqli_fetch_array(mysqli_query($con, $sql));
                        if ($rsub['semesterID'] == $semID) {          
                            echo '
                                <a href="subject_student.php?id='.$sid.' "style="text-decoration:none; color: #000;">
                                    <div class="col h-100">
                                        <div class="card h-100" style="border-radius: 20px;">
                                        <img id="myImg" src="read_subject_pic.php?id='.$sid.'" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h5 class="card-title text-danger">
                                                '.$rsub['subjectsID'].'
                                                </h5>
                                                <p class="card-text">
                                                '.$rsub['nameEN'].'
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                ';
                        }
                    }
                ?>
            </div>

         
            
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