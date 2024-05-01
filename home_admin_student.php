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

        <?php include('components/home_admin_banner.php') ?>
        <script>
            // Get a reference to the button element
            var active_banner = document.querySelector('a[href="./home_admin_student.php"]');
            active_banner.classList.remove('shadow')
            active_banner.classList.add('shadow-sm');
            active_banner.classList.add('bg-light');
            active_banner.classList.add('text-black-50');
        </script>

        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <div class="container px-5" style="margin-top: 3rem !important;">
            <div class="p-3 shadow rounded" style="background-color:#FEFEFF">
                <div class="d-flex justify-content-between">
                    <h3 class="fs-2 fw-bold mb-0">Student</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentCSV">เพิ่มนักเรียนใหม่</button>
                </div>
                <br>
                <table class="table align-middle table-row-bordered table-row-dashed">
                    <tbody id="Subject">
                        <tr class="text-start text-gray-800 fw-bolder fs-5 text-uppercase">
                            <th class="text-center w-25">Student ID</th>
                            <th class="text-center w-25">Name</th>
                            <th class="text-center w-30">Email</th>
                            <th class="text-center pe-2 w-20">Action</th>
                        </tr>
                        <?php 
                            $sql = "SELECT *
                                    FROM students
                                    LEFT JOIN user ON students.studentsID = user.id
                                    WHERE is_deleted=0
                                    ORDER BY studentsID ASC"; 
                            $students = mysqli_query($con,$sql);
                            while($student = mysqli_fetch_assoc($students)) {
                                $id = $student['studentsID'];
                                $name = $student['fname'] . " " . $student["lname"]; 
                                $email = $student['userEmail'];
                        ?>
                            <tr class="fs-5">
                                <td class="text-center text-gray-400"><?= $id ?></td>
                                <td class="text-gray-400"><?= $name ?></td>
                                <td class="text-gray-400"><?= $email ?></td>
                                <td class="pe-0 text-center">
                                    <a type="button" class="btn btn-danger text-white fw-bolder btn-sm px-2 editStudent" href="home_admin_student_edit.php?id=<?= $id ?>">Edit</a>
                                    <button type="button" class="btn btn-danger text-white fw-bolder btn-sm px-2 deleteButton">Delete</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

         
            
        <!-- จบ : ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        </div>
    <!-- จบ : ส่วนข้อมูลภายในเว็บ -->
    </div>

    <!-- **** -->
    <!-- รันแจ้งเตือน -->
    <?php include('components/noti_admin.php') ?>
    <!-- จบ : รันแจ้งเตือน -->
    

    <!-- modal -->
    <!-- modal เพิ่มรายชื่อ CSV -->
    <div class="modal fade" id="addStudentCSV" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title justify-content-center" id="exampleModalLabel">กรุณาเพิ่มไฟล์รายชื่อนักเรียน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="./controller/admin/addStudentToSystemCSV.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <!--ปุ่มในการเลือกการรับข้อมูล-->
                        <div class="col-xs-6">
                            <label>Import CSV File:</label>
                            <input type="file" class="form-control" name="file" id="student_csv_file" accept=".csv">
                        </div>
                        <table class="table" id="student_csv_table"></table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function (){
            $('.deleteButton').on('click', function(){
                $('#deleteStudent').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.children('td').map(function() {
                    return $(this).text();
                }).get();

                $('#delete_student').val(data[0]);
            });
        });
    </script>

    <!-- modal alert ลบนักเรียน -->
    <div class="modal fade" id="deleteStudent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title justify-content-center" id="exampleModalLabel">คำเตือน!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="./controller/admin/delete_student.php" method="POST">
                    <div class="modal-body">
                        <h5 class="modal-title justify-content-center" id="exampleModalLabel">ต้องการลบนิสิตหรือไม่</h5>
                        <input type="hidden" name="delete_student" id="delete_student">
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

    <script>
        window.onload = () => {
            // (A) FILE READER + HTML ELEMENTS
            let reader = new FileReader(),
                picker = document.getElementById("student_csv_file"),
                table = document.getElementById("student_csv_table");

            // (B) READ CSV ON FILE PICK
            picker.onchange = () => reader.readAsText(picker.files[0]);

            // (C) READ CSV & GENERATE TABLE
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
                            td.innerHTML = col;
                        }
                    }
                }
            };
        };
    </script>

</body>

</html>