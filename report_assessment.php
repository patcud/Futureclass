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
        <div class="row justify-content-md-center" style="margin-top: 4rem !important;">

            <div class="row">
                <!--สร้างtab ย่อยด้านบนเพื่อแสดงreportย่อย-->
                <div class="list-of-re" style="margin-top: 35px;">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link text-danger" aria-current="page" href="report.php">Request Report</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="report_assessment.php">Assessment Report</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="report_teacher_assessment.php">Teacher Assessment Report</a>
                        </li>
                    </ul>
                </div>
                <div class="row my-2">
                    <h3 style="margin-bottom:0px"> ความคิดเห็นที่ถูกรายงานว่าไม่เหมาะสม </h3>
                    <p style="color:#6e6e6e;margin-top:5px;margin-bottom:3px;">[ แสดงความคิดเห็นที่มีผู้ร้องเรียนว่าไม่เหมาะสมโดย Admin สามารถลบหรือเก็บความคิดเห็นเหล่านั้นได้ ]</p>
                </div>
                <hr style="margin-bottom:0px; padding-bottom:0px;">
            </div>

            <!--create card auto-->
            <!--row-cols-1 : แถวนึงมี1อัน , g-3 : ช่องห่างระหว่างอัน-->
            <div id='requestment_report' class="row row-cols-1 g-3 bg-light"></div>
                <?php
                    $sql2 = "SELECT * FROM courses_good_report WHERE report=1 AND action=0 ORDER BY timestamp DESC";
                    $r = mysqli_query($con, $sql2); 
                    while($a = mysqli_fetch_array($r)) {
                        $ra = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM courses_good WHERE ID=$a[ID]"));
                        echo '
                            <div class="col">
                            <div class="card" style="border-radius: 20px;margin-top:3px;margin-bottom:10px;">
                            <div class="card-body">
                            <div class="container-fluid">
                            <div class="row">

                            <div class="col-sm-5 col-md-6 col-lg-7">
                            <h4 class="card-title text-danger">
                            '.$ra['context'].'
                            </h4>
                            <p class="text-muted">
                            '.$ra['timestamp'].'
                            </p>
                            </div>

                            <div class="col-sm-7 col-md-6 col-lg-5">
                            <div class="container-fluid p-0 h-100">

                            <div class="row h-100" style="min-height:50px;">
                        
                            <div class="col-3 my-auto">
                            <svg class="submit" xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#7fe941" class="bi bi-hand-thumbs-up" viewBox="0 0 16 16"><path d="M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.288 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.114 1.564.415 2.068.723l.048.03c.272.165.578.348.97.484.397.136.861.217 1.466.217h3.5c.937 0 1.599-.477 1.934-1.064a1.86 1.86 0 0 0 .254-.912c0-.152-.023-.312-.077-.464.201-.263.38-.578.488-.901.11-.33.172-.762.004-1.149.069-.13.12-.269.159-.403.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2.144 2.144 0 0 0-.138-.362 1.9 1.9 0 0 0 .234-1.734c-.206-.592-.682-1.1-1.2-1.272-.847-.282-1.803-.276-2.516-.211a9.84 9.84 0 0 0-.443.05 9.365 9.365 0 0 0-.062-4.509A1.38 1.38 0 0 0 9.125.111L8.864.046zM11.5 14.721H8c-.51 0-.863-.069-1.14-.164-.281-.097-.506-.228-.776-.393l-.04-.024c-.555-.339-1.198-.731-2.49-.868-.333-.036-.554-.29-.554-.55V8.72c0-.254.226-.543.62-.65 1.095-.3 1.977-.996 2.614-1.708.635-.71 1.064-1.475 1.238-1.978.243-.7.407-1.768.482-2.85.025-.362.36-.594.667-.518l.262.066c.16.04.258.143.288.255a8.34 8.34 0 0 1-.145 4.725.5.5 0 0 0 .595.644l.003-.001.014-.003.058-.014a8.908 8.908 0 0 1 1.036-.157c.663-.06 1.457-.054 2.11.164.175.058.45.3.57.65.107.308.087.67-.266 1.022l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.414-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.047.047.109.177.005.488a2.224 2.224 0 0 1-.505.805l-.353.353.353.354c.006.005.041.05.041.17a.866.866 0 0 1-.121.416c-.165.288-.503.56-1.066.56z"/></svg>                                
                            '.$ra['likeCount'].'
                            </div>

                            <div class="col-3 my-auto">                  
                            <svg class="submit" xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#e94174" class="bi bi-hand-thumbs-down" viewBox="0 0 16 16" ><path d="M8.864 15.674c-.956.24-1.843-.484-1.908-1.42-.072-1.05-.23-2.015-.428-2.59-.125-.36-.479-1.012-1.04-1.638-.557-.624-1.282-1.179-2.131-1.41C2.685 8.432 2 7.85 2 7V3c0-.845.682-1.464 1.448-1.546 1.07-.113 1.564-.415 2.068-.723l.048-.029c.272-.166.578-.349.97-.484C6.931.08 7.395 0 8 0h3.5c.937 0 1.599.478 1.934 1.064.164.287.254.607.254.913 0 .152-.023.312-.077.464.201.262.38.577.488.9.11.33.172.762.004 1.15.069.13.12.268.159.403.077.27.113.567.113.856 0 .289-.036.586-.113.856-.035.12-.08.244-.138.363.394.571.418 1.2.234 1.733-.206.592-.682 1.1-1.2 1.272-.847.283-1.803.276-2.516.211a9.877 9.877 0 0 1-.443-.05 9.364 9.364 0 0 1-.062 4.51c-.138.508-.55.848-1.012.964l-.261.065zM11.5 1H8c-.51 0-.863.068-1.14.163-.281.097-.506.229-.776.393l-.04.025c-.555.338-1.198.73-2.49.868-.333.035-.554.29-.554.55V7c0 .255.226.543.62.65 1.095.3 1.977.997 2.614 1.709.635.71 1.064 1.475 1.238 1.977.243.7.407 1.768.482 2.85.025.362.36.595.667.518l.262-.065c.16-.04.258-.144.288-.255a8.34 8.34 0 0 0-.145-4.726.5.5 0 0 1 .595-.643h.003l.014.004.058.013a8.912 8.912 0 0 0 1.036.157c.663.06 1.457.054 2.11-.163.175-.059.45-.301.57-.651.107-.308.087-.67-.266-1.021L12.793 7l.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581 0-.211-.027-.414-.075-.581-.05-.174-.111-.273-.154-.315l-.353-.354.353-.354c.047-.047.109-.176.005-.488a2.224 2.224 0 0 0-.505-.804l-.353-.354.353-.354c.006-.005.041-.05.041-.17a.866.866 0 0 0-.121-.415C12.4 1.272 12.063 1 11.5 1z"/></svg>
                            '.$ra['dislikeCount'].'
                            </div>

                            <div class="col-3 my-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Delete'.$ra['ID'].'" style="background-color:  Transparent; background-repeat:no-repeat; border: none;"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="svg-delete" viewBox="0 0 16 16"><path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" /></svg></button>
                            </div>

                            <div class="col-3 my-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Pass'.$ra['ID'].'" style="background-color:  Transparent; background-repeat:no-repeat; border: none;"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#20c997" class="bi bi-check2" viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/></svg></button>  
                            </div>

                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            <div class="modal fade" id="Delete'.$ra['ID'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                เป็นข้อความที่ไม่เหมาะสมได้คิดอย่างไตร่ตรองแล้ว
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <!-- ใส่ไฟล์รันPHPต่อ -->
                                <a href="assessment_delete.php?id='.$ra['ID'].'" type="button" class="btn btn-danger" formaction=".php">Delete</a>
                            </div>
                            </div>
                            </div>
                            </div>

                            <div class="modal fade" id="Pass'.$ra['ID'].'" tabindex="-1" aria-labelledby="ModalPass" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalPass">Pass</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                เป็นข้อความที่ไตร่ตรองแล้ว จึงปล่อยผ่าน
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <!-- ใส่ไฟล์รันPHPต่อ -->
                                <a href="assessment_pass.php?id='.$ra['ID'].'" type="button" class="btn btn-danger">Pass</a>
                            </div>
                            </div>
                            </div>
                            </div>
                            ';
                    }
                ?>

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