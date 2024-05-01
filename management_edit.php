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
                    <h3 style="margin-bottom:0px">แก้ไขข้อมูล</h3>
                    <hr>
                    <!--เป็นฟอร์มที่จะรับข้อมูลรายละเอียดวิชาใหม่เพื่อไปแสดงผลในหน้า information ของรายวิชา-->
                    <form action = "management_submit.php?id=<?php echo $coursesID; ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="subjectimginput" class="form-label">รูปประจำรายวิชา</label>
                            <div class="input-group mb3">
                            <input type="hidden" name="MAX_FILE_SIZE" value="25000000">
                            <input type="file" class="form-control" id="subjectimginput" style="border-radius: 20px;margin-left:10px;" name="file" accept="image/*"></div>
                        </div>
                        <!--ส่วนที่เป็นข้อมูลที่แก้ไขไม่ได้จะถูกครอบด้วย fieldset disabled สามารถดึงข้อมูลมาแสดงที่ placeholder="ข้อมูลที่ดึงมา"-->
                        <fieldset disabled>
                            <div class="mb-3">
                                <label for="subjectidinput" class="form-label">รหัสวิชา</label>
                                <div class="decinput2">
                                <input type="text" class="form-control" name="subID" placeholder=<?php echo $result['subjectsID'] ?>></div>
                            </div>
                            <div class="mb-3">
                                <label for="thainameinput" class="form-label">ชื่อวิชา(ภาษาไทย)</label>
                                <div class="decinput2">
                                <input type="text" class="form-control" name="thname" placeholder="<?php echo $result['nameTH'] ?>"></div>
                            </div>
                            <div class="mb-3">
                                <label for="engnameinput" class="form-label">ชื่อวิชา(อังกฤษ)</label>
                                <div class="decinput2">
                                <input type="text" class="form-control" name="enname" placeholder="<?php echo $result['nameEN']; ?>"></div>
                            </div>
                            
                            <?php
                                $pwcheck = "";$ctcheck = "";$cmcheck = "";$elcheck = "";$fdcheck = "";
                                if ($result['class'] == "power") { $pwcheck = "Checked disabled"; } 
                                else if ($result['class'] == "control") { $ctcheck = "Checked disabled"; } 
                                else if ($result['class'] == "communication") { $cmcheck = "Checked disabled"; } 
                                else if ($result['class'] == "electronics") { $elcheck = "Checked disabled"; } 
                                else if ($result['class'] == "fundamental") { $fdcheck = "Checked disabled"; }
                            ?>
                            <div class="mb-3">สาขาวิชา</div>
                            <!--ไม่มั่นใจว่าจะต้องดึงค่ามายังไงนะ ที่จจใส่ไว้ตัวที่ถูกเลือกจะมี  Checked disabled ไว้ตัวที่เหลือจะเลือกไม่ได้-->
                            <div class="mb-2"><span>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="class"
                                            id="inlineRadio1" value="power" <?php echo $pwcheck ?>>
                                        <label class="form-check-label" for="inlineRadio1">กำลัง</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="class"
                                            id="inlineRadio2" value="control" <?php echo $ctcheck ?>>
                                        <label class="form-check-label" for="inlineRadio2">ควบคุม</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="class"
                                            id="inlineRadio3" value="commmunication" <?php echo $cmcheck ?>>
                                        <label class="form-check-label" for="inlineRadio3">สื่อสาร</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="class"
                                            id="inlineRadio4" value="electronics" <?php echo $elcheck ?>>
                                        <label class="form-check-label" for="inlineRadio3">อิเล็กทรอนิกส์</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="class"
                                            id="inlineRadio5" value="fundamental" <?php echo $fdcheck ?>>
                                        <label class="form-check-label" for="inlineRadio3">ทั่วไป</label>
                                    </div>
                                </span>
                            </div>

                            <div class="mb-3">
                                <label for="creditinput" class="form-label">จำนวนหน่วยกิต</label>
                                <div class="decinput2">
                                <input type="text" class="form-control" name="credit" placeholder=<?php echo $result['credit']; ?>></div>
                            </div>
                        </fieldset>    
                        <!--สิ้นสุดส่วนข้อมูลที่ห้ามแก้ไข-->
                        <div class="mb-3">
                            <label for="descripinput" class="form-label">คำอธิบายวิชา</label>
                            <div class="decinput">
                            <input type="text" class="form-control" name="content" ></div>
                        </div>
                        <div class="mb-3">
                            <label for="numberinput" class="form-label">จำนวนนิสิตที่เปิดรับ</label>
                            <div class="decinput">
                            <input type="text" class="form-control" name="regisall"></div>
                        </div>
                        <!--แก้ไข type ของรูปแบบการสอนจาก radio เป็น check ทำให้เลือกได้หลายอันพร้อมกัน-->
                        <div class="mb-3 fw-normal">รูปแบบการสอน</div>
                        <dl class="row">
                            <dt class="col-sm-3 fw-normal">Active</dt>
                            <dd class="col-sm-9"><span>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="sem[]"
                                            id="inlineRadio1" value="ภาคต้น">
                                        <label class="form-check-label" for="inlineRadio1">ภาคต้น</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="sem[]"
                                            id="inlineRadio2" value="ภาคปลาย">
                                        <label class="form-check-label" for="inlineRadio2">ภาคปลาย</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="sem[]"
                                            id="inlineRadio3" value="ภาคฤดูร้อน">
                                        <label class="form-check-label" for="inlineRadio3">ภาคฤดูร้อน</label>
                                    </div>
                                </span></dd>
                        </dl>        
                        <dl class="row">
                            <dt class="col-sm-3 fw-normal">Section</dt>
                            <dd class="col-sm-9"><span>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sec"
                                                id="inlineRadio1" value="รวม section">
                                    <label class="form-check-label" for="inlineRadio1">รวมsec</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sec"
                                                id="inlineRadio2" value="แยก section">
                                    <label class="form-check-label" for="inlineRadio2">แยกsec</label>
                                </div>
                            </span></dd>
                        </dl>
                        
                        <!--เมื่อกด submit แล้วข้อมูลจะเชื่อมไปยัง database แล้วจะแสดงผลคือย้อนกลับไปที่หน้า information-->
                        <button type="submit" class="btn btn-danger"> Submit</button>
                    </form>
                </div>
                    
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