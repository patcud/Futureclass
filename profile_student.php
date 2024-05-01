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

<body class="bg-light">

    <!-- ส่วนข้อมูลภายในเว็บ -->
    <div class="container-fluid h-100 bg-light">
        <?php include('components/header_student.php') ?>

        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <div class="row w-100" style="margin-top: 5rem !important;">
            <!-- การ fixed แถบเมนูด้านบนทับพื้นที่แสดงผล จึงเพิ่มแถวเพื่อลดพื้นที่ที่ถูกทับ-->
            <div class="card mb-12 mx-3" style="margin-top: 50px; border-style: hidden; background-color: transparent">
                <div class="row g-0 w-100">
                    <div class="col-12 col-md-1"></div>
                    <div class="col-12 col-md-3" style="display: flex; align-items: center; justify-content: center;">
                        <!-- รูปโปรไฟล์ -->
                        <div class="profile-pic-div">
                            <img id="photo">
                            <input type="file" id="file" accept="image/*" >
                            <!-- <label for="file" id="uploadBtn">Choose Photo</label> -->
                        </div>
                        <script src="PicProJ.js"></script>                    
                    </div>
                    <div class="col-1 col-md-1"></div>
                    <div class="col-10 col-md-6 justify-content-center" style="margin-top: 20px;">
                        <div class="container-fluid w-100">
                            <div class="card-body" >
                                <h5 class="card-title">User Profile</h5>
                                <dl class="row">
                                    <!--ดึงข้อมูลชื่อมาใส่แทน a-->
                                    <dt class="col-sm-3 fw-normal">Name</dt>
                                    <dd class="col-sm-9"><span><?php echo $_SESSION['user']; ?></span>

                                        <!--ดึงข้อมูลID นิสิตมาใส่แทน a-->
                                    <dt class="col-sm-3 fw-normal">ID CU</dt>
                                    <dd class="col-sm-9"><span><?php echo $_SESSION['userid']; ?></span>

                                        <!--ดึงข้อมูลกรุ๊ปมาใส่แทน a-->
                                    <!--<dt class="col-sm-3 fw-normal">Group</dt>
                                    <dd class="col-sm-9"><span>a</span>-->

                                        <!--ดึงข้อมูลEmail chula มาใส่แทน a-->
                                    <dt class="col-sm-3 fw-normal">User (Email CU)</dt>
                                    <dd class="col-sm-9"><span><?php echo $_SESSION['email']; ?></span>

                                        <!--ดึงข้อมูลpassword มาใส่แทน a-->
                                    <!--<dt class="col-sm-3 fw-normal"> Password </dt>
                                    <dd class="col-sm-9"><span></span>-->
                                </dl>
                                <div class="d-grid gap-2 col-2 mx-auto mx-md-0" style="margin-bottom: 20px;">
                                    <!--เมื่อกดปุ่ม edit จะลิ้งค์ไปที่หน้า edit ซึ่งเป็นการกรอบข้อมูลเข้าใหม่ใหม่ทั้งหมด-->
                                    <a href="profile_edit_student.php" class="btn btn-danger btn-sm" role="button">edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 col-md-1"></div>
                </div>
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