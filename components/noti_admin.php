<?php
    if($notinum != 0) {
?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11" data-bs-autohide="false">
        <div aria-live="assertive" aria-atomic="true" class="position-relative">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive"
                aria-atomic="true" >
                <button type="button" class="btn-close ms-2 mb-1 position-absolute end-0" 
                    style="background-color: rgb(219, 219, 219);" data-bs-dismiss="toast"
                    aria-label="close"></button>
                <?php
                    $query = "SELECT * FROM notifications_admin WHERE needAction = 1 ORDER BY adminNotiID DESC";
                    $results = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_array($results)):
                ?>
                <!-- แจ้งเตือน Admin เมื่อมีคน report (กดปุ่ม flag) ข้อเรียกร้อง (request) -->
                <?php
                    if ($row['type'] == 'request'):
                    $querycname = " SELECT * 
                                    FROM courses
                                    LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
                                    WHERE coursesID IN (SELECT coursesID FROM requestment WHERE requestmentID = '$row[ID]')";
                    $resultcname = mysqli_fetch_array(mysqli_query($con, $querycname));              
                ?>
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true"
                    data-bs-autohide="false" style="border-radius: 10px; background-color: whitesmoke; margin-top: 20px;">
                    <a href="request_admin.php?id=<?php echo $resultcname['coursesID']; ?>&rid=<?php echo $row['ID']; ?> "
                        style="text-decoration: none;">
                        <div class="toast-header" style="border-radius: 10px;background-color: rgb(161, 16,16);">
                            <img src="read_subject_pic.php?rid=<?php echo $row['ID']; ?>"
                                width="35px" height="35px" class="rounded me-2" alt="...">
                            <div class="me-auto" style="font-weight:bold; color: whitesmoke;">
                                <?php echo $resultcname['nameEN']; ?>
                            </div>
                            <small style="color: whitesmoke;">
                                <?php
                                    $qtimestamp = "SELECT timestamp FROM request_report WHERE rID = '$row[ID]'";
                                    $timestamp = mysqli_fetch_array(mysqli_query($con, $qtimestamp));
                                    //echo date('F j, Y, g:i a', strtotime($timestamp[0]));
                                    $pasttime = time() - strtotime($timestamp[0]);
                                    if ($pasttime >= 60*60*24*547.5) {
                                        $pt = round(($pasttime)/60/60/24/365);
                                        echo $pt." years ago";
                                    } elseif ($pasttime >= 60*60*24*365) {
                                        echo "a year ago";
                                    } elseif ($pasttime >= 60*60*24*45) {
                                        $pt = round(($pasttime)/60/60/24/30);
                                        echo $pt." months ago";
                                    } elseif ($pasttime >= 60*60*24*30) {
                                        echo "a month ago";
                                    } elseif ($pasttime >= 60*60*24*10.5) {
                                        $pt = round(($pasttime)/60/60/24/7);
                                        echo $pt." weeks ago";
                                    } elseif ($pasttime >= 60*60*24*7) {
                                        echo "a week ago";
                                    } elseif ($pasttime >= 60*60*36) {
                                        $pt = round(($pasttime)/60/60/24);
                                        echo $pt." days ago";
                                    } elseif ($pasttime >= 60*60*24) {
                                        echo "a day ago";
                                    } elseif ($pasttime >= 60*90) {
                                        $pt = round(($pasttime)/60/60);
                                        echo $pt." hours ago";
                                    } elseif ($pasttime >= 60*60) {
                                        echo "1 hour ago";
                                    } elseif ($pasttime >= 90) {
                                        $pt = round(($pasttime)/60);
                                        echo $pt." mins ago";
                                    } elseif ($pasttime >= 60) {
                                        echo "1 min ago";
                                    } else {
                                        echo "a moment ago";
                                    }    
                                ?>
                            </small>
                        </div>
                        <div class="toast-body" style="font-weight:bold; color: black;">
                            <?php
                                $sql = "SELECT * FROM requestment WHERE requestmentID = '$row[ID]'";
                                $result = mysqli_fetch_array(mysqli_query($con, $sql));
                                $req = $result['topic'];
                                echo "Someone reported this request: ".$req;
                            ?>
                        </div>
                    </a>
                </div>
                <?php endif; ?>
                
                <!-- แจ้งเตือน Admin เมื่อมีคน report (กดปุ่ม flag) ความคิดเห็น (ข้อดี ปัญหา ข้อเสนอแนะ) ในการประเมินรายวิชา (Subject Assessment) -->
                <?php if ($row['type'] == 'assess'): ?>
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true"
                    data-bs-autohide="false" style="border-radius: 10px; background-color: whitesmoke; margin-top: 20px;">
                    <a href="report_assessment.php" style="text-decoration: none;">
                        <div class="toast-header" style="border-radius: 10px;background-color: rgb(161, 16,16);">
                            <img src="read_subject_pic.php?qid=<?php echo $row['ID']; ?>"
                                width="35px" height="35px" class="rounded me-2" alt="...">
                            <div class="me-auto" style="font-weight:bold; color: whitesmoke;">
                                <?php
                                    $querycname = " SELECT * 
                                                    FROM courses 
                                                    LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
                                                    WHERE coursesID IN (SELECT coursesID FROM courses_good WHERE ID = '$row[ID]')";
                                    $resultcname = mysqli_fetch_array(mysqli_query($con, $querycname));
                                    $cname = $resultcname['nameEN'];
                                    echo $cname;
                                ?>
                            </div>
                            <small style="color: whitesmoke;">
                                <?php
                                    $qtimestamp = "SELECT timestamp FROM courses_good_report WHERE ID = '$row[ID]'";
                                    $timestamp = mysqli_fetch_array(mysqli_query($con, $qtimestamp));
                                    //echo date('F j, Y, g:i a', strtotime($timestamp[0]));
                                    $pasttime = time() - strtotime($timestamp[0]);
                                    if ($pasttime >= 60*60*24*547.5) {
                                        $pt = round(($pasttime)/60/60/24/365);
                                        echo $pt." years ago";
                                    } elseif ($pasttime >= 60*60*24*365) {
                                        echo "a year ago";
                                    } elseif ($pasttime >= 60*60*24*45) {
                                        $pt = round(($pasttime)/60/60/24/30);
                                        echo $pt." months ago";
                                    } elseif ($pasttime >= 60*60*24*30) {
                                        echo "a month ago";
                                    } elseif ($pasttime >= 60*60*24*10.5) {
                                        $pt = round(($pasttime)/60/60/24/7);
                                        echo $pt." weeks ago";
                                    } elseif ($pasttime >= 60*60*24*7) {
                                        echo "a week ago";
                                    } elseif ($pasttime >= 60*60*36) {
                                        $pt = round(($pasttime)/60/60/24);
                                        echo $pt." days ago";
                                    } elseif ($pasttime >= 60*60*24) {
                                        echo "a day ago";
                                    } elseif ($pasttime >= 60*90) {
                                        $pt = round(($pasttime)/60/60);
                                        echo $pt." hours ago";
                                    } elseif ($pasttime >= 60*60) {
                                        echo "1 hour ago";
                                    } elseif ($pasttime >= 90) {
                                        $pt = round(($pasttime)/60);
                                        echo $pt." mins ago";
                                    } elseif ($pasttime >= 60) {
                                        echo "1 min ago";
                                    } else {
                                        echo "a moment ago";
                                    }
                                ?>
                            </small>

                        </div>
                        <div class="toast-body" style="font-weight:bold; color: black;">
                                <?php
                                    $sql = "SELECT * FROM courses_good WHERE ID = '$row[ID]'";
                                    $result = mysqli_fetch_array(mysqli_query($con, $sql));
                                    $qtype = $result['qtype'];
                                    echo "Someone reported ";
                                    if ($qtype == 'good') {
                                        echo "a good point of this subject.";
                                    } else if ($qtype == 'problem') {
                                        echo "a problem in subject assessment.";
                                    } else if ($qtype == 'suggestion') {
                                        echo "a suggestion in subject assessment.";
                                    }
                                ?>
                        </div>
                    </a>
                </div>
                
                <?php endif; ?>

                <!-- แจ้งเตือน Admin เมื่อมีคน report (กดปุ่ม flag) ความคิดเห็น (ข้อดี ปัญหา ข้อเสนอแนะ) ในการประเมินรายวิชา (Subject Assessment) -->
                <?php if ($row['type'] == 'tassess'): ?>
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true"
                    data-bs-autohide="false" style="border-radius: 10px; background-color: whitesmoke; margin-top: 20px;">
                    <a href="report_assessment.php" style="text-decoration: none;">
                        <div class="toast-header" style="border-radius: 10px;background-color: rgb(161, 16,16);">
                            <img src="read_subject_pic.php?tqid=<?php echo $row['ID']; ?>"
                                width="35px" height="35px" class="rounded me-2" alt="...">
                            <div class="me-auto" style="font-weight:bold; color: whitesmoke;">
                                <?php
                                    $querycname = " SELECT * 
                                                    FROM courses 
                                                    LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
                                                    WHERE coursesID IN (SELECT coursesID FROM teachers_good WHERE ID = '$row[ID]')";
                                    $resultcname = mysqli_fetch_array(mysqli_query($con, $querycname));
                                    $cname = $resultcname['nameEN'];
                                    echo $cname;
                                ?>
                            </div>
                            <small style="color: whitesmoke;">
                            <?php
                                    $qtimestamp = "SELECT timestamp FROM teachers_good_report WHERE ID = '$row[ID]'";
                                    $timestamp = mysqli_fetch_array(mysqli_query($con, $qtimestamp));
                                    //echo date('F j, Y, g:i a', strtotime($timestamp[0]));
                                    $pasttime = time() - strtotime($timestamp[0]);
                                    if ($pasttime >= 60*60*24*547.5) {
                                        $pt = round(($pasttime)/60/60/24/365);
                                        echo $pt." years ago";
                                    } elseif ($pasttime >= 60*60*24*365) {
                                        echo "a year ago";
                                    } elseif ($pasttime >= 60*60*24*45) {
                                        $pt = round(($pasttime)/60/60/24/30);
                                        echo $pt." months ago";
                                    } elseif ($pasttime >= 60*60*24*30) {
                                        echo "a month ago";
                                    } elseif ($pasttime >= 60*60*24*10.5) {
                                        $pt = round(($pasttime)/60/60/24/7);
                                        echo $pt." weeks ago";
                                    } elseif ($pasttime >= 60*60*24*7) {
                                        echo "a week ago";
                                    } elseif ($pasttime >= 60*60*36) {
                                        $pt = round(($pasttime)/60/60/24);
                                        echo $pt." days ago";
                                    } elseif ($pasttime >= 60*60*24) {
                                        echo "a day ago";
                                    } elseif ($pasttime >= 60*90) {
                                        $pt = round(($pasttime)/60/60);
                                        echo $pt." hours ago";
                                    } elseif ($pasttime >= 60*60) {
                                        echo "1 hour ago";
                                    } elseif ($pasttime >= 90) {
                                        $pt = round(($pasttime)/60);
                                        echo $pt." mins ago";
                                    } elseif ($pasttime >= 60) {
                                        echo "1 min ago";
                                    } else {
                                        echo "a moment ago";
                                    }
                                ?>
                            </small>

                        </div>
                        <div class="toast-body" style="font-weight:bold; color: black;">
                                <?php
                                    $sql = "SELECT * FROM teachers_good WHERE ID = '$row[ID]'";
                                    $result = mysqli_fetch_array(mysqli_query($con, $sql));
                                    $qtype = $result['qtype'];

                                    $sql2 = "SELECT * FROM teachers WHERE teachersID IN (SELECT teachersID FROM teachers_good WHERE ID = '$row[ID]')";
                                    $result2 = mysqli_fetch_array(mysqli_query($con, $sql2));
                                    $tname = $result2['title'].$result2['fname']." ".$result2['lname'];

                                    echo "Someone reported ";
                                    if ($qtype == 'good') {
                                        echo "a good point of ".$tname;
                                    } else if ($qtype == 'suggestion') {
                                        echo "a suggestion in instructor assessment of ".$tname;
                                    }
                                ?>
                        </div>
                    </a>
                </div>

                <?php endif; ?>

                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <script>
    <?php if ($notinum != 0): ?>
        var toastTrigger = document.getElementById("liveToastBtn")
        var toastLiveExample = document.getElementById("liveToast")

        if (toastTrigger) {
            toastTrigger.addEventListener("click", function () {
                new bootstrap.Toast(toastLiveExample).show()
            })
        }
    <?php endif; ?>
    </script>