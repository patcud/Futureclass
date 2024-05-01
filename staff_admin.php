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
                    <?php include('components/alert.php') ?>

                    <h2 class="text-danger">Staff</h2>
                    <button class="btn btn-danger text-white fw-bolder btn-sm postion-end" data-bs-toggle="modal" data-bs-target="#addStaff">Add</button>
                    <table class="table align-middle table-row-bordered table-row-dashed gy-5">
                        <tbody id="Subject">
                            <tr class="text-start text-gray-800 fw-bolder fs-5 text-uppercase">
                                <th class="min-w-100px">Profile</th>
                                <th class="min-w-200px">Teacher Name</th>
                                <th class="min-w-125px">Section</th>
                                <th class="text-center min-w-70px">Action</th>
                            </tr>
                            <?php 
                                $sql = "SELECT teachersID FROM teacher_course WHERE coursesID=$coursesID ORDER BY teachersID ASC";
                                $r = mysqli_query($con, $sql);
                                while($a = mysqli_fetch_array($r)) {
                                    $tid = $a['teachersID'];
                                    $sql = "SELECT * FROM teachers WHERE teachersID = $tid";
                                    $rsub = mysqli_fetch_array(mysqli_query($con, $sql));
                                    $tname = $rsub['title']." ".$rsub['fname']. " " .$rsub['lname']; 
                                    $sql2 = "SELECT * FROM teacher_course WHERE teachersID = $tid AND coursesID=$coursesID";
                                    $rsub = mysqli_fetch_array(mysqli_query($con, $sql2));
                            ?>
                            <tr>
                                <td class="p-0"><img src= "read_pic.php?id=<?=$tid?>" width="80" class="rounded-circle me-2"></td>
                                <td><?=$tname?></td>
                                <td>section <?=$rsub['section']?></td>
                                <td class="text-center">
                                    <form action="delete_staff_admin.php?id=<?=$coursesID?>" method="POST">
                                        <input type="hidden" name="teacherID" value=<?=$tid?>>
                                        <button type="submit" class="btn btn-danger text-white fw-bolder btn-sm px-5">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                    
            <!-- จบ : ส่วนข้อมูลด้านข้างเมนูย่อย -->
            </div>
        
        <!-- จบ : ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        </div>
    <!-- จบ : ส่วนข้อมูลภายในเว็บ -->
    </div>

    <!-- modal เพิ่ม staff -->
    <div class="modal fade" id="addStaff" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title justify-content-center" id="exampleModalLabel">โปรดเลือกอาจารย์ที่ต้องการเพิ่ม</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!--ปุ่มในการเลือกการรับข้อมูล-->
                    <div class="container">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-bordered table-row-dashed">
                                <tbody id="Subject">
                                    <tr class="text-start text-gray-800 fw-bolder fs-5 text-uppercase">
                                        <th class="text-center">Abbreviation</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Section</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    <?php 
                                        $sql = "SELECT t.*
                                                FROM teachers t
                                                LEFT JOIN (
                                                    SELECT COUNT(*) AS is_regis,teachersID,coursesID 
                                                    FROM teacher_course 
                                                    WHERE coursesID=$coursesID
                                                    GROUP BY teachersID) tc
                                                ON t.teachersID = tc.teachersID
                                                WHERE is_regis IS NULL
                                                ORDER BY abbreviation ASC"; 
                                        $teachers = mysqli_query($con,$sql);
                                        while($teacher = mysqli_fetch_assoc($teachers)) {
                                            $tid = $teacher['teachersID'];
                                            $abbr = $teacher['abbreviation'];
                                            $name = $teacher['fname'] . " " . $teacher["lname"]; 
                                    ?>
                                        <tr class="fs-5">
                                            <form action="controller/admin/staff_admin_add.php?coursesID=<?= $coursesID ?>" method="POST">
                                                <input type="hidden" name="teachersID" value="<?= $tid ?>">
                                                <td class="text-center text-gray-400"><?= $abbr ?></td>
                                                <td class="text-gray-400"><?= $name ?></td>
                                                <td>
                                                    <input type="number" class="form-control" name="section" placeholder="ใส่ section">
                                                </td>
                                                <td class="pe-0 text-center">
                                                    <button class="btn btn-danger text-white fw-bolder btn-sm px-2 addButton">Add</button>
                                                </td>
                                            </form>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- **** -->
    <!-- รันแจ้งเตือน -->
    <?php include('components/noti_admin.php') ?>
    <!-- จบ : รันแจ้งเตือน -->

</body>

</html>