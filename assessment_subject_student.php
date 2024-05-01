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

<body class="bg-light">

    <!-- ส่วนข้อมูลภายในเว็บ -->
    <div class="container-fluid bg-light">    
        <?php include('components/header_student.php') ?>
                                                    
        <!-- ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        <div class="row flex-nowrap w-100" style="margin-top: 5rem !important;">
            <?php include('components/sidebar_student.php') ?>

            <!-- ส่วนข้อมูลด้านข้างเมนูย่อย -->
            <div class="col-11 col-xs-11 col-md-10" style="margin-top: 20px;">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="assessment_subject_student.php?id=<?php echo $coursesID; ?>">คะแนน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="subject_q1.php?id=<?php echo $coursesID; ?>">ข้อดี</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="subject_q2.php?id=<?php echo $coursesID; ?>">ปัญหาที่พบ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="subject_q3.php?id=<?php echo $coursesID; ?>">ข้อเสนอแนะ</a>
                    </li>
                </ul>
                
                <?php 
                    $check = mysqli_fetch_array(mysqli_query($con,"SELECT status FROM assessment_count WHERE coursesID = $coursesID AND userEmail = '$_SESSION[email]'"));
                    if (time() < $_SESSION['startassess'] && time() > $_SESSION['endassess']) { 
                ?>
                    <h3 class="mt-2">ระบบยังไม่เปิดให้ทำการประเมิน</h3>
                    <hr>
                
                <?php } else if ($check[0] == 1) {
                    $qteacher = "SELECT * FROM teacher_course WHERE coursesID = '$coursesID'";
                    $rteacher = mysqli_query($con, $qteacher);
                    $teacherInCourse = array();
                    if (mysqli_num_rows($rteacher) > 0) {
                        while ($row = mysqli_fetch_array($rteacher)) {
                            $teacherInCourse[] = $row['teachersID'];
                        }
                    }
                    $teacherEmail = array();
                    foreach ($teacherInCourse as $teacher) {
                        $qemailteacher = "SELECT mail FROM teachers WHERE teachersID = '$teacher'";
                        $remailteacher = mysqli_query($con, $qemailteacher);
                        if (mysqli_num_rows($remailteacher) > 0) {
                            $resultEmail = mysqli_fetch_array($remailteacher);
                            $emailTeacher = $resultEmail[0];
                        }
                        $teacherEmail[] = $emailTeacher;
                    }
                    $qrequest = "SELECT * FROM requestment WHERE coursesID = '$coursesID'";
                    $rrequest = mysqli_query($con, $qrequest);
                    $requestInCourse = array();
                    if (mysqli_num_rows($rrequest) > 0) {
                        while ($row = mysqli_fetch_array($rrequest)) {
                            $requestInCourse[] = array("requestmentID"=>$row['requestmentID'], "checkTeacherReply"=>0);
                        }
                    }
                    $n = 0;
                    $countTeacherReply = 0;
                    foreach ($requestInCourse as $request) {
                        $qreply = "SELECT * FROM requestment_reply WHERE requestmentID = '$request[requestmentID]'";
                        $rreply = mysqli_query($con, $qreply);
                        if (mysqli_num_rows($rreply) > 0) {
                            while ($row = mysqli_fetch_array($rreply)) {
                                if (in_array($row['userEmail'], $teacherEmail)) {
                                    $requestInCourse[$n]['checkTeacherReply'] = 1;
                                    $countTeacherReply ++;
                                    break 1;
                                }
                            }
                        }
                        $n++;
                    }
                
                    $query_assess_num = "SELECT * FROM assessment_count WHERE coursesID = '$coursesID' AND status = 1";
                    $results_assess_num = mysqli_query($con, $query_assess_num);
                    $assess_num = mysqli_num_rows($results_assess_num);
                
                    $query_req_num = "SELECT * FROM requestment WHERE coursesID = '$coursesID'";
                    $results_req_num = mysqli_query($con, $query_req_num);
                    $req_num = mysqli_num_rows($results_req_num);
                
                    /*
                    $query_question_num = "SELECT MAX(questionID) AS QuestionNum
                                            FROM assessment_score WHERE coursesID = '$coursesID'";
                    $results_question_num = mysqli_fetch_array(mysqli_query($con, $query_question_num));
                    $question_num = $results_question_num['QuestionNum'];
                    */
                
                    // force $question_num = 8 for subject assessment except for some courses *****
                    $question_num = 8;
                    if ($coursesID == 8) {
                        $question_num = 10;
                    } else if ($coursesID == 9) {
                        $question_num = 7;
                    }                
                
                    // query for pie chart
                    $query_pie = "SELECT
                    CASE
                    WHEN status = 1 THEN 'นิสิตที่ทำการประเมินแล้ว'
                    WHEN status = 0 THEN 'นิสิตที่ยังไม่ทำการประเมิน'
                    END AS status,
                    COUNT(userEmail) AS number
                    FROM assessment_count
                    WHERE coursesID = $coursesID
                    GROUP BY status;";
                    $results_pie = mysqli_query($con, $query_pie);
                    $pie_chart_data = array();
                    while ($result = mysqli_fetch_array($results_pie)) {
                    $pie_chart_data[] = array($result['status'], (int)$result['number']);
                    }
                    $pie_chart_data = json_encode($pie_chart_data);
                
                    // query for average scroe
                    $query_av_score = "SELECT questionID AS questionNo,
                                        AVG(score) AS score_average
                                        FROM assessment_score
                                        WHERE coursesID = '$coursesID'
                                        GROUP BY questionNo
                                        ORDER BY questionNo";
                    $resultsav = mysqli_query($con, $query_av_score);
                    $column_chart_dataav = array(array("Question No.", "คะแนนเฉลี่ย"));
                    /* ***** */
                    while ($result = mysqli_fetch_array($resultsav)) {
                        if ($coursesID == 8) {$column_chart_dataav[] = array("ข้อ ".($result['questionNo'] - 20), round($result['score_average'],2));}
                        else if ($coursesID == 9) {$column_chart_dataav[] = array("ข้อ ".($result['questionNo'] - 30), round($result['score_average'],2));}
                        else {$column_chart_dataav[] = array("ข้อ ".$result['questionNo'], round($result['score_average'],2));}
                    }
                    $column_chart_dataav = json_encode($column_chart_dataav);
                
                    $today = date("l jS \of F Y h:i A");
                
                    echo`
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
                    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                    <script type="text/javascript">
                     
                        // Load the Visualization API and the charts package.
                        google.load('visualization', '1.0', {'packages':['corechart']});
                    
                        // Set a callback to run when the Google Visualization API is loaded.
                        google.setOnLoadCallback(drawChart);
                    
                        // Callback that creates and populates a data table,
                        // instantiates the pie chart, passes in the data and
                        // draws it.
                        function drawChart() {
                    
                            // Create the data table.
                            var data = new google.visualization.DataTable();
                            data.addColumn('string', 'Age Range');
                            data.addColumn('number', 'Number');
                            data.addRows({$pie_chart_data});
                    
                            // Set chart options
                            var options = {title:'',
                                titleTextStyle: {fontName: 'Lato', fontSize: 18, bold: true},
                                            height: 400,
                                            is3D: true,
                                colors:['#a11010','#d62322','#8DA9BF','#F2C38D','#E6AC03','#F09B35', '#D94308', '#013453'],
                                chartArea:{left:30,top:30,width:'80%',height:'80%'}};
                    
                            // Instantiate and draw our chart, passing in some options.
                            var chart_div = document.getElementById('pie_chart_div');
                            var chart = new google.visualization.PieChart(chart_div);
                            
                            // Wait for the chart to finish drawing before calling the getIm geURI() method.
                            google.visualization.events.addListener(chart, 'ready', function ()      {
                            chart_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
                            });
                    
                            chart.draw(data, options);
                        }
                    
                        // Make the charts responsive
                        jQuery(document).ready(function(){
                        jQuery(window).resize(function(){
                            drawChart();
                        });
                        });
                     
                    </script>
                    
                    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
                    <script type="text/javascript">
                    
                        // Load the Visualization API and the charts package.
                        google.load('visualization', '1.0', {'packages':['corechart']});
                    
                        // Column Chart
                        google.setOnLoadCallback(drawColumnChart);
                    
                        function drawColumnChart() {
                            var data = google.visualization.arrayToDataTable({$column_chart_dataav});
                    
                            var options = {
                            //title: 'Average score of each question',
                            //titleTextStyle: {fontName: 'Lato', fontSize: 18, bold: true},
                            hAxis: {textStyle: {fontName: 'TH Sarabun New', fontSize: 16}},
                            vAxis: {
                                textStyle: {fontName: 'TH Sarabun New', fontSize: 20},
                                viewWindow: {min: 0, max: 5}
                            },
                            height: 500,
                            chartArea:{left: 50, top: 50, bottom: 50, width: '100%', height: '85%'},
                            legend: { position: "none" },
                            colors:['#a11010','#C6D9AC']
                            };
                    
                            // Instantiate and draw our chart, passing in some options.
                            var chart_div = document.getElementById('column_chart_divav');
                            var chart = new google.visualization.ColumnChart(chart_div);
                    
                            // Wait for the chart to finish drawing before calling the getIm geURI() method.
                            google.visualization.events.addListener(chart, 'ready', function ()      {
                            chart_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
                            });
                    
                            chart.draw(data, options);
                        }
                    
                        // Make the charts responsive
                        jQuery(document).ready(function(){
                            jQuery(window).resize(function(){
                                drawColumnChart();
                            });
                        });
                    
                    </script>
                    
                    `;
                
                /* ***** */
                if ($coursesID == 8) {$i = 21; $lastquestion = 30;} else if ($coursesID == 9) {$i = 31; $lastquestion = 37;} else {$i = 1; $lastquestion = 8;}
                $qnum = 1;
                while ($i <= $lastquestion) {
                
                    // query for bar chart(s)
                    $query_score_vote = "SELECT
                    SUM(CASE WHEN score = 1 THEN 1 ELSE 0 END) AS score1,
                    SUM(CASE WHEN score = 2 THEN 1 ELSE 0 END) AS score2,
                    SUM(CASE WHEN score = 3 THEN 1 ELSE 0 END) AS score3,
                    SUM(CASE WHEN score = 4 THEN 1 ELSE 0 END) AS score4,
                    SUM(CASE WHEN score = 5 THEN 1 ELSE 0 END) AS score5
                    FROM assessment_score WHERE coursesID = '$coursesID' AND questionID = ".$i;
                    $results_score_count = mysqli_query($con, $query_score_vote);
                    $resultscc = mysqli_fetch_array($results_score_count);
                    $column_chart_data = array(array("Score", "จำนวน"));
                    for ($j = 1; $j <= 5; $j++) {
                        $score = "score".$j;
                        $column_chart_data[] = array($j." คะแนน", round($resultscc[$score], 2));
                    }
                    $column_chart_data = json_encode($column_chart_data);
                
                
                echo`
                <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
                <script type="text/javascript">
                
                    // Load the Visualization API and the charts package.
                    google.load('visualization', '1.0', {'packages':['corechart']});
                
                    // Column Chart
                    google.setOnLoadCallback(drawColumnChart);
                
                    function drawColumnChart() {
                        var data = google.visualization.arrayToDataTable({$column_chart_data});
                
                        var options = {
                        title: 'ผลการประเมินจากคำถามข้อที่ $qnum',
                        titleTextStyle: {fontName: 'TH Sarabun New', fontSize: 36, bold: true},
                        hAxis: {textStyle: {fontName: 'TH Sarabun New', fontSize: 20}},
                        vAxis: {textStyle: {fontName: 'TH Sarabun New', fontSize: 20}},
                        height: 500,
                        chartArea:{left:50,top:50,width:'100%',height:'85%'},
                        legend: { position: "top" },
                        colors:['#a11010','#C6D9AC']
                        };
                
                        // Instantiate and draw our chart, passing in some options.
                        var chart_div = document.getElementById('column_chart_div' + $i);
                        var chart = new google.visualization.ColumnChart(chart_div);
                
                        // Wait for the chart to finish drawing before calling the getIm geURI() method.
                        google.visualization.events.addListener(chart, 'ready', function ()      {
                        chart_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
                        });
                
                        chart.draw(data, options);
                    }
                
                    // Make the charts responsive
                    jQuery(document).ready(function(){
                        jQuery(window).resize(function(){
                            drawColumnChart();
                        });
                    });
                
                </script>
                
                `;
                $i++;
                $qnum++;
                }
                
                ?>
                    <h3 class="mt-2">ขอบคุณสำหรับการประเมิน</h3>
                    <hr>

                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-6">
                        <div class="card" style="border-radius: 20px;">
                                <div class="card-body">
                                    <h5 class="card-title text-danger">คะแนนเฉลี่ยการประเมินแบบเลือกคำตอบ</h5>
                                    <dl class="row">
                                        <?php
                                            $query_av_score = "SELECT questionID AS questionNo,
                                            AVG(score) AS score_average
                                            FROM assessment_score
                                            WHERE coursesID = '$coursesID'
                                            GROUP BY questionNo
                                            ORDER BY questionNo";
                                            $resultsav = mysqli_query($con, $query_av_score);
                                            if (mysqli_num_rows($resultsav) > 0):
                                                while ($result = mysqli_fetch_array($resultsav)):
                                        ?>
                                        <!-- ***** -->
                                        <dt class="col-sm-3">ข้อที่ <?php if ($coursesID == 8) {echo $result['questionNo'] - 20;} else if ($coursesID == 9) {echo $result['questionNo'] - 30;} else {echo $result['questionNo'];} ?></dt>
                                        <dd class="col-sm-9">&nbsp;&nbsp;<span><?php echo round($result['score_average'], 2); ?> คะแนน</span></dd>
                                        <?php
                                                endwhile;
                                            else:
                                                for ($x = 1; $x <= $question_num; $x++):
                                        ?>
                                        <dt class="col-sm-3">ข้อที่ <?php echo $x; ?></dt>
                                        <dd class="col-sm-9">&nbsp;&nbsp;<span>- คะแนน</span></dd>
                                        <?php
                                                endfor;
                                            endif;
                                        ?>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md-6 col-sm-6">
                            <h5 style="margin-top: 20px;">คะแนนการประเมินรายข้อ</h5>

                            <?php
                                if ($coursesID == 8) {
                                    $query_question = "SELECT * FROM questions WHERE questionsID >= 21 AND questionsID <= 30 ORDER BY questionsID";
                                } else if ($coursesID == 9) {
                                    $query_question = "SELECT * FROM questions WHERE questionsID >= 31 AND questionsID <= 37 ORDER BY questionsID";
                                } else {
                                    $query_question = "SELECT * FROM questions WHERE questionsID <= '$question_num' ORDER BY questionsID";
                                }
                                $results_question = mysqli_query($con, $query_question);
                                while ($result = mysqli_fetch_array($results_question)):
                            ?>
                            <div class="card" style="border-radius: 20px;">

                                <div class="card-body">
                                    <h5 class="card-title text-danger">ข้อ <?php if ($coursesID == 8) {echo ($result['questionsID'] - 20).". ".$result['content'];} else if ($coursesID == 9) {echo ($result['questionsID'] - 30).". ".$result['content'];} else {echo $result['questionsID'].". ".$result['content'];} ?></h5>
                                    <dd><?php echo $result['caption']; ?></dd>
                                    <!--ปุ่มเพื่อเปิด modal แสดงคะแนนรายข้อแบบละเอียด -->
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop<?php echo $result['questionsID']; ?>" style="border-radius: 20px;">
                                            More <i class="cil-chart"></i>
                                        </button>
                                    </div>

                                    <!-- modal รายละเอียดคะแนนรายข้อ -->
                                    <div class="modal fade" id="staticBackdrop<?php echo $result['questionsID']; ?>" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">
                                                        ข้อ <?php if ($coursesID == 8) {echo ($result['questionsID'] - 20).". ".$result['content'];} else if ($coursesID == 9) { echo ($result['questionsID'] - 30).". ".$result['content']; }  else {echo $result['questionsID'].". ".$result['content'];} ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php
                                                        $query_score = "SELECT
                                                        SUM(CASE WHEN score = 1 THEN 1 ELSE 0 END) AS score1,
                                                        SUM(CASE WHEN score = 2 THEN 1 ELSE 0 END) AS score2,
                                                        SUM(CASE WHEN score = 3 THEN 1 ELSE 0 END) AS score3,
                                                        SUM(CASE WHEN score = 4 THEN 1 ELSE 0 END) AS score4,
                                                        SUM(CASE WHEN score = 5 THEN 1 ELSE 0 END) AS score5
                                                        FROM assessment_score
                                                        WHERE coursesID = '$coursesID' AND questionID = '$result[questionsID]'
                                                        ORDER BY questionID";
                                                        $results_score = mysqli_query($con, $query_score);
                                                        $resultsc = mysqli_fetch_array($results_score);
                                                        $maxvote = max($resultsc['score1'], $resultsc['score2'], $resultsc['score3'], $resultsc['score4'], $resultsc['score5']);

                                                        for ($i = 1; $i <= 5; $i++):
                                                    ?>
                                                    <dd><?php echo $i; ?> คะแนน</dd>
                                                    <dd>
                                                        <div class="row">
                                                            <div class="col-8">
                                                                <div class="progress">
                                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                                        style="width: <?php $score = "score".$i; echo $resultsc[$score]/$maxvote*100; ?>%;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-4"><?php echo $resultsc[$score]; ?> people</div>
                                                        </div>
                                                    </dd>
                                                    <?php
                                                        endfor;
                                                    ?>
                                        
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                        

                    <?php } else if($coursesID == 8) { ?>
                    <h3 class="mt-2">การประเมินแบบเลือกคะแนน</h3>
                    <p style="color:#6e6e6e">[ เป็นการประเมินช่วง2สัปดาห์สุดท้ายก่อน/หลังจบภาคเรียน โดยนิสิตสามารถทำการประเมินได้เพียง1ครั้ง ]</p>
                    <hr>
                    <form action="assessment_subject_submit.php?id=<?php echo $coursesID; ?>" method="POST">
                        <!--คำถามที่1แบบเลือกคะเเนน-->

                        <label class="form-label"style="margin-bottom:0px;">1.การใช้งานเข้าเว็บไซต์เข้าใจง่ายหรือไม่ </label><br>
                        <label class="form-text" style="margin-top:0px;">1 คะแนน หมายถึง ไม่เข้าใจเลย 5 คะแนน หมายถึง เข้าใจง่ายที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>

                        <!--คำถามที่2แบบเลือกคะเเนน-->
                        <label class="form-label">2.หารายวิชาที่ลงทะเบียนเรียนในเทอมสะดวกหรือไม่</label><br>
                        <!-- <label class="form-text">1 คะแนน หมายถึง ไม่สะดวก 5 คะแนน หมายถึง สะดวก</label> -->
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q2" id="input-Q2-1" value="5" required>
                                <label class="form-check-label" for="inlineRadio1">สะดวก</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q2" id="input-Q2-5" value="1">
                                <label class="form-check-label" for="inlineRadio3">ไม่สะดวก</label>
                            </div>
                        </div>
                        <hr>
                        <!--คำถามที่3แบบเลือกคะเเนน-->
                        <label class="form-label">3.หากมีการใช้งานเว็บไซต์ future class นิสิตจะใช้งานเป็นประจำหรือไม่</label><br>
                        <label class="form-text">โดยหมายเลข 1 หมายถึงไม่ใช้เลย หมายเลข 5 หมายถึงใช้งานสม่ำเสมอ</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>

                        <!--คำถามที่4แบบเลือกคะเเนน-->
                        <label class="form-label">4.หน้าตาของเว็บไซต์มีความสวยงามระดับใด</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึงไม่สวยงามเลย หมายเลข 5
                            หมายถึงสวยงามที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q4" id="input-Q4-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q4" id="input-Q4-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q4" id="input-Q4-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q4" id="input-Q4-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q4" id="input-Q4-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>
                        <!--คำถามที่5แบบเลือกคะเเนน-->
                        <label class="form-label">5.นิสิตทำประเมินในระบบ CUCAS ครบหรือไม่</label><br>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">ไม่เคยประเมินเลย</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">ประเมินบางครั้ง</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">ประเมินทุกเทอม</label>
                            </div>
                        </div>
                        <hr>

                        <!--คำถามที่6แบบเลือกคะเเนน-->
                        <label class="form-label">6.นิสิตคิดว่าการใช้เว็บไซต์ Future class จะเพิ่มประสิทธิภาพในการเรียน การสอนหรือไม่</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึง ไม่เพิ่มเลย หมายเลข 5 หมายถึง เพิ่มมากที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>
                        <!--คำถามที่7แบบเลือกคะเเนน-->
                        <label class="form-label">7.นิสิตคิดว่าเว็บไซต์ Future class สามารถใช้ทดแทน application ในการติดต่อสื่อสารอื่นๆ ระหว่างอาจารย์และนิสิต เช่น line หรือไม่</label><br>
                        
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">ไม่สามารถทดแทนได้</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">ทดแทนได้บางส่วน</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">สามารถทดแทนได้</label>
                            </div>
                        </div>
                        <hr>

                        <!--คำถามที่8แบบเลือกคะเเนน-->
                        <label class="form-label">8.นิสิตคิดว่าระบบreport จะสามารถป้องกันนิสิตที่ต้องการก่อกวนได้หรือไม่</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึงไม่สามารถป้องกันได้ หมายเลข 5 หมายถึง ป้องกันได้มากที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q8" id="input-Q8-1" value="1">
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q8" id="input-Q8-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q8" id="input-Q8-3" value="3" required>
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q8" id="input-Q8-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q8" id="input-Q8-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>

                         <!--คำถามที่9แบบเลือกคะเเนน-->
                         <label class="form-label">9.ระบบนี้ทำให้นิสิตกล้าถามคำถามมากขึ้นหรือไม่</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึง น้อยที่สุด หมายเลข 5 หมายถึง มากที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q9" id="input-Q9-1" value="1">
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q9" id="input-Q9-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q9" id="input-Q9-3" value="3" required>
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q9" id="input-Q9-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q9" id="input-Q9-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>

                         <!--คำถามที่10แบบเลือกคะเเนน-->
                         <label class="form-label">10.การแสดงความเห็นแบบ Anonymous จะทำให้นิสิตแสดงความเห็นแบบตรงไปตรงมาหรือไม่</label><br>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q10" id="input-Q10-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">ใช่</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q10" id="input-Q10-2" value="0">
                                <label class="form-check-label" for="inlineRadio2">ไม่</label>
                            </div>
                        </div>
                        <hr>
                        <!--ส่งข้อมูล-->
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </form>
                <?php } else if($coursesID == 9) { ?>
                    <h3 class="mt-2">การประเมินแบบเลือกคะแนน</h3>
                    <hr>
                    <form action="assessment_subject_submit.php?id=<?php echo $coursesID; ?>" method="POST">
                        <!--คำถามที่1แบบเลือกคะเเนน-->

                        <label class="form-label">1.การใช้งานเข้าเว็บไซต์เข้าใจง่ายหรือไม่ </label><br>
                        <label class="form-text">1 คะแนน หมายถึง ไม่เข้าใจเลย 5 คะแนน หมายถึง เข้าใจง่ายที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>

                        <!--คำถามที่2แบบเลือกคะเเนน-->
                        <label class="form-label">2.หากมีการใช้งานเว็บไซต์ future class อาจารย์จะใช้งานเป็นประจำหรือไม่</label><br>
                        <label class="form-text">โดยหมายเลข 1 หมายถึงไม่ใช้เลย หมายเลข 5 หมายถึงใช้งานสม่ำเสมอ</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q2" id="input-Q2-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q2" id="input-Q2-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q2" id="input-Q2-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q2" id="input-Q2-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q2" id="input-Q2-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>

                        <!--คำถามที่4แบบเลือกคะเเนน-->
                        <label class="form-label">3.หน้าตาของเว็บไซต์มีความสวยงามระดับใด</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึงไม่สวยงามเลย หมายเลข 5 หมายถึงสวยงามที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>

                        <!--คำถามที่4แบบเลือกคะเเนน-->
                        <label class="form-label">4.สรุปคะแนนต่างๆในระบบเข้าใจง่ายหรือไม่ </label><br>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q4" id="input-Q4-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">เข้าใจง่าย</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q4" id="input-Q4-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">เข้าใจยาก</label>
                            </div>
                        </div>
                        <hr>
                    
                        <!--คำถามที่6แบบเลือกคะเเนน-->
                        <label class="form-label">5.อาจารย์คิดว่าการใช้เว็บไซต์ Future class จะเป็นเครื่องมือที่ป้อนกลับการเรียนการสอนได้เร็วกว่าและดีกว่าระบบ CU-CAS หรือไม่</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึง ไม่เพิ่มเลย หมายเลข 5 หมายถึง เพิ่มมากที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>
                        <!--คำถามที่7แบบเลือกคะเเนน-->
                        <label class="form-label">6.อาจารย์คิดว่าเว็บไซต์ Future class สามารถใช้ทดแทน application ในการติดต่อสื่อสารอื่นๆ ระหว่างอาจารย์และนิสิต เช่น line หรือไม่</label><br>
                        
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">ไม่สามารถทดแทนได้</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">ทดแทนได้บางส่วน</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">สามารถทดแทนได้</label>
                            </div>
                        </div>
                        <hr>

                        <!--คำถามที่8แบบเลือกคะเเนน-->
                        <label class="form-label">7.อาจารย์คิดว่าระบบช่วยให้มองเห็นภาพรวมของวิชามากขึ้นว่านิสิตส่วนมากมีความเห็นอย่างไรต่อวิชา</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึงไม่ช่วยเลย หมายเลข 5 หมายถึง ช่วยมากที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-1" value="1">
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>
                        <!--ส่งข้อมูล-->
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </form>
                <?php } else { ?>
                    <br>
                    <h3>การประเมินแบบเลือกคะแนน</h3>
                    <hr>
                    <form action="assessment_subject_submit.php?id=<?php echo $coursesID; ?>" method="POST">
                        <!--คำถามที่1แบบเลือกคะเเนน-->

                        <label class="form-label">1.ความเหมาะสมของปริมาณเนื้อหาต่อเวลาเรียน</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึงไม่เหมาะสมที่สุด หมายเลข 5
                            หมายถึงเหมาะสมที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q1" id="input-Q1-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>

                        <!--คำถามที่2แบบเลือกคะเเนน-->
                        <label class="form-label">2.เนื้อหาภายในห้องเรียนตรงกับที่ระบุไว้ในประมวลรายวิชา</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึงไม่เหมาะสมที่สุด หมายเลข 5
                            หมายถึงเหมาะสมที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q2" id="input-Q2-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q2" id="input-Q2-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q2" id="input-Q2-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q2" id="input-Q2-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q2" id="input-Q2-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>
                        <!--คำถามที่3แบบเลือกคะเเนน-->
                        <label class="form-label">3.ความยากง่ายของเนื้อหา</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึงง่ายที่สุด หมายเลข 5 หมายถึงยากที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q3" id="input-Q3-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>

                        <!--คำถามที่4แบบเลือกคะเเนน-->
                        <label class="form-label">4.ความเหมาะสมของปริมาณงานต่อเวลาที่กำหนด</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึงไม่เหมาะสมที่สุด หมายเลข 5
                            หมายถึงเหมาะสมที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q4" id="input-Q4-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q4" id="input-Q4-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q4" id="input-Q4-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q4" id="input-Q4-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q4" id="input-Q4-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>
                        <!--คำถามที่5แบบเลือกคะเเนน-->
                        <label class="form-label">5.ความเหมาะสมของรูปแบบการเรียนการสอน</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึงไม่เหมาะสมที่สุด หมายเลข 5
                            หมายถึงเหมาะสมที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q5" id="input-Q5-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>

                        <!--คำถามที่6แบบเลือกคะเเนน-->
                        <label class="form-label">6.ความยากง่ายของข้อสอบ</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึง ง่ายที่สุด หมายเลข 5 หมายถึง ยากที่สุด</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q6" id="input-Q6-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>
                        <!--คำถามที่7แบบเลือกคะเเนน-->
                        <label class="form-label">7.จำนวนครั้งในการสอบ</label><br>

                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">ไม่มีการสอบเลย</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">สอบน้อยเกินไป</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-3" value="3">
                                <label class="form-check-label" for="inlineRadio3">เหมาะสม</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">มาก</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q7" id="input-Q7-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">มากเกินไป</label>
                            </div>
                        </div>
                        <hr>

                        <!--คำถามที่8แบบเลือกคะเเนน-->
                        <label class="form-label">8.ความเข้าใจในเนื้อหาที่เรียนและสามารถนำไปประยุกต์ใช้ได้</label><br>
                        <label class="form-text">โดยหมายเลข1 หมายถึงน้อย หมายเลข 5 หมายถึง มาก</label>
                        <div class="form-check">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q8" id="input-Q8-1" value="1">
                                <label class="form-check-label" for="inlineRadio1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q8" id="input-Q8-2" value="2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q8" id="input-Q8-3" value="3" required>
                                <label class="form-check-label" for="inlineRadio3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q8" id="input-Q8-4" value="4">
                                <label class="form-check-label" for="inlineRadio3">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Q8" id="input-Q8-5" value="5">
                                <label class="form-check-label" for="inlineRadio3">5</label>
                            </div>
                        </div>
                        <hr>
                        <!--ส่งข้อมูล-->
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </form>
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