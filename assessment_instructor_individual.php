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
    
    if (isset($_GET['id'])) {
        $coursesID = $_GET['id'];
    }

    if(isset($_GET['tid'])) {
        // set $id เป็นรหัสอาจารย์
        $id = $_GET['tid'];
    }
    // set $id เป็นรหัสแอดมิน
    //$id = $_SESSION['userid'];

    $sql = "SELECT *
            FROM courses
            LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
            WHERE coursesID=$coursesID";
    $result = mysqli_fetch_array(mysqli_query($con,$sql));
    $coursename = $result['nameEN'];
    $sql2 = "SELECT * FROM requestment WHERE coursesID=$coursesID ORDER BY timestamp DESC";
    $r = mysqli_query($con, $sql2);

    $sql = "SELECT * FROM courses WHERE coursesID='$coursesID'";
    $courses = mysqli_fetch_array(mysqli_query($con, $sql));

    // force $question_num = 7 for teacher assessment
    $question_num = 7;

    // query for pie chart
    $query_pie =    "SELECT
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
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="assessment_instructor_individual.php?id=<?php echo $coursesID; ?>&tid=<?php echo $_GET['tid']; ?>">คะแนน</a>
                        </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="instructor_q1_admin.php?id=<?php echo $coursesID; ?>&tid=<?php echo $_GET['tid']; ?>">ข้อดี</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="instructor_q2_admin.php?id=<?php echo $coursesID; ?>&tid=<?php echo $_GET['tid']; ?>">ข้อเสนอแนะ</a>
                    </li>
                </ul>
                <br>
                <!--card แสดงข้อมูลรายละเอียดรายวิชาและการประเมิน ฝั่งด้านซ้าย-->
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-6">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                            <h3>สรุปผลการประเมินอาจารย์</h3>
                            <?php
                                $qcheckassess = "SELECT COUNT(*) FROM tassessment_score
                                WHERE tassessmentID IN (SELECT tassessmentID FROM tassessment WHERE coursesID = '$coursesID' AND teachersID = '$id')";
                                $checkassess = mysqli_fetch_array(mysqli_query($con, $qcheckassess));
                                if ($checkassess[0] != 0):        
                            ?>
                            <form method='post' action = 'save_chart_to_pdf.php?id=<?php echo $coursesID; ?>&tid=<?php echo $id; ?>' id='savePDFForm'>
                                <input type='hidden' id='htmlContentHidden' name='htmlContent' value=''>
                                <button type='submit' id="downloadBtn" class="btn btn-dark" style="border-radius: 10px;">
                                    Print PDF
                                    <i class="cil-print"></i>
                                </button>
                            </form>                           
                            <?php
                                endif;
                            ?>
                        </div>

                        <?php

                        $assesscount = mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) FROM tassessment_count WHERE coursesID=$coursesID AND teachersID = '$id' AND status = 1"))[0];
                        $goodcount = mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) FROM teachers_good WHERE coursesID=$coursesID AND teachersID='$id' AND qtype='good' AND status = 0"));
                        $goodnow = $goodcount[0];
                        $suggestcount = mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) FROM teachers_good WHERE coursesID=$coursesID AND teachersID='$id' AND qtype='suggestion' AND status = 0"));
                        $suggestnow = $suggestcount[0];
                            
                        ?>

                        <div class="card" style="border-radius: 20px; margin-bottom: 20px;">
                            <div class="card-body">
                                <h5 class="card-title text-danger">Overview</h5>
                                <dl class="row">
                                    <dt class="col-sm-6">รหัสประจำตัวผู้สอน</dt>
                                    <dd class="col-sm-6">&nbsp;&nbsp;<span><?php echo $id; ?></span></dd>

                                    <dt class="col-sm-6">ชื่อผู้สอน</dt>
                                    <dd class="col-sm-6">&nbsp;&nbsp;<span><?php echo $_SESSION['user']; ?></span></dd>

                                <?php if ($courses['sectiontype'] == "แยก section") {
                                    $teachersec = mysqli_fetch_array(mysqli_query($con,"SELECT section FROM teacher_course WHERE coursesID=$coursesID AND teachersID = $id"))[0];
                                    $secregis = mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) FROM student_course WHERE coursesID=$coursesID AND section = $teachersec AND status='1'"))[0];
                                    $regisnow = $secregis;
                                ?>

                                    <dt class="col-sm-6">จำนวนนิสิตภายใน section</dt>
                                    <dd class="col-sm-6">&nbsp;&nbsp;<span><?php echo $secregis; ?></span></dd>

                                    <dt class="col-sm-6">จำนวนนิสิตที่ทำการประเมินผู้สอน</dt>
                                    <dd class="col-sm-6">&nbsp;&nbsp;<span><?php echo $assesscount; ?></span></dd>

                                    <dt class="col-sm-6">จำนวนข้อดีต่อผู้สอน</dt>
                                    <dd class="col-sm-6">&nbsp;&nbsp;<span><?php echo $goodnow; ?></span></dd>

                                    <dt class="col-sm-6">จำนวนข้อเสนอแนะต่อผู้สอน</dt>
                                    <dd class="col-sm-6">&nbsp;&nbsp;<span><?php echo $suggestnow; ?></span></dd>
                                
                                <?php } ?>
                                <?php if ($courses['sectiontype'] == "รวม section") {
                                    $regisnow = mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) FROM student_course WHERE coursesID=$coursesID AND status='1'"))[0];
                                ?>

                                    <dt class="col-sm-6">จำนวนนิสิตที่ลงทะเบียน</dt>
                                    <dd class="col-sm-6">&nbsp;&nbsp;<span><?php echo $regisnow."/".$courses['registotal']; ?></span></dd>

                                    <dt class="col-sm-6">จำนวนนิสิตที่ทำการประเมินผู้สอน</dt>
                                    <dd class="col-sm-6">&nbsp;&nbsp;<span><?php echo $assesscount; ?></span></dd>

                                    <dt class="col-sm-6">จำนวนข้อดีต่อผู้สอน</dt>
                                    <dd class="col-sm-6">&nbsp;&nbsp;<span><?php echo $goodnow; ?></span></dd>

                                    <dt class="col-sm-6">จำนวนข้อเสนอแนะต่อผู้สอน</dt>
                                    <dd class="col-sm-6">&nbsp;&nbsp;<span><?php echo $suggestnow; ?></span></dd>
                                
                                <?php } ?>
                                </dl>
                            </div>
                        </div>

                        <div class="card" style="border-radius: 20px;">
                            <div class="card-body">
                                <h5 class="card-title text-danger">คะแนนเฉลี่ยการประเมินแบบเลือกคำตอบ</h5>
                                <dl class="row">
                                    <?php
                                        $query_av_score = "SELECT tquestionsID AS questionNo,
                                                            AVG(score) AS score_average
                                                            FROM tassessment_score
                                                            WHERE tassessmentID IN (SELECT tassessmentID FROM tassessment WHERE coursesID = '$coursesID' AND teachersID = '$id')
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

                        <div class="card" style="border-radius: 20px;">
                            <div class="card-body">
                                <h5 class="card-title text-danger">กราฟคะแนนเฉลี่ยจากการประเมิน</h5>
                                <?php
                                    $checkresultsav = mysqli_query($con, $query_av_score);
                                    if (mysqli_num_rows($checkresultsav) > 0):
                                ?>
                                <p id="column_chart_divav" width="400px" height="350px"></p>
                                <?php
                                    else:
                                ?>
                                <p align="center">-</p>
                                <?php
                                    endif;
                                ?>
                            </div>
                        </div>


                        
                    </div>

                    <br>
                    <!--card แสดงกราฟการประเมิน ฝั่งครึ่งหน้าขวา-->
                    <!--รายละเอียดคะแนนข้อต่างๆ-->
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
                                                    $qcheckassess = "SELECT COUNT(*) FROM tassessment_score
                                                                        WHERE tassessmentID IN (SELECT tassessmentID FROM tassessment WHERE coursesID = '$coursesID' AND teachersID = '$id')";
                                                    $checkassess = mysqli_fetch_array(mysqli_query($con, $qcheckassess));
                                                    if ($checkassess[0] == 0):
                                                ?>
                                                <p align="center">ขณะนี้ยังไม่มีนิสิตที่ทำการประเมิน</p>
                                                <?php
                                                    else:
                                                        $query_score = "SELECT
                                                                        SUM(CASE WHEN score = 1 THEN 1 ELSE 0 END) AS score1,
                                                                        SUM(CASE WHEN score = 2 THEN 1 ELSE 0 END) AS score2,
                                                                        SUM(CASE WHEN score = 3 THEN 1 ELSE 0 END) AS score3,
                                                                        SUM(CASE WHEN score = 4 THEN 1 ELSE 0 END) AS score4,
                                                                        SUM(CASE WHEN score = 5 THEN 1 ELSE 0 END) AS score5
                                                                        FROM tassessment_score
                                                                        WHERE tassessmentID IN (SELECT tassessmentID FROM tassessment WHERE coursesID = '$coursesID' AND teachersID = '$id')
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
                                                    endif;
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
            </div>
            <!-- จบ : ส่วนข้อมูลด้านข้างเมนูย่อย -->

        <!-- จบ : ส่วนข้อมูลภายในเว็บล่างเมนูหลักด้านบน -->
        </div>
    <!-- จบ : ส่วนข้อมูลภายในเว็บ -->
    <div id="pie_chart_div" style="width: 33.33%; display: none;"></div>
    <?php for ($i = 1; $i <= $question_num; $i++): ?>
        <div id="column_chart_div<?php echo $i; ?>" style="width: 33.33%; display: none;"></div>
    <?php endfor; ?>

    </div>

    <!-- **** -->
    <!-- รันแจ้งเตือน -->
    <?php include('components/noti_admin.php') ?>
    <!-- จบ : รันแจ้งเตือน -->

</body>

</html>

<script>
    <?php
        
        $qcoursename = "SELECT *
                        FROM courses
                        LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
                        WHERE coursesID = '$coursesID'";
        $rcoursename = mysqli_fetch_array(mysqli_query($con, $qcoursename));
    
        $subjectsID = $rcoursename['subjectsID'];
        $coursename = $rcoursename['nameEN'];
        $coursesnameTH = $rcoursename['nameTH'];

        $teachername = $_SESSION['user'];
        if ($_SESSION['userlevel'] == 'admin') {
            $rtname = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM teachers WHERE teachersID = '$id'"));
            $teachername = $rtname['title'].$rtname['fname']." ".$rtname['lname'];
        }

        $qGoodNum = "SELECT COUNT(*) FROM teachers_good
                        WHERE coursesID = '$coursesID' AND teachersID = '$id' AND qtype = 'good'";
        $rGoodNum = mysqli_fetch_array(mysqli_query($con, $qGoodNum));
        $goodNum = $rGoodNum[0];
        
        $qSugNum = "SELECT COUNT(*) FROM teachers_good
                        WHERE coursesID = '$coursesID' AND teachersID = '$id' AND qtype = 'suggestion'";
        $rSugNum = mysqli_fetch_array(mysqli_query($con, $qSugNum));
        $sugNum = $rSugNum[0];
        
    ?>
jQuery(document).ready(function() {
    jQuery("#downloadBtn").on("click", function() {
        
        var htmlContent = '<head>';
        htmlContent += '<style>';
        //htmlContent += 'body {font-family: 'Sarabun', sans-serif;}';
        htmlContent += '.center {display: block; margin-left: auto; margin-right: auto; width: 50%;}';
        htmlContent += '.left {float: left; width: 50%; margin: 0 15px 0 0;}';
        htmlContent += '* {box-sizing: border-box;}';
        htmlContent += '.column {float: left; width: 33%;}';
        htmlContent += '.row::after {content: ""; clear: both; display: table;}';
        htmlContent += 'table, th, td {border: 0.5px solid black; border-collapse: collapse;}';
        htmlContent += '</style>';
        htmlContent += '</head>';

        htmlContent += '<body>';

        htmlContent += '<table align="center" style="width: 750px;">';
        htmlContent += '<tr>';
        htmlContent += '<th align="right" style="border: none;" rowspan="3"> <img src="./images/full-logo-b.png" width="90" height="80"></th>';
        htmlContent += '<th align="center" style="height: 60px; font-size: 40px; border: none;">';
        htmlContent += 'รายงานสรุปผลการประเมินอาจารย์';
        htmlContent += '</th>';
        htmlContent += '</tr>';
        htmlContent += '<tr>';
        htmlContent += '<td align="center" style="height: 60px; font-size: 36px; border: none;">';
        htmlContent += '<?php echo "สำหรับ ".$teachername." ในการเรียนการสอนรายวิชา" ?>';
        htmlContent += '</td>';
        htmlContent += '</tr>';
        htmlContent += '<tr>';
        htmlContent += '<td align="center" style="height: 60px; font-size: 36px; border: none;">';
        htmlContent += '<?php echo $subjectsID.' '.$coursesnameTH; ?>';
        htmlContent += '</td>';
        htmlContent += '</tr>';

        htmlContent += '</table>';

        htmlContent += '<div align="right" style="font-size: 20px; color: grey;">พิมพ์เมื่อวันที่ <?php echo $today; ?></div>';
        htmlContent += '<br>';
        
        htmlContent += '<div class="row">';

        htmlContent += '<div style="float: left; width: 49.5%;">';
        htmlContent += '<div style="font-size: 22px; font-weight: bold;">เปอร์เซ็นต์ของนิสิตที่ทำการประเมิน</div>';
        htmlContent += jQuery("#pie_chart_div").html();
        htmlContent += '</div>';

        htmlContent += '<div style="float: left; width: 49.5%;">';
        htmlContent += '<div style="font-size: 22px; font-weight: bold;">คะแนนเฉลี่ยของคำถามทั้งหมด</div>';
        htmlContent += jQuery("#column_chart_divav").html();
        htmlContent += '</div>';

        htmlContent += '</div>';

        htmlContent += '<div class="row">';

        htmlContent += '<div style="float: left; width: 49.5%;">';
        htmlContent += '<div style="font-size: 22px; font-weight: bold;">ภาพรวมการประเมินอาจารย์</div>';
        htmlContent += '</div>';

        htmlContent += '</div>';

        htmlContent += '<div class="row">';

        htmlContent += '<table style="width: 100%;">';

        htmlContent += '<tr>';
        htmlContent += '<td align="left" style="font-size: 18px">จำนวนนิสิตที่ลงทะเบียน</td>';
        htmlContent += '<td align="center" style="font-size: 18px"><?php echo $regisnow; ?></td>';
        htmlContent += '</tr>';

        htmlContent += '<tr>';
        htmlContent += '<td align="left" style="font-size: 18px">จำนวนนิสิตที่ทำการประเมิน</td>';
        htmlContent += '<td align="center" style="font-size: 18px"><?php echo $assesscount; ?> (<?php echo round($assesscount/$regisnow*100, 2); ?>%)</td>';
        htmlContent += '</tr>';

        htmlContent += '<tr>';
        htmlContent += '<td align="left" style="font-size: 18px">จำนวนข้อดีต่อผู้สอน</td>';
        htmlContent += '<td align="center" style="font-size: 18px"><?php echo $goodNum; ?></td>';
        htmlContent += '</tr>';

        htmlContent += '<tr>';
        htmlContent += '<td align="left" style="font-size: 18px">จำนวนข้อเสนอแนะต่อผู้สอน</td>';
        htmlContent += '<td align="center" style="font-size: 18px"><?php echo $sugNum; ?></td>';
        htmlContent += '</tr>';


        htmlContent += '</table>';

        htmlContent += '</div>';

        htmlContent += '<br>';

        htmlContent += '<div class="row">';
        htmlContent += '<div style="font-size: 22px; font-weight: bold; page-break-before: always;">ผลการประเมินรายข้อ</div>';
        htmlContent += '</div>';

        var i = 1;

        <?php
            
            $scoreav2 = array();
            $query_av_score2 = "SELECT tquestionsID AS questionNo,
            AVG(score) AS score_average
            FROM tassessment_score
            WHERE tassessmentID IN (SELECT tassessmentID FROM tassessment WHERE coursesID = '$coursesID' AND teachersID = '$id')
            GROUP BY questionNo
            ORDER BY questionNo";
            $resultsav2 = mysqli_query($con, $query_av_score2);
            
            if (mysqli_num_rows($resultsav2) > 0) {
                
                while ($rowav = mysqli_fetch_array($resultsav2)) {
                    $scoreav2[] = round($rowav['score_average'],2);
                }        
                
            }
            
            $m = 0;

            // query only question 1-7
            
            $qquestion = "SELECT * FROM tquestions WHERE tquestionsID <= 7";
            $rquestions = mysqli_query($con, $qquestion);
            while ($row = mysqli_fetch_array($rquestions)):
            
        ?>

        htmlContent += '<div class="row" ';
        <?php if ($m != 0 && $m % 2 == 0): ?>
            htmlContent += 'style="page-break-before: always;"';
        <?php endif; ?>
        htmlContent += '>';

        htmlContent += '<div style="float: left; width: 66%; font-size: 22px;">';
        htmlContent += 'คำถามที่ <?php echo $row["tquestionsID"]." - ".$row["content"]; ?>';
        htmlContent += '</div>';

        htmlContent += '<br>';

        htmlContent += '<div style="float: left; width: 66%; font-size: 20px;">';
        htmlContent += '<?php echo $row["caption"]; ?>';
        htmlContent += '<br>';
        htmlContent += 'คะแนนเฉลี่ยจากการประเมิน <?php echo $scoreav2[$m]; ?> คะแนน';
        htmlContent += '</div>';

        htmlContent += '<div class="column">';
        htmlContent += '<div style="width: 100%;">';
        htmlContent += jQuery("#column_chart_div" + i).html();
        htmlContent += '</div>';
        htmlContent += '</div>';

        htmlContent += '</div>';

        htmlContent += '<br>';

        i += 1;

        <?php
            $m ++;
            endwhile;
        ?>

        // ตารางข้อดี

        htmlContent += '<div class="row" style="page-break-before: always;">';
        htmlContent += '<p align="left" style="font-size: 22px; font-weight: bold;">';
        htmlContent += 'ข้อดีของการเรียนการสอน';
        htmlContent += '</p>';
        htmlContent += '</div>';

        htmlContent += '<table style="width: 100%;">';
        htmlContent += '<tr>';
        htmlContent += '<th align="center" style="font-size: 18px;">';
        htmlContent += 'ข้อดีของการเรียนการสอน';
        htmlContent += '</th>';
        htmlContent += '<th align="center" width="150" style="font-size: 18px;">';
        htmlContent += 'จำนวนผู้เห็นด้วย';
        htmlContent += '</th>';
        htmlContent += '<th align="center" width="150" style="font-size: 18px;">';
        htmlContent += 'จำนวนผู้ไม่เห็นด้วย';
        htmlContent += '</th>';
        htmlContent += '</tr>';

        <?php
            
            $qgood = "SELECT * FROM teachers_good
                        WHERE coursesID = '$coursesID' AND teachersID = '$id' AND qtype = 'good'
                        ORDER BY likeCount DESC";
            $resultsgood = mysqli_query($con, $qgood);
            if (mysqli_num_rows($resultsgood) > 0):
                while ($row = mysqli_fetch_array($resultsgood)):
            
        ?>

        htmlContent += '<tr>';
        htmlContent += '<td align="left" style="font-size: 18px;"><?php echo $row['context']; ?></td>';
        htmlContent += '<td align="center" style="font-size: 18px;"><?php echo $row['likeCount']; ?></td>';
        htmlContent += '<td align="center" style="font-size: 18px;"><?php echo $row['dislikeCount']; ?></td>';
        htmlContent += '</tr>';

        <?php
                endwhile;
            else:
        ?>
        
        htmlContent += '<tr>';
        htmlContent += '<td align="center" style="font-size: 18px;">- ยังไม่มีผู้แสดงความคิดเห็นในหัวข้อนี้ -</td>';
        htmlContent += '<td align="center" style="font-size: 18px;">-</td>';
        htmlContent += '<td align="center" style="font-size: 18px;">-</td>';
        htmlContent += '</tr>';

        <?php
            endif;
        ?>

        htmlContent += '</table>';

        htmlContent += '<br>';

        // ตารางข้อเสนอแนะ

        htmlContent += '<div class="row">';
        htmlContent += '<p align="left" style="font-size: 22px; font-weight: bold;">';
        htmlContent += 'ข้อเสนอแนะจากการประเมินอาจารย์';
        htmlContent += '</p>';
        htmlContent += '</div>';

        htmlContent += '<table style="width: 100%;">';
        htmlContent += '<tr>';
        htmlContent += '<th align="center" style="font-size: 18px;">';
        htmlContent += 'ข้อเสนอแนะจากการประเมินอาจารย์';
        htmlContent += '</th>';
        htmlContent += '<th align="center" width="150" style="font-size: 18px;">';
        htmlContent += 'จำนวนผู้เห็นด้วย';
        htmlContent += '</th>';
        htmlContent += '<th align="center" width="150" style="font-size: 18px;">';
        htmlContent += 'จำนวนผู้ไม่เห็นด้วย';
        htmlContent += '</th>';
        htmlContent += '</tr>';

        <?php
            
            $qgood = "SELECT * FROM teachers_good
                        WHERE coursesID = '$coursesID' AND teachersID = '$id' AND qtype = 'suggestion'
                        ORDER BY likeCount DESC";
            $resultsgood = mysqli_query($con, $qgood);
            if (mysqli_num_rows($resultsgood) > 0):
                while ($row = mysqli_fetch_array($resultsgood)):
            
        ?>

        htmlContent += '<tr>';
        htmlContent += '<td align="left" style="font-size: 18px;"><?php echo $row['context']; ?></td>';
        htmlContent += '<td align="center" style="font-size: 18px;"><?php echo $row['likeCount']; ?></td>';
        htmlContent += '<td align="center" style="font-size: 18px;"><?php echo $row['dislikeCount']; ?></td>';
        htmlContent += '</tr>';

        <?php
                endwhile;
            else:
        ?>

        htmlContent += '<tr>';
        htmlContent += '<td align="center" style="font-size: 18px;">- ยังไม่มีผู้แสดงความคิดเห็นในหัวข้อนี้ -</td>';
        htmlContent += '<td align="center" style="font-size: 18px;">-</td>';
        htmlContent += '<td align="center" style="font-size: 18px;">-</td>';
        htmlContent += '</tr>';
        
        <?php
            endif;
        ?>

        htmlContent += '</table>';



        htmlContent += '</body>';


        /*
        htmlContent += jQuery("#pie_chart_div").html();
        htmlContent += jQuery("#column_chart_divav").html();
        htmlContent += jQuery("#column_chart_div1").html();
        htmlContent += jQuery("#column_chart_div2").html();
        htmlContent += jQuery("#column_chart_div3").html();
        */

        jQuery("#htmlContentHidden").val(htmlContent);

        // submit the form

        jQuery('#savePDFForm').submit();

    });
});
</script>