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

    if ($_SESSION['userlevel'] != "teacher") {
        header('location: userlevel.php');
    }

    if (!isset($_SESSION['userid'])){
        $_SESSION['msg'] = "User ID Error";
        header('location: error.php');
    }
    
    $id = $_SESSION['userid'];
    $sql = "SELECT coursesID FROM teacher_course WHERE teachersID = $id ORDER BY coursesID ASC";
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
        <?php include('components/header_teacher.php') ?>

        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <div class="row w-100" style="margin-top: 5rem !important;">
            <div class="card mb-12 mx-3" style="margin-top: 50px; border-style: hidden; background-color: transparent">
                <div class="row g-0 w-100">
                    <div class="col-12 col-md-1"></div>
                    <div class="col-12 col-md-3" style="display: flex; align-items: center; justify-content: center;">
                        <!-- รูปโปรไฟล์ -->
                        <form action="profile_submit_teacher.php" method="POST" enctype="multipart/form-data">
                        <div class="profile-pic-div">
                            <img id="photo">
                            <input type="hidden" name="MAX_FILE_SIZE" value="25000000">
                            <input type="file" id="file" name="file" accept="image/*" >
                            <label for="file" id="uploadBtn">Choose Photo</label>
                        </div>
                        <script src="PicProJ.js"></script>  
                                         
                    </div>
                    <div class="col-1 col-md-1"></div>
                    <div class="col-10 col-md-6" style="margin-top: 20px;">
                        <div class="container-fluid w-100">        
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <div class="decinput"><input type="text" class="form-control" placeholder=<?php echo $_SESSION['fname']; ?> name="name"></div>
                                <div class="mb-3">
                                    <label for="surname" class="form-label">Surname</label>
                                    <div class="decinput"><input type="text" class="form-control" placeholder=<?php echo $_SESSION['lname']; ?> name="surname"></div>    
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="decinput"><input type="password" class="form-control" name="password"></div>    
                                </div>
                                <div class="mb-3">
                                    <label for="conpassword" class="form-label">Confirm Password</label>
                                    <div class="decinput"><input type="password" class="form-control" name="conpassword"></div>    
                                </div>
                                    <?php if(isset($_SESSION['error'])) {
                                        echo $_SESSION['error'];
                                        unset($_SESSION['error']);
                                    } ?>
                                    <!--เมื่อกด submit แล้วข้อมูลจะเชื่อมไปยัง database แล้วจะแสดงผลคือย้อนกลับไปที่หน้า Profile-->
                                <div class="d-grid gap-2 col-6 mx-auto" style="margin-bottom: 20px;">
                                    <button type="submit" class="btn btn-danger">Save</button>
                                </div>
                            </form>
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
    <?php include('components/noti_teacher.php') ?>
    <!-- จบ : รันแจ้งเตือน -->
    
</body>

</html>