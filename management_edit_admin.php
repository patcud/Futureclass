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
                <div class="container-fluid">
                    <!--เป็นฟอร์มที่จะรับข้อมูลรายละเอียดวิชาใหม่เพื่อไปแสดงผลในหน้า information ของรายวิชา-->
                    <form action = "management_submit.php?id=<?php echo $coursesID; ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="subjectimginput" class="form-label">รูปประจำรายวิชา</label>
                            <div class="input-group mb3">
                            <input type="hidden" name="MAX_FILE_SIZE" value="25000000">
                            <input type="file" class="form-control" id="subjectimginput" style="border-radius: 20px;margin-left:10px;" name="file" accept="image/*"></div>
                        </div>
                        <fieldset disabled>
                            <div class="mb-3">
                                <label for="subjectidinput" class="form-label">รหัสวิชา</label>
                                <input type="text" class="form-control" name="subID" placeholder=<?php echo $result['subjectsID'] ?>>
                            </div>
                            <div class="mb-3">
                                <label for="thainameinput" class="form-label">ชื่อวิชา(ภาษาไทย)</label>
                                <input type="text" class="form-control" name="thname" placeholder="<?php echo $result['nameTH'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="engnameinput" class="form-label">ชื่อวิชา(อังกฤษ)</label>
                                <input type="text" class="form-control" name="enname" placeholder="<?php echo $result['nameEN']; ?>">
                            </div>
                            
                            <?php
                                $pwcheck = "";$ctcheck = "";$cmcheck = "";$elcheck = "";$fdcheck = "";
                                if ($result['class'] == "power") { $pwcheck = "Checked"; } 
                                else if ($result['class'] == "control") { $ctcheck = "Checked"; } 
                                else if ($result['class'] == "communication") { $cmcheck = "Checked"; } 
                                else if ($result['class'] == "electronics") { $elcheck = "Checked"; } 
                                else if ($result['class'] == "fundamental") { $fdcheck = "Checked"; }
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
                                <input type="text" class="form-control" name="credit" placeholder=<?php echo $result['credit']; ?>>
                            </div>
                        </fieldset>
                        
                        <div class="mb-3">
                            <label for="descripinput" class="form-label">คำอธิบายวิชา</label>
                            <input type="text" class="form-control" name="content" placeholder=<?php echo $result['content']; ?>>
                        </div>
                        <div class="mb-3">
                            <label for="numberinput" class="form-label">จำนวนคนลงทะเบียน</label>
                            <input type="text" class="form-control" name="regisall" placeholder=<?php echo $result['registotal']; ?>>
                        </div>
                        
                        <?php 
                            foreach(explode(',',$result['actionperiod']) as $activeperiod) {
                                if ($activeperiod == 'ภาคต้น') {
                                    $premier = "checked";
                                } elseif ($activeperiod == 'ภาคปลาย') {
                                    $dernier = "checked";
                                } elseif ($activeperiod == 'ภาคฤดูร้อน') {
                                    $summer = "checked";
                                }
                            }
                        ?>
                        <div class="mb-3 fw-normal">รูปแบบการสอน</div>
                        <dl class="row">
                            <dt class="col-sm-3 fw-normal">Active</dt>
                            <dd class="col-sm-9"><span>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="sem[]"
                                            id="inlineRadio1" value="ภาคต้น" <?php echo $premier ?>>
                                        <label class="form-check-label" for="inlineRadio1">ภาคต้น</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="sem[]"
                                            id="inlineRadio2" value="ภาคปลาย" <?php echo $dernier ?>>
                                        <label class="form-check-label" for="inlineRadio2">ภาคปลาย</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="sem[]"
                                            id="inlineRadio3" value="ภาคฤดูร้อน" <?php echo $summer ?>>
                                        <label class="form-check-label" for="inlineRadio3">ภาคฤดูร้อน</label>
                                    </div>
                                </span></dd>
                        </dl>

                        <?php
                            if ($result['sectiontype'] == "รวม section") { $compound = "Checked"; } 
                            else if ($result['sectiontype'] == "แยก section") { $separate = "Checked"; } 
                        ?>
                        <dl class="row">
                            <dt class="col-sm-3 fw-normal">Section</dt>
                            <dd class="col-sm-9"><span>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sec"
                                                id="inlineRadio1" value="รวม section"  <?php echo $compound ?>>
                                    <label class="form-check-label" for="inlineRadio1">รวมsec</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sec"
                                                id="inlineRadio2" value="แยก section">
                                    <label class="form-check-label" for="inlineRadio2"  <?php echo $separate ?>>แยกsec</label>
                                </div>
                            </span></dd>
                        </dl>

                        <div class="mb-3">
                            <label for="password" class="form-label">รหัสลงทะเบียน</label>
                            <input type="text" class="form-control" name="password" placeholder=<?php echo $result['password']; ?>>
                        </div>
                        
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
    <?php include('components/noti_admin.php') ?>
    <!-- จบ : รันแจ้งเตือน -->

</body>

</html>