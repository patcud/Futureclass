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
                        <a class="nav-link active" aria-current="page" href="assessment_instructor_student.php?id=<?php echo($coursesID) ?>&tid=<?php echo $teacherID; ?>">คะแนน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="instructor_q1.php?id=<?php echo($coursesID) ?>&tid=<?php echo $teacherID; ?>">ข้อดี</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="instructor_q2.php?id=<?php echo($coursesID) ?>&tid=<?php echo $teacherID; ?>">ข้อเสนอแนะ</a>
                    </li>
                </ul>
                <?php
                    $check = mysqli_fetch_array(mysqli_query($con,"SELECT status FROM tassessment_count WHERE coursesID = $coursesID AND userEmail = '$_SESSION[email]' AND teachersID = '$teacherID'"));
                    $sql = "SELECT section FROM student_course WHERE coursesID=$coursesID AND studentsID=$id";
                    $student_section = mysqli_fetch_array(mysqli_query($con, $sql))[0];
                    $sql2 = "SELECT section FROM teacher_course WHERE coursesID=$coursesID AND teachersID=$teacherID";
                    $teacher_section = mysqli_fetch_array(mysqli_query($con, $sql2))[0];
                    if ($result['sectiontype'] == "แยก section" && $student_section != $teacher_section){ ?>
                <h3 class="mt-2">ไม่สามารถทำการประเมินได้</h3>
                <hr>
                <?php } else if(isset($check) && ($check[0] == 1)) { ?>
                <h3 class="mt-2">ขอบคุณสำหรับการประเมิน</h3>
                <hr>
                <?php 
                    // force $question_num = 7 for teacher assessment
    $question_num = 7;

    // query for pie chart
    $query_pie = "SELECT
    CASE
    WHEN status = 1 THEN 'นิสิตที่ทำการประเมินแล้ว'
    WHEN status = 0 THEN 'นิสิตที่ยังไม่ทำการประเมิน'
    END AS status,
    COUNT(userEmail) AS number
    FROM tassessment_count
    WHERE coursesID = '$coursesID' AND teachersID = '$id'
    GROUP BY status;";
    $results_pie = mysqli_query($con, $query_pie);
    $pie_chart_data = array();
    while ($result = mysqli_fetch_array($results_pie)) {
    $pie_chart_data[] = array($result['status'], (int)$result['number']);
    }
    $pie_chart_data = json_encode($pie_chart_data);

    // query for average scroe
    $query_av_score = "SELECT tquestionsID AS questionNo,
    AVG(score) AS score_average
    FROM tassessment_score
    WHERE tassessmentID IN (SELECT tassessmentID FROM tassessment WHERE coursesID = '$coursesID' AND teachersID = '$id')
    GROUP BY questionNo
    ORDER BY questionNo";
    $resultsav = mysqli_query($con, $query_av_score);
    $column_chart_dataav = array(array("Question No.", "คะแนนเฉลี่ย"));
    while ($result = mysqli_fetch_array($resultsav)) {
    $column_chart_dataav[] = array("ข้อ ".$result['questionNo'], round($result['score_average'],2));
    }
    $column_chart_dataav = json_encode($column_chart_dataav);

    $today = date("l jS \of F Y h:i A");
    
echo<<<XYZ
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
            chartArea:{left:30,top:30,width:'100%',height:'80%'}};

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

XYZ;

for ($i = 1; $i <= $question_num; $i++) {

    // query for bar chart(s)
    $query_score_vote = "SELECT
    SUM(CASE WHEN score = 1 THEN 1 ELSE 0 END) AS score1,
    SUM(CASE WHEN score = 2 THEN 1 ELSE 0 END) AS score2,
    SUM(CASE WHEN score = 3 THEN 1 ELSE 0 END) AS score3,
    SUM(CASE WHEN score = 4 THEN 1 ELSE 0 END) AS score4,
    SUM(CASE WHEN score = 5 THEN 1 ELSE 0 END) AS score5
    FROM tassessment_score
    WHERE tassessmentID IN (SELECT tassessmentID FROM tassessment WHERE coursesID = '$coursesID' AND teachersID = '$id')
    AND tquestionsID = ".$i;
    $results_score_count = mysqli_query($con, $query_score_vote);
    $resultscc = mysqli_fetch_array($results_score_count);
    $column_chart_data = array(array("Score", "Score Count"));
    for ($j = 1; $j <= 5; $j++) {
        $score = "score".$j;
        $column_chart_data[] = array($j." คะแนน", round($resultscc[$score], 2));
    }
    $column_chart_data = json_encode($column_chart_data);

echo<<<PQR
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
        title: 'ผลการประเมินจากคำถามข้อที่ $i',
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

PQR;

}
    
?>
                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-6">
                            <div class="card" style="border-radius: 20px;">
                                <div class="card-body">
                                    <h5 class="card-title text-danger">คะแนนเฉลี่ยการประเมินแบบเลือกคำตอบ</h5>
                                    <dl class="row">
                                        <?php
                                            $query_av_score = "SELECT tquestionsID AS questionNo,
                                            AVG(score) AS score_average
                                            FROM tassessment_score
                                            WHERE tassessmentID IN (SELECT tassessmentID FROM tassessment WHERE coursesID = '$coursesID' AND teachersID = '$_GET[tid]')
                                            GROUP BY questionNo
                                            ORDER BY questionNo";
                                            $resultsav = mysqli_query($con, $query_av_score);
                                            if (mysqli_num_rows($resultsav) > 0):
                                                while ($result = mysqli_fetch_array($resultsav)):
                                        ?>
                                        <dt class="col-sm-3">ข้อที่ <?php echo $result['questionNo']; ?></dt>
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
                                $query_question = "SELECT * FROM tquestions WHERE tquestionsID <= '$question_num' ORDER BY tquestionsID";
                                $results_question = mysqli_query($con, $query_question);
                                while ($result = mysqli_fetch_array($results_question)):
                            ?>
                            <div class="card" style="border-radius: 20px;">

                                <div class="card-body">
                                    <h5 class="card-title text-danger">ข้อ <?php echo $result['tquestionsID'].". ".$result['content']; ?></h5>
                                    <dd><?php echo $result['caption']; ?></dd>
                                    <!--ปุ่มเพื่อเปิด modal แสดงคะแนนรายข้อแบบละเอียด -->
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop<?php echo $result['tquestionsID']; ?>" style="border-radius: 20px;">
                                            More <i class="cil-chart"></i>
                                        </button>
                                    </div>

                                    <!-- modal รายละเอียดคะแนนรายข้อ -->
                                    <div class="modal fade" id="staticBackdrop<?php echo $result['tquestionsID']; ?>" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">
                                                        ข้อ <?php echo $result['tquestionsID'].". ".$result['content']; ?></h5>
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
                                                            FROM tassessment_score
                                                            WHERE tassessmentID IN (SELECT tassessmentID FROM tassessment WHERE coursesID = '$coursesID' AND teachersID = '$_GET[tid]')
                                                            AND tquestionsID = '$result[tquestionsID]'
                                                            ORDER BY tquestionsID";
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
                <?php } else { ?>
                <h3 class="mt-2">การประเมินแบบเลือกคะแนนของ <?php echo $tname; ?></h3>
                <p style="color:#6e6e6e">[ เป็นการประเมินช่วง2สัปดาห์สุดท้ายก่อน/หลังจบภาคเรียน โดยนิสิตสามารถทำการประเมินได้เพียง1ครั้ง ]</p>
                <hr>
                <!--form action="แสดงว่าจะlimkผลการกรอกฟอร์มไปที่ไหน"-->
                <form action="assessment_instructor_submit.php?id=<?php echo $coursesID; ?>&tid=<?php echo $teacherID ?>" method="POST">
                    <!-- คำถามที่1แบบเลือกคะเเนน
                    <!--คำถามที่ 1 แบบ btn radio group-->
                    <label class="form-label">1.อาจารย์ผู้สอนมีความสามารถในการถ่ายทอดความรู้</label><br>
                    <label class="form-text">โดยหมายเลข1 หมายถึงไม่มีความสามารถ หมายเลข 5 หมายถึงมีความสามารถมากที่สุด</label>
                    <br>
                    <br>
                    <div class="btn-group">
                        <input type="radio" class="btn-check" name="Q1" id="input-Q1-1" value="1" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q1-1">1</label>

                        <input type="radio" class="btn-check" name="Q1" id="input-Q1-2" value="2"  autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q1-2">2</label>

                        <input type="radio" class="btn-check" name="Q1" id="input-Q1-3" value="3" autocomplete="off" checked/>
                        <label class="btn btn-danger" for="input-Q1-3">3</label>

                        <input type="radio" class="btn-check" name="Q1" id="input-Q1-4" value="4" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q1-4">4</label>

                        <input type="radio" class="btn-check" name="Q1" id="input-Q1-5" value="5" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q1-5">5</label>
                    </div>
                    <hr>

                    <!--คำถามที่ 2 แบบ btn radio group-->
                    <label class="form-label">2.ความตรงต่อเวลาของผู้สอน</label><br>
                    <label class="form-text">โดยหมายเลข1 หมายถึง น้อย หมายเลข 5 หมายถึง มาก</label>
                    <br>
                    <div class="btn-group">
                        <input type="radio" class="btn-check" name="Q2" id="input-Q2-1" value="1" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q2-1">1</label>

                        <input type="radio" class="btn-check" name="Q2" id="input-Q2-2" value="2" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q2-2">2</label>

                        <input type="radio" class="btn-check" name="Q2" id="input-Q2-3" value="3" autocomplete="off" checked/>
                        <label class="btn btn-danger" for="input-Q2-3">3</label>

                        <input type="radio" class="btn-check" name="Q2" id="input-Q2-4" value="4" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q2-4">4</label>

                        <input type="radio" class="btn-check" name="Q2" id="input-Q2-5" value="5" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q2-5">5</label>
                    </div>
                    <hr>

                    <!--คำถามที่ 3 แบบ btn radio group-->
                    <label class="form-label">3.ผู้สอนจัดเตรียมสื่อการเรียนการสอนได้อย่างเหมาะสม</label><br>
                    <label class="form-text">โดยหมายเลข1 หมายถึงง่ายที่สุด หมายเลข 5 หมายถึงยากที่สุด</label>
                    <br>
                    <div class="btn-group">
                        <input type="radio" class="btn-check" name="Q3" id="input-Q3-1" value="1" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q3-1">1</label>

                        <input type="radio" class="btn-check" name="Q3" id="input-Q3-2" value="2" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q3-2">2</label>

                        <input type="radio" class="btn-check" name="Q3" id="input-Q3-3" value="3" autocomplete="off" checked/>
                        <label class="btn btn-danger" for="input-Q3-3">3</label>

                        <input type="radio" class="btn-check" name="Q3" id="input-Q3-4" value="4" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q3-4">4</label>

                        <input type="radio" class="btn-check" name="Q3" id="input-Q3-5" value="5" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q3-5">5</label>
                    </div>
                    <hr>

                    <!--คำถามที่ 4 แบบ btn radio group-->
                    <label class="form-label">4.ความสอดคล้องของเนื้อหาภายในห้องเรียนและคำถามในข้อสอบ</label><br>
                    <label class="form-text">โดยหมายเลข1 หมายถึง ไม่สอดคล้อง หมายเลข 5 หมายถึง สอดคล้อง</label>
                    <br>
                    <div class="btn-group">
                        <input type="radio" class="btn-check" name="Q4" id="input-Q4-1" value="1" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q4-1">1</label>

                        <input type="radio" class="btn-check" name="Q4" id="input-Q4-2" value="2" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q4-2">2</label>

                        <input type="radio" class="btn-check" name="Q4" id="input-Q4-3" value="3" autocomplete="off" checked />
                        <label class="btn btn-danger" for="input-Q4-3">3</label>

                        <input type="radio" class="btn-check" name="Q4" id="input-Q4-4" value="4" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q4-4">4</label>

                        <input type="radio" class="btn-check" name="Q4" id="input-Q4-5" value="5" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q4-5">5</label>
                    </div>
                    <hr>

                    <!--คำถามที่ 5 แบบ btn radio group-->
                    <label class="form-label">5.ผู้สอนรับฟังความคิดเห็นของนิสิตและดำเนินการตามความเหมาะสม</label><br>
                    <label class="form-text">โดยหมายเลข1 หมายถึง น้อย หมายเลข 5 หมายถึง มาก</label>
                    <br>
                    <div class="btn-group">
                        <input type="radio" class="btn-check" name="Q5" id="input-Q5-1" value="1" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q5-1">1</label>

                        <input type="radio" class="btn-check" name="Q5" id="input-Q5-2" value="2" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q5-2">2</label>

                        <input type="radio" class="btn-check" name="Q5" id="input-Q5-3" value="3" autocomplete="off" checked />
                        <label class="btn btn-danger" for="input-Q5-3">3</label>

                        <input type="radio" class="btn-check" name="Q5" id="input-Q5-4" value="4" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q5-4">4</label>

                        <input type="radio" class="btn-check" name="Q5" id="input-Q5-5" value="5" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q5-5">5</label>
                    </div>
                    <hr>

                    <!--คำถามที่ 6 แบบ btn radio group-->
                    <label class="form-label">6.สามารถติดต่อกับอาจารย์ผู้สอนได้เมื่อมีคำถามหรือปัญหาในการเรียน</label><br>
                    <label class="form-text">โดยหมายเลข1 หมายถึง ง่ายที่สุด หมายเลข 5 หมายถึง ยากที่สุด</label>
                    <br>
                    <div class="btn-group">
                        <input type="radio" class="btn-check" name="Q6" id="input-Q6-1" value="1" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q6-1">1</label>

                        <input type="radio" class="btn-check" name="Q6" id="input-Q6-2" value="2" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q6-2">2</label>

                        <input type="radio" class="btn-check" name="Q6" id="input-Q6-3" value="3" autocomplete="off" checked />
                        <label class="btn btn-danger" for="input-Q6-3">3</label>

                        <input type="radio" class="btn-check" name="Q6" id="input-Q6-4" value="4" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q6-4">4</label>

                        <input type="radio" class="btn-check" name="Q6" id="input-Q6-5" value="5" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q6-5">5</label>
                    </div>
                    <hr>

                    <!--คำถามที่ 7 แบบ btn radio group-->
                    <label class="form-label">7.บรรยากาศภายในห้องเรียนเหมาะสมกับการเรียนรู้</label><br>
                    <label class="form-text">โดยหมายเลข1 หมายถึงน้อย หมายเลข 5 หมายถึง มาก</label>
                    <br>
                    <div class="btn-group">
                        <input type="radio" class="btn-check" name="Q7" id="input-Q7-1" value="1" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q7-1">1</label>

                        <input type="radio" class="btn-check" name="Q7" id="input-Q7-2" value="2" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q7-2">2</label>

                        <input type="radio" class="btn-check" name="Q7" id="input-Q7-3" value="3" autocomplete="off" checked />
                        <label class="btn btn-danger" for="input-Q7-3">3</label>

                        <input type="radio" class="btn-check" name="Q7" id="input-Q7-4" value="4" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q7-4">4</label>

                        <input type="radio" class="btn-check" name="Q7" id="input-Q7-5" value="5" autocomplete="off" />
                        <label class="btn btn-danger" for="input-Q7-5">5</label>
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