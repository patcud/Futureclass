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
    
    $coursesID = $_GET['id'];
    $sql = "SELECT *
            FROM courses
            LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
            WHERE coursesID=$coursesID";
    $result = mysqli_fetch_array(mysqli_query($con,$sql));
    $coursename = $result['nameEN'];
    $sql2 = "SELECT * FROM requestment WHERE coursesID=$coursesID ORDER BY timestamp DESC";
    $r = mysqli_query($con, $sql2);
    $id = $_SESSION['userid'];
    $resultcount = mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) FROM student_course WHERE coursesID=$coursesID AND status='1'"));
    $regisnow = $resultcount[0];
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

            <!-- ส่วนข้อมูลด้านข้างเมนูย่อย -->
            <div class="col-11 col-xs-11 col-md-10 bg-light" style="margin-top: 20px;">
                <div class="row">
                    <h3>Information</h3>
                </div>
                <!--card รายละเอียดรายวิชา-->
                <div class="col">
                    <p style="color:#6e6e6e">[ แสดงข้อมูลรายละเอียดของวิชาทั้งหมด อาจารย์สามารถแก้ไข/เพิ่มเติมข้อมูลที่ต้องการแสดงได้ ข้อมูลเหล่านี้จะถูกแสดงที่เมนู About ของนิสิตและอาจารย์ ]</p>
                    <hr>
                    <div class="card" style="border-radius: 20px;">
                        <div class="card-body">
                            <h5 class="card-title text-danger">About subject</h5>
                            <dl class="row">
                                <dt class="col-sm-3">รูปประจำวิชา</dt>
                                <dd class="col-sm-9"><img src="read_subject_pic.php?id=<?php echo $coursesID ?>" weight=120px height=120px></dd>

                                <dt class="col-sm-3">รหัสวิชา</dt>
                                <dd class="col-sm-9"><span><?php echo $result['subjectsID']; ?></span></dd>

                                <dt class="col-sm-3">ชื่อวิชา(ภาษาไทย)</dt>
                                <dd class="col-sm-9"><span><?php echo $result['nameTH']; ?></span></dd>

                                <dt class="col-sm-3">ชื่อวิชา(ภาษาอังกฤษ)</dt>
                                <dd class="col-sm-9"><span><?php echo $result['nameEN']; ?></span></dd>

                                <dt class="col-sm-3">สาขาวิชา</dt>
                                <dd class="col-sm-9">&nbsp;&nbsp;<span><?php echo $result['class']; ?></span>

                                <dt class="col-sm-3">จำนวนหน่วยกิต</dt>
                                <dd class="col-sm-9">&nbsp;&nbsp;<span><?php echo $result['credit']; ?></span></dd>
                                <dt class="col-sm-3">คำอธิบายวิชา</dt>
                                <dd class="col-sm-9">&nbsp;&nbsp;<span><?php echo $result['content']; ?></span></dd>
                                <dt class="col-sm-3">จำนวนคนลงทะเบียน</dt>
                                <dd class="col-sm-9">&nbsp;&nbsp;<span><?php echo $regisnow; ?>/<?php echo $result['registotal']; ?></span></dd>
                            </dl>

                            <!--ภาคที่เปิดสอนและลักษณะการสอน-->
                            <h5 class="card-title text-danger">รูปแบบการสอน</h5>
                            <dl class="row">
                                <dt class="col-sm-3">Active</dt>
                                <dd class="col-sm-9">&nbsp;&nbsp;<span><?php echo $result['actionperiod']; ?></span></dd>
                                <dt class="col-sm-3">Section</dt>
                                <dd class="col-sm-9">&nbsp;&nbsp;<span><?php echo $result['sectiontype']; ?></span></dd>
                            </dl>

                            <a href="management_edit_admin.php?id=<?php echo $coursesID; ?>" class="btn btn-danger btn-sm" role="button">edit</a>
                        </div>
                    </div>
                </div>

                <!-- card ช่องทางการส่งงาน-->
                <div class="col">
                    <br>
                    <p style="color:#6e6e6e">[ แสดงข้อมูลรายละเอียดของการสั่งงาน/ส่งงานทั้งหมด อาจารย์สามารถแก้ไข/เพิ่มเติมข้อมูลที่ต้องการแสดงได้ ข้อมูลเหล่านี้จะถูกแสดงที่เมนู About ของนิสิตและอาจารย์ ]</p>
                    <div class="card" style="border-radius: 20px;">
                        <div class="card-body">
                            <h5 class="card-title text-danger">ช่องทางต่าง ๆ</h5>
                            <dl class="row">

                                <dt class="col-sm-3">สั่งงาน</dt>
                                <dd class="col-sm-9"><span><?php echo $result['contact1'] ?></span></dd>

                                <dt class="col-sm-3">ส่งงาน</dt>
                                <dd class="col-sm-9"><span><?php echo $result['contact2'] ?></span></dd>

                                <dt class="col-sm-3">คลิปเรียน</dt>
                                <dd class="col-sm-9"><span><?php echo $result['contact3'] ?></span></dd>

                                <dt class="col-sm-3">ติดต่ออาจารย์</dt>
                                <dd class="col-sm-9"><span><?php echo $result['contact4'] ?></span>

                                <dt class="col-sm-3">ช่องทางการสอน</dt>
                                <dd class="col-sm-9"><span><?php echo $result['contact5'] ?></span></dd>
                            </dl>

                            <a href="contact_admin.php?id=<?php echo $coursesID ?>" class="btn btn-danger btn-sm" role="button">edit</a>
                        </div>
                    </div>
                </div>

                <!--รายละเอียดบทเรียน-->
                <div class="col">
                    <br>
                    <p style="color:#6e6e6e">[ Material แสดงบทเรียนและเนื้อหาโดยย่อของบทเรียน ]</p>
                    <div class="card" style="border-radius: 20px;">
                        <div class="card-body">
                            <dl class="row">
                                <h5 class="card-title text-danger">Material
                                <span style="font-size: x-large">
                                    <a href="material_admin.php?id=<?php echo $coursesID; ?>" style="text-decoration:none; color: white;" class="btn btn-danger btn-sm" role="button">Add
                                    </a>
                                </span>
                                </h5>
                                <!--gen card Material-->
                                <div id='mat_sub_card' class="row row-cols-1 g-1"></div>
                                <!--ดึงไฟล์JSมาใช้-->
                                <?php 
                                    $sql3 = "SELECT * FROM courses_material WHERE coursesID='$coursesID' ORDER BY chapnum ASC ";
                                    $r3 = mysqli_query($con,$sql3);
                                    while($a = mysqli_fetch_array($r3)) {
                                        $chapr3 = $a['chapnum'];
                                        $contentr3 = $a['content'];
                                        echo '
                                            <div class="card" style="border-color:transparent;">
                                            <div class="card-body p-0" >
                                            <dt class="col-sm-4">Chapter '.$chapr3.'</dt>
                                            <dd class="col-sm-8">'.$contentr3.'</dd>
                                            </div>
                                            </div>
                                        ';
                                    }
                                ?>
                        </div>                    
                    </div>
                </div>
                
                <!--อาจารย์ในรายวิชา-->
                <div class="col">
                    <br>
                    <p style="color:#6e6e6e">[ Staff แสดงอาจารย์ในรายวิชา ]</p>
                    <div class="card" style="border-radius: 20px;">
                        <div class="card-body">
                            <dl class="row">
                                <h5 class="card-title text-danger">Staff
                                <span style="font-size: x-large">
                                    <a href="staff_admin.php?id=<?php echo $coursesID; ?>" style="text-decoration:none; color: white;" class="btn btn-danger btn-sm" role="button">Edit
                                    </a>
                                </span>
                                </h5>
                                <!--gen card Material-->
                                <div id='mat_sub_card' class="row row-cols-1 g-1"></div>
                                <!--ดึงไฟล์JSมาใช้-->
                                <dl class="row" id="aj_in_sub">
                                <?php 
                                    $sql = "SELECT teachersID FROM teacher_course WHERE coursesID=$coursesID ORDER BY teachersID ASC";
                                    $r = mysqli_query($con, $sql);
                                    while($a = mysqli_fetch_array($r)) {
                                        $tid = $a['teachersID'];
                                        $sql = "SELECT * FROM teachers WHERE teachersID = $tid";
                                        $rsub = mysqli_fetch_array(mysqli_query($con, $sql));
                                        $subname = $rsub['title']." ".$rsub['fname']. " " .$rsub['lname']; 
                                        $sql2 = "SELECT * FROM teacher_course WHERE teachersID = $tid AND coursesID=$coursesID";
                                        $rsub = mysqli_fetch_array(mysqli_query($con, $sql2));
                                        echo '
                                            <div style="margin-bottom:2px;display:flex;flex-direction:row;align-items:center;justify-content:center>>">
                                            <dt class="col-sm-2" style="margin-bottom:0px;">
                                            <img src= "read_pic.php?id='.$tid.'" width="32" height="32" class="rounded-circle me-2" style="margin-right:0px">
                                            </dt>
                                            <dt class="col-sm-5">'.$subname.'</dt>
                                            <dd class="col-sm-5" style="margin-bottom:0px"><span> section '.$rsub['section'].'</span></dd>
                                            </div>
                                            ';
                                    }
                                ?>
                                </dl>
                        </div>                    
                    </div>
                </div>
                
                <!-- วิชาบังคับที่ต้องลงก่อนลงทะเบียน -->
                <div class="col">
                    <br>
                    <p style="color:#6e6e6e">[ Prerequisite Subject/Skill แสดงรายวิชาบังคับที่ต้องเรียนก่อนจะลงทะเบียนวิชานี้ หรือ ความรู้พิ้นฐานที่ควรทราบ ]</p>
                    <div class="card" style="border-radius: 20px;">
                        <div class="card-body">
                            <dl class="row">
                                <h5 class="card-title text-danger">Prerequisite
                                <span style="font-size: x-large">
                                    <a href="prerequisite_admin.php?id=<?php echo $coursesID; ?>" style="text-decoration:none; color: white;" class="btn btn-danger btn-sm" role="button">Add
                                    </a>
                                </span>
                                </h5>
                                <!--ดึงไฟล์JSมาใช้-->
                                <?php 
                                    $sql4 = "SELECT * FROM courses_prere WHERE coursesID='$coursesID' ORDER BY requiretype ASC ";
                                    $r4 = mysqli_query($con,$sql4);
                                    while($a = mysqli_fetch_array($r4)) {
                                        $chapr3 = $a['requiretype'];
                                        $contentr3 = $a['requirecontent'];
                                        echo '
                                            <div class="card" style="border-color:transparent;">
                                            <div class="card-body p-0">
                                            <dt class="col-sm-4">'.$chapr3.'</dt>
                                            <dd class="col-sm-8"><span>'.$contentr3.'</span></dd>
                                            </div>
                                        ';
                                    }
                                ?>
                        </div>                    
                    </div>
                </div>
            <!-- จบ : ส่วนข้อมูลด้านข้างเมนูย่อย -->
            </div>
        
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