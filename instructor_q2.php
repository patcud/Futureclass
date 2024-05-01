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
    
    if (!isset( $_GET['tid'])) {
        $_SESSION['msg'] = "Teacher ID Error";
        header('location: error.php');;
    }
    
    $id = $_SESSION['userid'];
    $coursesID = $_GET['id'];
    $teacherID = $_GET['tid'];
    $sql = "SELECT *
            FROM courses 
            LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
            WHERE coursesID = $coursesID";
    $result = mysqli_fetch_array(mysqli_query($con,$sql));
    $sql2 = "SELECT * FROM teachers_good WHERE coursesID=$coursesID AND teachersID='$teacherID' AND qtype='suggestion' AND status=0 ORDER BY timestamp DESC";
    $r = mysqli_query($con,$sql2);
    $tr = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM teachers WHERE teachersID = '$teacherID'"));
    $tname = $tr['title']." ".$tr['fname']." ".$tr['lname'];

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
    <div class="container-fluid bg-light"> 
        <?php include('components/header_student.php') ?>
                                      
        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <div class="row flex-nowrap w-100" style="margin-top: 5rem !important;">
            <?php include('components/sidebar_student.php') ?>

            <!-- ส่วนข้อมูลด้านข้างเมนูย่อย -->
            <div class="col-11 col-xs-11 col-md-10" style="margin-top: 20px;">     
                <!--สร้างtab ย่อยด้านบนเพื่อแสดงคำถามประเภทต่างๆซึ่งหน้าหลักที่จะแสดงคือหน้าการประเมินแบบเลือกคะแนน ซึ่งหลังclass จะกำหนดให้active-->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link text-danger" aria-current="page" href="assessment_instructor_student.php?id=<?php echo($coursesID) ?>&tid=<?php echo $teacherID; ?>">คะแนน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="instructor_q1.php?id=<?php echo($coursesID) ?>&tid=<?php echo $teacherID; ?>">ข้อดี</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="instructor_q2.php?id=<?php echo($coursesID) ?>&tid=<?php echo $teacherID; ?>">ข้อเสนอแนะ</a>
                    </li>
                </ul>
                <?php
                    $sql = "SELECT section FROM student_course WHERE coursesID=$coursesID AND studentsID=$id";
                    $student_section = mysqli_fetch_array(mysqli_query($con, $sql))[0];
                    $sql2 = "SELECT section FROM teacher_course WHERE coursesID=$coursesID AND teachersID=$teacherID";
                    $teacher_section = mysqli_fetch_array(mysqli_query($con, $sql2))[0];
                    if ($result['sectiontype'] == "แยก section" && $student_section != $teacher_section){ ?>
                <h3>ไม่สามารถทำการประเมินได้</h3>
                <hr>
                <?php } else { ?>
                <div class="col">
                    <div class="card" style="border-radius: 20px;background-color:transparent;border-color:transparent;">
                        <div class="row">
                            <div class="col-sm-11 col-9">
                                <h3>ข้อเสนอแนะเพื่อพัฒนาการเรียนการสอนของ <?php echo $tname ?></h3>
                                <p style="color:#6e6e6e; margin-bottom:0px;">[ เป็นการประเมินความเห็น นิสิตสามารถเพิ่มความเห็นได้ตลอดทั้งเทอม และสามารถกด like ความคิดเห็นที่ตรงกับผู้อื่นได้ ]</p>
                            </div>
                            <div class="col-sm-1 col-3">
                                <!-- หลังจากกดปุ่ม Add ทำให้ Model เด้งขึ้นมา -->
                                <div style = "display: flex; justify-content:flex-end;position:absolute;top:50%;transform:translateY(-50%)">
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#Modalassessment1" data-bs-whatever="@mdo">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                        
                <hr>

                <!-- gen topic assessment1 -->
                <div id="assessment_card"></div>

                <!--**เด้ง Model หลังจากกดปุ่ม Add**-->
                <div class="modal fade" id="Modalassessment1" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <!-- หัวข้อ -->
                                <h5 class="modal-title" id="exampleModalLabel">Comment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <!-- ตัวโมเดลที่ซ่อนไว้ แสดงเมื่อกดปุ่ม Add -->
                            <div class="modal-body">
                                <!-- form id="addcomment" -->
                                <form id="assessment1" method="POST" action="instructor_q2_submit.php?id=<?php echo $coursesID;?>&tid=<?php echo $teacherID ?>">
                                    <!--ตัวรับ Comment-->
                                    <div class="mb-3">
                                        <!--เก็บ Comment-->
                                        <label for="message-text" class="col-form-label">Comment:</label>
                                        <textarea class="form-control" id="details-assessment1"
                                            name="comment" required></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger" form="assessment1">Add Comment</button>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!--ดึงไฟล์JSมาใช้-->
                <div id='requestment_card' class="row row-cols-1 g-1 bg-light"></div>
                    <?php 
                        while($a = mysqli_fetch_array($r)) {
                            $qid = $a['ID'];
                            $email = $_SESSION['email'];
                            $oldres = mysqli_fetch_array(mysqli_query($con,"SELECT response FROM teachers_good_response WHERE qid=$qid and userEmail = '$email'"));
                            $like_color = '#979797';
                            $dislike_color = '#979797';
                            if (isset($oldres)) {
                                if ($oldres[0] == 'like') {
                                        $like_color = '#7fe941';
                                        $dislike_color = '#979797';
                                }
                                else if ($oldres[0] == 'dislike') {
                                        $like_color = '#979797';
                                        $dislike_color = '#e94174';
                                }
                            }
                            $report_color = '#979797';
                            $oldreport = mysqli_fetch_array(mysqli_query($con,"SELECT report FROM teachers_good_report WHERE ID=$qid and userEmail = '$email'"));
                            if (isset($oldreport)) {
                                $report_color = '#ffc74d';
                            }
                            if ($report_color == '#979797') {
                            echo '
                                <!-- Modal report-->
                                <div class="modal fade" id="modelreport'.$qid.'" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Report</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                เข้าข่ายเป็นข้อความที่ไม่สุภาพ ไม่ให้เกียรติผู้อื่น
                                ไม่เกี่ยวข้องกับการแลกเปลี่ยนความคิดเห็นเพื่อพัฒนาระบบการศึกษา
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <!-- ใส่ php -->
                                <a href="qt_report.php?id='.$qid.'&cid='.$coursesID.'&tid='.$teacherID.'&qn=q2" class="btn btn-danger" role="button">Report</a>
                                </div>
                                </div>
                                </div>
                                </div>';
                            }
                            echo '
                                <div class="col">
                                <div class="card" style="border-radius: 20px;">
                                <div class="card-body">
                                <div class="container-fluid">
                                <div class="row">
                                <div class="col-sm-5">
                                <h4 class="card-title text-danger">
                                '.$a['context'].'
                                </h4>
                                <p class="text-muted">
                                '.$a['timestamp'].'
                                </p>
                                </div>
                                <div class="col-sm-7">
                                <div class="box">
                                <form class="form" id="likedislikeform" method="POST" action="instructor_q2_response.php?cid='.$coursesID.'&id='.$qid.'&tid='.$teacherID.'">
                                <label class="custom-radio-button__container">
                                <input type="radio" name="radio" id="votelike" value="like" onclick="this.form.submit();">
                                <span class="custom-radio-button">
                                <svg class="submit" xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill='.$like_color.' class="bi bi-hand-thumbs-up" viewBox="0 0 16 16"><path d="M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.288 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.114 1.564.415 2.068.723l.048.03c.272.165.578.348.97.484.397.136.861.217 1.466.217h3.5c.937 0 1.599-.477 1.934-1.064a1.86 1.86 0 0 0 .254-.912c0-.152-.023-.312-.077-.464.201-.263.38-.578.488-.901.11-.33.172-.762.004-1.149.069-.13.12-.269.159-.403.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2.144 2.144 0 0 0-.138-.362 1.9 1.9 0 0 0 .234-1.734c-.206-.592-.682-1.1-1.2-1.272-.847-.282-1.803-.276-2.516-.211a9.84 9.84 0 0 0-.443.05 9.365 9.365 0 0 0-.062-4.509A1.38 1.38 0 0 0 9.125.111L8.864.046zM11.5 14.721H8c-.51 0-.863-.069-1.14-.164-.281-.097-.506-.228-.776-.393l-.04-.024c-.555-.339-1.198-.731-2.49-.868-.333-.036-.554-.29-.554-.55V8.72c0-.254.226-.543.62-.65 1.095-.3 1.977-.996 2.614-1.708.635-.71 1.064-1.475 1.238-1.978.243-.7.407-1.768.482-2.85.025-.362.36-.594.667-.518l.262.066c.16.04.258.143.288.255a8.34 8.34 0 0 1-.145 4.725.5.5 0 0 0 .595.644l.003-.001.014-.003.058-.014a8.908 8.908 0 0 1 1.036-.157c.663-.06 1.457-.054 2.11.164.175.058.45.3.57.65.107.308.087.67-.266 1.022l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.414-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.047.047.109.177.005.488a2.224 2.224 0 0 1-.505.805l-.353.353.353.354c.006.005.041.05.041.17a.866.866 0 0 1-.121.416c-.165.288-.503.56-1.066.56z"/></svg></span>                                
                                '.$a['likeCount'].'
                                </label> 
                                <label class="custom-radio-button__container">
                                <input type="radio" name="radio" id="votelike" value="dislike" onclick="this.form.submit();">
                                <span class="custom-radio-button">
                                <svg class="submit" xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill='.$dislike_color.' class="bi bi-hand-thumbs-down" viewBox="0 0 16 16"><path d="M8.864 15.674c-.956.24-1.843-.484-1.908-1.42-.072-1.05-.23-2.015-.428-2.59-.125-.36-.479-1.012-1.04-1.638-.557-.624-1.282-1.179-2.131-1.41C2.685 8.432 2 7.85 2 7V3c0-.845.682-1.464 1.448-1.546 1.07-.113 1.564-.415 2.068-.723l.048-.029c.272-.166.578-.349.97-.484C6.931.08 7.395 0 8 0h3.5c.937 0 1.599.478 1.934 1.064.164.287.254.607.254.913 0 .152-.023.312-.077.464.201.262.38.577.488.9.11.33.172.762.004 1.15.069.13.12.268.159.403.077.27.113.567.113.856 0 .289-.036.586-.113.856-.035.12-.08.244-.138.363.394.571.418 1.2.234 1.733-.206.592-.682 1.1-1.2 1.272-.847.283-1.803.276-2.516.211a9.877 9.877 0 0 1-.443-.05 9.364 9.364 0 0 1-.062 4.51c-.138.508-.55.848-1.012.964l-.261.065zM11.5 1H8c-.51 0-.863.068-1.14.163-.281.097-.506.229-.776.393l-.04.025c-.555.338-1.198.73-2.49.868-.333.035-.554.29-.554.55V7c0 .255.226.543.62.65 1.095.3 1.977.997 2.614 1.709.635.71 1.064 1.475 1.238 1.977.243.7.407 1.768.482 2.85.025.362.36.595.667.518l.262-.065c.16-.04.258-.144.288-.255a8.34 8.34 0 0 0-.145-4.726.5.5 0 0 1 .595-.643h.003l.014.004.058.013a8.912 8.912 0 0 0 1.036.157c.663.06 1.457.054 2.11-.163.175-.059.45-.301.57-.651.107-.308.087-.67-.266-1.021L12.793 7l.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581 0-.211-.027-.414-.075-.581-.05-.174-.111-.273-.154-.315l-.353-.354.353-.354c.047-.047.109-.176.005-.488a2.224 2.224 0 0 0-.505-.804l-.353-.354.353-.354c.006-.005.041-.05.041-.17a.866.866 0 0 0-.121-.415C12.4 1.272 12.063 1 11.5 1z"/></svg></span>
                                '.$a['dislikeCount'].'
                                </label> 
                                <label class="custom-radio-button__container">
                                <span class="custom-radio-button">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"data-bs-target="#modelreport'.$qid.'" style="background-color: Transparent; background-repeat:no-repeat; border: none; ">
                                <svg class="submit" xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill='.$report_color.' class="svg-report" viewBox="0 0 16 16">
                                <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21.294 21.294 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21.317 21.317 0 0 0 14 7.655V1.222z" />
                                </svg>
                                </label> 
                                <br>
                                </form>
                                </div>
                                </div>
                                </div>
                                </div>
                                </div>
                                </div>';
                            }
                    ?>
                <?php } ?>
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