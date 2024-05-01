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
    
    $id = $_SESSION['userid'];
    $sql = "SELECT coursesID FROM courses WHERE semesterID = $semID ORDER BY subjectsID ASC";
    $r = mysqli_query($con,$sql);

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

        <?php include("components/home_admin_banner.php") ?>
        <script>
            // Get a reference to the button element
            var active_banner = document.querySelector('a[href="./home_admin.php"]');
            active_banner.classList.remove('shadow')
            active_banner.classList.add('shadow-sm');
            active_banner.classList.add('bg-light');
            active_banner.classList.add('text-black-50');
        </script>

        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <script>
            function showSubject(str) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("Subject").innerHTML = this.responseText;
                }
                };
                xmlhttp.open("GET", "search_admin.php?text=" + str, true);
                xmlhttp.send();
            }
        </script>

        <div class="container" style="margin-top: 3rem !important;">
            <div class="row gy-4">
                <div class="col-md-4">
                    <div class="p-3 shadow rounded" style="background-color:#FEFEFF">
                        <h3 class="fs-2 fw-bold mb-0">Subject <span class="fs-6 text-black-50">(วิชาที่มีในระบบแล้ว)</span></h3>
                        <div class="table-responsive">
                            <table class="table table-align-middle gy-5">
                                <tbody>
                                    <tr class="text-start text-gray-800 fw-bolder fs-6 text-uppercase">
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Name(EN)</th>
                                    </tr>
                                    <?php
                                        $sql = "SELECT *
                                                FROM subjects
                                                WHERE subjectsID != ''
                                                ORDER BY subjectsID ASC";
                                        $subs = mysqli_query($con,$sql);
                                        while($sub = mysqli_fetch_assoc($subs)) {
                                            $subid = $sub['subjectsID'];
                                            $subNameEN = $sub['nameEN'];
                                    ?>
                                    <tr class="fs-6">
                                        <td class="text-gray-400 w-40"><?= $subid ?></td>
                                        <td class="text-gray-400 w-60"><?= $subNameEN ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="p-3 shadow rounded" style="background-color:#FEFEFF">
                        <div class="d-flex justify-content-between">
                            <h3 class="fs-2 fw-bold mb-0">Course</h3>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addsubmodal">เพิ่มวิชาใหม่</button>
                        </div>
                        <div class="input-group input-group-sm my-3 justify-content-center align-items-center">
                            <div class="input-group-text">
                                <i class="fa-solid fa-magnifying-glass" style="font-size:21px;"></i>
                            </div>
                            <input type="text" class="form-control border" id="subSearch" name="subSearch" onkeyup="showSubject(this.value)"
                                aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Search Here">
                        </div>
                        <div class="line"></div>
                        <div class="table-responsive">
                            <table class="table table-align-middle gy-5">
                                <tbody id="Subject">
                                    <tr class="text-start text-gray-800 fw-bolder fs-6 text-uppercase">
                                        <th class="w-10">Icon</th>
                                        <th class="text-center w-30">Subject ID</th>
                                        <th class="text-center w-30">Name(EN)</th>
                                        <th class="text-center w-30">Action</th>
                                    </tr>
                                    <?php
                                        $sql = "SELECT *
                                                FROM courses
                                                LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
                                                WHERE semesterID = $semID
                                                ORDER BY courses.subjectsID ASC";
                                        $subs = mysqli_query($con,$sql);
                                        while($sub = mysqli_fetch_assoc($subs)) {
                                            $cid = $sub['coursesID'];
                                            $subNameEN = $sub['nameEN'];
                                            $subid = $sub['subjectsID'];
                                    ?>
                                    <tr class="fs-6">
                                        <td class="text-center w-10"><img src="read_subject_pic.php?id=<?= $cid ?>" class="w-25px" style="width:25px;"></td>
                                        <td class="text-gray-400 w-30"><?= $subid ?></td>
                                        <td class="text-gray-400 w-30"><?= $subNameEN ?></td>
                                        <td class="text-center w-30 gap-1 d-block">
                                            <a href="subject_admin.php?id=<?= $cid ?>" class="btn btn-light text-muted fw-bolder btn-sm">View</a>
                                            <button type="button" class="btn btn-danger text-white fw-bolder btn-sm px-2 addstudent">Add student</button>
                                            <button type="button" class="btn btn-danger text-white fw-bolder btn-sm px-2 deletebutton">Delete</button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- จบ : ส่วนข้อมูลภายในเว็บ -->
    </div>

    <!-- **** -->
    <!-- รันแจ้งเตือน -->
    <?php include('components/noti_admin.php') ?>
    <!-- จบ : รันแจ้งเตือน -->
    

    <!-- modal -->
    <!-- modal เพิ่มรายวิชา -->
    <div class="modal fade" id="addsubmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title justify-content-center" id="exampleModalLabel">โปรดเลือกวิธีเพิ่มข้อมูล</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!--ปุ่มในการเลือกการรับข้อมูล-->
                    <div class="container">
                        <div class="d-grid gap-2 mb-4 mx-auto">
                            <button type="button" class="btn btn-danger" id="buttonAddCSV" data-bs-toggle="modal" data-bs-target="#addsubcsv">ไฟล์ CSV</button>
                            <label for="buttonAddCSV" class="text-danger">* สำหรับการเพิ่มรายวิชาด้วยไฟล์ CSV ที่มีวิชาอยู่ในระบบแล้ว</label>
                        </div>
                        <div class="d-grid gap-2 mb-4 mx-auto">
                            <button type="button" class="btn btn-danger" id="buttonAddDeatail" data-bs-toggle="modal" data-bs-target="#adddetail">กรอกรายละเอียด</button>
                            <label for="buttonAddDetail" class="text-danger">* สำหรับการเพิ่มรายวิชาใหม่ที่ไม่เคยมีในระบบ</label>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- modal เพิ่มรายวิชา CSV -->
    <div class="modal fade" id="addsubcsv" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title justify-content-center" id="exampleModalLabel">เพิ่มรายวิชาด้วยไฟล์ CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="addsub_csv.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <!--ปุ่มในการเลือกการรับข้อมูล-->
                        <div class="col-xs-6">
                            <label>Import CSV File:</label>
                            <input type="file" class="form-control" name="file" id="teacher_csv_file" accept=".csv">
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="teacher_csv_table"></table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal เพิ่มรายวิชาแบบกรอกรายละเอียด -->
    <div class="modal fade" id="adddetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เพิ่มรายวิชาแบบกรอกรายละเอียด</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!--เชื่อมกับphpในการรับข้อมูลต่างๆ-->
                    <div class="container mt-3">
                        <form class="row needs-validation" novalidate action="addsub.php" method="POST" enctype="multipart/form-data">
                            <div class="col-md-6">
                                <h2>ข้อมูลทั่วไป</h2>
                                <div class="row mb-3">
                                    <label for="subjectcode" class="col-sm-4 col-form-label">รหัสรายวิชา</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="subjectcode" name="subjectcode" required>
                                        <div class="invalid-feedback">
                                            กรุณากรอกรหัสวิชา
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="subjectimginput" class="col-sm-4 col-form-label">รูปประจำรายวิชา</label>
                                    <div class="col-sm-6">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="25000000">
                                        <input type="file" class="form-control" id="subjectimginput" name="file" accept="image/*">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="subjectnameTH" class="col-sm-4 col-form-label">ชื่อรายวิชา(TH)</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="subjectnameTH" name="subjectnameTH" required>
                                        <div class="invalid-feedback">
                                            กรุณากรอกชื่อรายวิชาภาษาไทย
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="subjectnameENG" class="col-sm-4 col-form-label">ชื่อรายวิชา(ENG)</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="subjectnameENG" name="subjectnameENG" required>
                                        <div class="invalid-feedback">
                                            กรุณากรอกชื่อรายวิชาภาษาอังกฤษ
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="credit" class="col-sm-4 col-form-label">จำนวนหน่วยกิต</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="credit" name="credit" required>
                                        <div class="invalid-feedback">
                                            กรุณากรอกจำนวนหน่วยกิต
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="courseregistotal" class="col-sm-4 col-form-label">จำนวนนิสิตที่เปิดรับ</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="courseregistotal" name="courseregistotal" required>
                                        <div class="invalid-feedback">
                                            กรุณากรอกจำนวนนิสิตที่เปิดรับ
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h2>รูปแบบการสอน</h2>
                                <div class="row mb-3">
                                    <label for="sectiontype" class="col-sm-4 col-form-label">การแบ่งตอนเรียน</label>
                                    <div class="col-sm-6">
                                        <select class="form-select" id="sectiontype" name="sectiontype" required>
                                            <option selected disabled value="">กรุณาเลือก</option>
                                            <option value="แยก section">แยกตอนเรียน</option>
                                            <option value="รวม section">รวมตอนเรียน</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            กรุณาเลือกการแบ่งตอนเรียน
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="subjectclass" class="col-sm-4 form-label">สาขา</label>
                                    <div class="col-sm-6">
                                        <select class="form-select" id="subjectclass" name="subjectclass" required>
                                            <option selected disabled value="">กรุณาเลือก</option>
                                            <option value="power">กำลัง</option>
                                            <option value="control">ควบคุม</option>
                                            <option value="communication">สื่อสาร</option>
                                            <option value="electronics">อิเล็กทรอนิกส์</option>
                                            <option value="fundamental">ทั่วไป</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            กรุณาเลือกการแบ่งตอนเรียน
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="sectiontype" class="col-sm-4 form-label">ภาคการเรียนที่เปิดสอน</label>
                                    <div class="col-sm-8">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sem[]"
                                                id="inlineRadio1" value="ภาคต้น" required>
                                            <label class="form-check-label" for="inlineRadio1">ภาคต้น</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sem[]"
                                                id="inlineRadio2" value="ภาคปลาย" required>
                                            <label class="form-check-label" for="inlineRadio2">ภาคปลาย</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sem[]"
                                                id="inlineRadio3" value="ภาคฤดูร้อน" required>
                                            <label class="form-check-label" for="inlineRadio3">ภาคฤดูร้อน</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="subjectcontent" class="col-sm-4 form-label">คำอธิบายรายวิชา</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" id="subjectcontent" name="subjectcontent" required></textarea>
                                        <div class="invalid-feedback">
                                            กรุณากรอกคำอธิบายรายวิชา
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="coursepassword" class="col-sm-4 form-label">รหัสสำหรับลงทะเบียน</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="coursepassword" name="coursepassword" required>
                                        <div class="invalid-feedback">
                                            กรุณากรอกรหัสสำหรับลงทะเบียน
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            'use strict';
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = $('.needs-validation');

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach(function(form) {
                $(form).submit(function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    $(form).addClass('was-validated');
                });
            });
        });

        $(document).ready(function() {
            $(':checkbox').on('click', function(){
                var checked = $(':checkbox:checked').length;
                if (checked) {
                    $(':checkbox').removeAttr('required');
                } else {
                    $(':checkbox').attr('required','true');
                }
            })
        });
    </script>
    
    <script>
        $(document).ready(function (){
            $('.deletebutton').on('click', function(){
                $('#deletesubject').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.children('td').map(function() {
                    return $(this).text();
                }).get();

                $('#delete_sub').val(data[1]);
            });

            $('.addstudent').on('click', function(){
                $('#addstudent').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.children('td').map(function() {
                    return $(this).text();
                }).get();

                $('#add_student_sub').val(data[1]);
            });
        });
    </script>

    <!-- modal alert ลบรายวิชา -->
    <div class="modal fade" id="deletesubject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title justify-content-center" id="exampleModalLabel">คำเตือน!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="delete_subject.php" method="POST">
                    <div class="modal-body">
                        <h5 class="modal-title justify-content-center" id="exampleModalLabel">ต้องการลบรายวิชาหรือไม่</h5>
                        <input type="hidden" name="delete_sub" id="delete_sub">
                    </div>

                    <div class="modal-footer">
                        <!--ปุ่มในการเลือกการรับข้อมูล-->
                        <div class="row gap-4 mb-4 mx-auto">
                            <button type="submit" class="btn btn-danger col">Yes</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal">No</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal add นิสิตรายวิชา -->
    <div class="modal fade" id="addstudent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title justify-content-center" id="exampleModalLabel">เพิ่มไฟล์ CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="addstudent_csv.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <!--ปุ่มในการเลือกการรับข้อมูล-->
                        <div class="col-xs-6">
                            <label for="name">Import CSV File:</label>
                            <input type="file" class="form-control" name="file" id="student_csv_file" accept=".csv">
                            <input type="hidden" name="add_student_sub" id="add_student_sub">
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="student_csv_table"></table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.onload = () => {
            // (A) FILE READER + HTML ELEMENTS
            let reader_t = new FileReader(),
                picker_t = document.getElementById("teacher_csv_file"),
                table_t = document.getElementById("teacher_csv_table");
            
            let reader = new FileReader(),
                picker = document.getElementById("student_csv_file"),
                table = document.getElementById("student_csv_table");

            // (B) READ CSV ON FILE PICK
            picker_t.onchange = () => reader_t.readAsText(picker_t.files[0]);
            
            picker.onchange = () => reader.readAsText(picker.files[0]);

            // (C) READ CSV & GENERATE TABLE
            reader_t.onloadend = () => {
                let csv_t = reader_t.result;
                table_t.innerHTML = "";
                let rows_t = csv_t.split("\r\n");

                for (let row_t of rows_t) {
                    let cols_t = row_t.match(/(?:\"([^\"]*(?:\"\"[^\"]*)*)\")|([^\",]+)/g);
                    if (cols_t != null) {
                        let tr_t = table_t.insertRow();
                        for (let col_t of cols_t) {
                            let td_t = tr_t.insertCell();
                            td_t.style.whiteSpace = "nowrap";
                            td_t.innerHTML = col_t;
                        }
                    }
                }
            };

            reader.onloadend = () => {
                let csv = reader.result;
                table.innerHTML = "";
                let rows = csv.split("\r\n");

                for (let row of rows) {
                    let cols = row.match(/(?:\"([^\"]*(?:\"\"[^\"]*)*)\")|([^\",]+)/g);
                    if (cols != null) {
                        let tr = table.insertRow();
                        for (let col of cols) {
                            let td = tr.insertCell();
                            td.style.whiteSpace = "nowrap";
                            td.innerHTML = col;
                        }
                    }
                }
            };
        };
    </script>

</body>

</html>