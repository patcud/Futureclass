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

    if (!isset($_SESSION['userid'])) {
        $_SESSION['msg'] = "User ID Error";
        header('location: error.php');
    }

    if (!isset( $_GET['id'])) {
        $_SESSION['msg'] = "Courses ID Error";
        header('location: error.php');;
    }

    $id = $_SESSION['userid'];
    $coursesID = $_GET['id'];

    $sql = "SELECT *
            FROM courses 
            LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
            WHERE coursesID = $coursesID";
    $result = mysqli_fetch_array(mysqli_query($con,$sql));

    $sql2 = "SELECT * FROM requestment WHERE coursesID=$coursesID AND status=0 ORDER BY timestamp DESC";
    $r = mysqli_query($con, $sql2);

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
    <link rel="stylesheet" href="style2.css">
    
    <!-- **** -->
    <title>Subject</title>
  
</head>

<body>

    <!-- ส่วนข้อมูลภายในเว็บ -->
    <div class="container-fluid bg-light">
        <?php include('components/header_student.php') ?>
                                                    
        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <div class="row flex-nowrap w-100" style="margin-top: 5rem !important;">
            <?php include('components/sidebar_student.php') ?>

            <!-- ส่วนข้อมูลด้านข้างเมนูย่อย -->
            <div class="col-11 col-xs-11 col-md-10 bg-light" style="margin-top: 20px;">                    
                <div class="col">
                    <div class="card" style="border-radius: 20px;background-color:transparent;border-color:transparent;margin-top:0px;">        
                                <div class="row">
                                    <div class="col-sm-11 col-9">
                                        <ul class="list-inline" style="margin-bottom: 0px;">
                                            <h3> Request </h3>
                                            <p style="color:#6e6e6e; margin-bottom: 5px">Request เป็นส่วนที่นิสิตสามารถเพิ่มข้อเรียกร้องเกี่ยวกับรายวิชา</p>
                                            <li class="list-inline-item"><div style="width: 20px; height: 20px; border-radius: 50%; background:#D0312D;"></div></li>
                                            <li class="list-inline-item" style="color:#6e6e6e">ตนเองเพิ่ม</li>
                                            <li class="list-inline-item"><div style="width: 20px; height: 20px; border-radius: 50%; background:#303030;"></li>
                                            <li class="list-inline-item" style="color:#6e6e6e">คนอื่นเพิ่ม</li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-1 col-3">
                                        <div style = "display: flex;justify-content:flex-end;position:absolute;top:50%;transform:translateY(-50%)">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                        data-bs-whatever="@mdo">Add</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <hr>
                </div>
                    <?php
                        if (isset($_SESSION['error'])) {
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        }
                    ?>
                    <!--เด้ง Model หลังจากกดปุ่ม Add-->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered  ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Request</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                            <form action="request_submit.php?id=<?php echo $coursesID;?>" method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                        <!--ตัวรับ Topic-->
                                        <div class="mb-3">
                                            <label for="recipient-name" class="col-form-label">Topic:</label>
                                            <!-- ***เก็บ Topic *** -->
                                            <textarea type="text" class="form-control" id="topic-requestment" name="topic" required></textarea>
                                        </div>
                                        <!--ตัวรับ Details-->
                                        <div class="mb-3">
                                            <label for="message-text" class="col-form-label">Details:</label>
                                            <!-- ***เก็บ Details *** -->
                                            <textarea type="text" class="form-control" id="details-requestment" name="details"></textarea>
                                        </div>
                                        <!--ตัวรับ File-->
                                        <div class="mb-3">
                                            <!-- ***เก็บ File *** -->
                                            <input type="hidden" name="MAX_FILE_SIZE" value="25000000">
                                            <input type="file" id="myFile" name="file">
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="AnonymousRequest" id="AnRe" value=1>
                                                <label class="form-check-label" for="AnRe">
                                                Anonymous
                                                </label>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger">Add Request</button>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>



                    <!--create card auto-->
                    <!--row-cols-1 : แถวนึงมี1อัน , g-3 : ช่องห่างระหว่างอัน-->
                    <div id='requestment_card' class="row row-cols-1 g-1 bg-light"></div>
                    <?php 
                        while($a = mysqli_fetch_array($r)) {
                            $sql = "SELECT * FROM students WHERE mail = '$a[userEmail]'";
                            if ($a['userEmail'] == $_SESSION['email']) {
                                $type = 'text-danger';
                                $color = "red";
                            } else {
                                $type = 'style = "#6E6E6E"';
                                $color = "#6E6E6E";
                            }
                            echo '
                                <div class="col">
                                <div class="card" style="border-radius: 20px;">
                                <div class="card-body">
                                <div class="container">
                                <div class="row">
                                <div class="col-10 col-sm-11">
                                <div class="container">
                                <h4 class="card-title '.$type.'">
                                <a href="request.php?id='.$a['requestmentID'].'" style="text-decoration:none;color:'.$color.';">
                                '.$a['topic'].'
                                </a>
                                </h4>
                                <p class="text-muted">
                                '.$a['timestamp'].'
                                </p>
                                </div>
                                </div>
                                <div class="col-2 col-sm-1">
                                <a href="request.php?id='.$a['requestmentID'].'"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" style="position:absolute;top:50%;transform:translateY(-50%)" fill="black" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg></a>
                                </div>
                                </div>
                                </div>
                                </div>
                                </div>
                                ';
                        }
                    ?>
                    <!-- เพิ่ม 1 -->
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
    <?php include('components/noti_student.php') ?>
    <!-- จบ : รันแจ้งเตือน -->
                            
</body>
</html>