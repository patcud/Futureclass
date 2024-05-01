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

    if ($_SESSION['userlevel'] != "admin") {
        header('location: userlevel.php');
    }

?>

 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--ส่วนที่ดึง jquery มาใช้งาน-->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- **** -->
    <title>My COURSE</title>
    
</head>

<body>

    <!-- ส่วนข้อมูลภายในเว็บ -->
    <div class="container-fluid h-100 bg-light" style="padding-top: 6rem;">
        <?php include('components/header_admin.php') ?>
        
        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <?php include('components/alert.php') ?>

        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <?php
            $id = $_GET['id'];
            $sql = "SELECT * 
                    FROM students 
                    LEFT JOIN user ON students.studentsID = user.id
                    WHERE studentsID='$id'";
            $student = mysqli_fetch_assoc(mysqli_query($con,$sql));
            $name = $student['fname'];
            $surname = $student['lname'];
            $email = $student['userEmail'];
        ?>
        <div class="container text-center" style="margin-top: 5rem !important;">
            <div class="mb-5">
                <h2>Student ID: <?= $id ?></h2>
            </div>
            <form action="./controller/admin/editStudentInfo.php?id=<?= $id ?>" method="POST">
                <div class="container-fluid w-50">        
                    <div class="mb-3">
                        <label for="name" class="form-label fs-4">Name</label>
                        <div class="decinput"><input type="text" class="form-control" placeholder="<?= $name ?>" name="name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="surname" class="form-label fs-4">Surname</label>
                        <div class="decinput"><input type="text" class="form-control" placeholder="<?= $surname ?>" name="surname"></div>    
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fs-4">Email</label>
                        <div class="decinput"><input type="email" class="form-control" placeholder="<?= $email ?>" name="email"></div>    
                    </div>
                        <!--เมื่อกด submit แล้วข้อมูลจะเชื่อมไปยัง database แล้วจะแสดงผลคือย้อนกลับไปที่หน้า Profile-->
                    <div class="d-grid gap-2 col-6 mx-auto" style="margin-bottom: 20px;">
                        <button type="submit" class="btn btn-danger">Save</button>
                        <a class="btn btn-danger" href="home_admin_student.php"> Cancel</a>
                    </div>
                </div>
            </form>
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