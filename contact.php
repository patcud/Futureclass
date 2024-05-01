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
                <div class="container-fluid">
                    <h5 class="text-danger">ช่องทางต่างๆ</h5>
                    <!-- form channal_edit -->
                    <form action ="contact_submit.php?id=<?php echo $coursesID; ?>" method="POST">
                        <!-- ตั้ง for id name ตรงกันได้เลย -->
                        <!-- สั่งงาน -->
                        <div class="mb-3 row">
                            <label for="" class="col-sm-2 col-form-label mt-2" style="min-width: 100px">สั่งงาน</label>
                            <div class="col-sm-10">
                                <div class="row mb-2">
                                    <div class="col decinput">
                                        <input type="text" class="form-control" id="c1" name="c1" placeholder="<?php if (isset($result['contact1'])) { echo $result['contact1'];}
                                        else echo "ช่องทางสำหรับการสั่งงาน"; ?>" >
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col decinput">
                                        <input type="text" class="form-control" id="uc1" name="uc1" placeholder="URL (ถ้ามี)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- จบ : สั่งงาน -->
                        <!-- ส่งงาน -->
                        <div class="mb-3 row">
                            <label for="" class="col-sm-2 col-form-label mt-2" style="min-width: 100px">ส่งงาน</label>
                            <div class="col-sm-10">
                                <div class="row mb-2">
                                    <div class="col decinput">
                                        <input type="text" class="form-control" id="" name="c2" placeholder="<?php if (isset($result['contact2'])) { echo $result['contact2'];}
                                        else echo "ช่องทางสำหรับการส่งงาน"; ?>" >
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col decinput">
                                        <input type="text" class="form-control" id="" name="uc2" placeholder="URL (ถ้ามี)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- จบ : ส่งงาน -->
                        <!-- คลิป -->
                        <div class="mb-3 row">
                            <label for="" class="col-sm-2 col-form-label mt-2" style="min-width: 100px">คลิป</label>
                            <div class="col-sm-10">
                                <div class="row mb-2">
                                    <div class="col decinput">
                                        <input type="text" class="form-control" id="" name="c3" placeholder="<?php if (isset($result['contact3'])) { echo $result['contact3'];}
                                        else echo "ช่องทางสำหรับการลงคลิป"; ?>" >
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col decinput">
                                        <input type="text" class="form-control" id="" name="uc3" placeholder="URL (ถ้ามี)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- จบ : คลิป -->
                        <!-- ติดต่ออาจารย์ -->
                        <div class="mb-3 row">
                            <label for="" class="col-sm-2 col-form-label mt-2" style="min-width: 100px">ติดต่ออาจารย์</label>
                            <div class="col-sm-10">
                                <div class="row mb-2">
                                    <div class="col decinput">
                                        <input type="text" class="form-control" id="" name="c4" placeholder="<?php if (isset($result['contact4'])) { echo $result['contact4'];}
                                        else echo "ช่องทางสำหรับการติดต่ออาจารย์"; ?>" >
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col decinput">
                                        <input type="text" class="form-control" id="" name="uc4" placeholder="URL (ถ้ามี)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- จบ : ติดต่ออาจารย์ -->
                        <!-- สอบผ่าน -->
                        <div class="mb-3 row">
                            <label for="" class="col-sm-2 col-form-label mt-2" style="min-width: 100px">สอบผ่าน</label>
                            <div class="col-sm-10">
                                <div class="row mb-2">
                                    <div class="col decinput">
                                        <input type="text" class="form-control" id="" name="c5" placeholder="<?php if (isset($result['contact5'])) { echo $result['contact5'];}
                                        else echo "ช่องทางสำหรับการสอบ"; ?>" >
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col decinput">
                                        <input type="text" class="form-control" id="" name="uc5" placeholder="URL (ถ้ามี)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- จบ : สอบผ่าน -->
            
                        <!-- submit -->
                        <button type="submit" class="btn btn-danger style"> Submit</button>
                    </form>
                </div>                     
            </div>
            <!-- จบ​ : ส่วนข้อมูลด้านข้างเมนูย่อย -->
        
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