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
    $coursesID = $_GET['id'];
    $sql = "SELECT *
            FROM courses
            LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
            WHERE coursesID=$coursesID";
    $result = mysqli_fetch_array(mysqli_query($con,$sql));
    $coursename = $result['nameEN'];
    $contact1 = $result['contact1'];
    $contact2 = $result['contact2'];
    $contact3 = $result['contact3'];
    $contact4 = $result['contact4'];
    $contact5 = $result['contact5'];
    $sql2 = "SELECT teachersID FROM teacher_course WHERE coursesID=$coursesID ORDER BY teachersID ASC";
    $r = mysqli_query($con, $sql2);
    $userID = $_SESSION['userid'];
    $sql3 = "SELECT * FROM courses_material WHERE coursesID='$coursesID' ORDER BY chapnum ASC ";
    $r3 = mysqli_query($con,$sql3);
    $sql4 = "SELECT * FROM courses_prere WHERE coursesID='$coursesID' ORDER BY requiretype ASC ";
    $r4 = mysqli_query($con,$sql4);


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
        <div class="row flex-nowrap w-100" style="margin-top: 5rem !important;">
            <?php include('components/sidebar_admin.php') ?>

            <!-- **** -->
            <!--ส่วนที่สองข้อมูลตรงกลาง ขนาดหน้าจอใหญ่กว่าขนาดMdจะมีความกว้าง5คอลัมน์ ถ้าเล็กกว่าจะเรียงต่อกัน-->
            <div class="col-6 col-xs-6 col-md-5 bg-light" style="margin-top: 15px; ">
                <!--row-cols-1 : แถวนึงมี1อัน , g-3 : ช่องห่างระหว่างอัน-->
                <div class="row row-cols-1 g-1 bg-light">

                    <!--เริ่ม Coppy ตรงนี้ค้าบ -->
                    <!--Card About-->
                    <div class="col">
                        <div class="card" style="border-radius: 20px;">
                            <div class="card-body">
                                <h5 class="card-title text-danger">About</h5>
                                <dl class="row">

                                    <!--gen card ประเมินอาจารย์-->
                                    <div id='about_sub'></div>
                                    <!--ดึงไฟล์JSมาใช้-->
                   
                                        <dt class="col-sm-6">รหัสวิชา</dt>
                                        <dd class="col-sm-6"><span><?php echo $result['subjectsID'] ?></span></dd>

                                        <dt class="col-sm-6">ชื่อวิชา (ภาษาไทย) </dt>
                                        <dd class="col-sm-6"><span><?php echo $result['nameTH'] ?></span></dd>

                                        <dt class="col-sm-6">ชื่อวิชา (ภาษาอังกฤษ) </dt>
                                        <dd class="col-sm-6"><span><?php echo $result['nameEN'] ?></span></dd>

                                        <dt class="col-sm-6">สาขา</dt>
                                        <dd class="col-sm-6"><span><?php echo $result['class'] ?></span></dd>

                                        <dt class="col-sm-6">จำนวนหน่วยกิต</dt>
                                        <dd class="col-sm-6"><span><?php echo $result['credit'] ?></span></dd>
                                        
                                        <!-- <dt class="col-sm-6">คำอธิบายวิชา</dt>
                                        <dd class="col-sm-6"><span><?php echo $result['content'] ?></span></dd> -->

                                        <!-- query ข้อมูล -->
                                        <?php 
                                            $regisnow = mysqli_fetch_array(
                                                mysqli_query($con,
                                                    "SELECT COUNT(*) FROM student_course WHERE coursesID = $coursesID"
                                                )
                                            )[0];
                                        ?>

                                        <dt class="col-sm-6">จำนวนคนลงทะเบียน</dt>
                                        <dd class="col-sm-6"><span><?php echo $regisnow ?></span></dd>

                                        <dt class="col-sm-6">Active</dt>
                                        <dd class="col-sm-6"><span><?php echo $result['actionperiod'] ?></span></dd>

                                </dl>
                            </div>
                        </div>
                    </div>

                <!-- Coppy ถึงตรงนี้พอคั้บ--> 
                
                    <!--Card Material-->
                    <div class="col">
                        <div class="card" style="border-radius: 20px;">
                            <div class="card-body">
                                <h5 class="card-title text-danger">Material</h5>
                                <dl class="row">
                                    <!--gen card ประเมินอาจารย์-->
                                    <div id='mat_sub'></div>
                                    <!--ดึงไฟล์JSมาใช้-->
                                    <?php 
                                        $sql = "SELECT * FROM courses_material WHERE coursesID='$coursesID' ORDER BY chapnum ASC ";
                                        $r3 = mysqli_query($con,$sql);
                                        while($a = mysqli_fetch_array($r3)) {
                                            echo '
                                                <dt class="col-sm-4">Chapter '.$a['chapnum'].'</dt>
                                                <dd class="col-sm-8"><span>'.$a['content'].'</span></dd>
                                            ';
                                        }
                                    ?>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!--Card Prerequisite สมมติยังไม่่ต้องดึงข้อมูล-->
                    <div class="col">
                        <div class="card" style="border-radius: 20px;">
                            <div class="card-body">
                                <h5 class="card-title text-danger">Prerequisite</h5>
                                <dl class="row">
                                <?php 
                                    $sql = "SELECT * FROM courses_prere WHERE coursesID='$coursesID' ORDER BY requiretype ASC ";
                                    $r4 = mysqli_query($con,$sql);
                                        while($a = mysqli_fetch_array($r4)) {
                                            echo '
                                                <dt class="col-sm-4">'.$a['requiretype'].'</dt>
                                                <dd class="col-sm-8"><span>'.$a['requirecontent'].'</span></dd>
                                            ';
                                        }
                                    ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- **** -->
            <!--ส่วนที่สามข้อมูลขวามือ ขนาดหน้าจอใหญ่กว่าขนาดMdจะมีความกว้าง5คอลัมน์ ถ้าเล็กกว่าจะเรียงต่อกัน-->
            <div class="col-5 col-xs-5 col-md-5 bg-light" style="margin-top: 15px;">
                <div class="row row-cols-1 g-1 bg-light">

                    <!--Card Staff-->
                    <div class="col">
                        <div class="card" style="border-radius: 20px;">
                            <div class="card-body">
                                <h5 class="card-title text-danger">Staff</h5>
                                <dl class="row" id="aj_in_sub">
                                    <?php          
                                        $sql = "SELECT * FROM teacher_course WHERE coursesID=$coursesID ORDER BY section ASC";
                                        $r = mysqli_query($con, $sql);
                                        while($a = mysqli_fetch_array($r)) {
                                            $tid = $a['teachersID'];
                                            $sql = "SELECT * FROM teachers WHERE teachersID = $tid";
                                            $rsub = mysqli_fetch_array(mysqli_query($con, $sql));
                                            $subname = $rsub['title']." ".$rsub['fname']. " " .$rsub['lname']; 
                                            $tsec = $a['section'];
                                            echo '
                                                <div style="margin-bottom:2px;display:flex;flex-direction:row;align-items:center;justify-content:center>>">
                                                <dt class="col-sm-2" style="margin-bottom:0px;">
                                                <img src= "read_pic.php?id='.$tid.'" width="32" height="32" class="rounded-circle me-2" style="margin-right:0px">
                                                </dt>
                                                <dt class="col-sm-5">'.$subname.'</dt>
                                                <dd class="col-sm-5" style="margin-bottom:0px"><span> section '.$tsec.'</span></dd>
                                                </div>
                                                ';
                                        }
                                    ?>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!--Card ช่องทางต่างๆ-->
                    <div class="col">
                        <div class="card" style="border-radius: 20px;">
                            <div class="card-body">
                                <h5 class="card-title text-danger">ช่องทางต่างๆ</h5>
                                <dl class="row">

                                <dt class="col-sm-6">สั่งงาน</dt>
                                    <?php if ($result['url1'] != "") { ?>
                                    <dd class="col-sm-6"><span><a href ="<?php echo $result['url1']; ?>" style="text-decoration: none;color: black;"><?php echo $result['contact1'];?></a></span></dd>
                                    <?php } else { ?>
                                    <dd class="col-sm-6"><span><?php echo $result['contact1'];?></span></dd>
                                    <?php } ?>

                                    <dt class="col-sm-6">ส่งงาน</dt>
                                    <?php if ($result['url2'] != "") { ?>
                                    <dd class="col-sm-6"><span><a href ="<?php echo $result['url2']; ?>" style="text-decoration: none;color: black;"><?php echo $result['contact2'];?></a></span></dd>
                                    <?php } else { ?>
                                    <dd class="col-sm-6"><span><?php echo $result['contact2'];?></span></dd>
                                    <?php } ?>

                                    <dt class="col-sm-6">คลิป</dt>
                                    <?php if ($result['url3'] != "") { ?>
                                    <dd class="col-sm-6"><span><a href ="<?php echo $result['url3']; ?>" style="text-decoration: none;color: black;"><?php echo $result['contact3'];?></a></span></dd>
                                    <?php } else { ?>
                                    <dd class="col-sm-6"><span><?php echo $result['contact3'];?></span></dd>
                                    <?php } ?>

                                    <dt class="col-sm-6">ติดต่ออาจารย์</dt>
                                    <?php if ($result['url4'] != "") { ?>
                                    <dd class="col-sm-6"><span><a href ="<?php echo $result['url4']; ?>" style="text-decoration: none;color: black;"><?php echo $result['contact4'];?></a></span></dd>
                                    <?php } else { ?>
                                    <dd class="col-sm-6"><span><?php echo $result['contact4'];?></span></dd>
                                    <?php } ?>

                                    <dt class="col-sm-6">สอนผ่าน</dt>
                                    <?php if ($result['url5'] != "") { ?>
                                    <dd class="col-sm-6"><span><a href ="<?php echo $result['url5']; ?>" style="text-decoration: none;color: black;"><?php echo $result['contact5'];?></a></span></dd>
                                    <?php } else { ?>
                                    <dd class="col-sm-6"><span><?php echo $result['contact5'];?></span></dd>
                                    <?php } ?>


                                </dl>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        
        <!-- จบ : ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        </div>
    <!-- จบ : ส่วนข้อมูลภายในเว็บ -->
    </div>

    <!-- **** -->
    <!-- รันแจ้งเตือน -->
    <?php include('component/noti_admin.php') ?>
    <!-- จบ : รันแจ้งเตือน -->

</body>

</html>