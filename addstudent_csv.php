<?php
    include('dbconnect.php');
    
    if(isset($_POST["upload"])) {

        $sid = $_POST["add_student_sub"];
        $sql = "SELECT * FROM courses WHERE subjectsID='$sid' and semesterID=$semID";
        $rsub = mysqli_fetch_array(mysqli_query($con,$sql));
        $cid = $rsub['coursesID'];
    
        $filename=$_FILES["file"]["tmp_name"];    
        
        if($_FILES["file"]["size"] > 0) {
            $file = fopen($filename, "r");
            $header = fgetcsv($file);
            while (($row = fgetcsv($file)) !== FALSE) {
                $data = array_combine($header, $row);
                $studentName = explode(" ",str_replace('"', '', $data['Name_en']));
                $title = substr($studentName[0],0,3);
                $id = $data['StudentID'];
                $fname = substr($studentName[0],3);
                $lname = $studentName[1];
                $sec = $data['sec'];
                $mail = $data['email1'];

                $queryStudentCourseCheck = "SELECT COUNT(1) AS student_course_check FROM student_course WHERE studentsID = $id AND coursesID=$cid";
                $resultStudentCourseCheck = mysqli_fetch_array(mysqli_query($con, $queryStudentCourseCheck));
                $studentCourseCheck = $resultStudentCourseCheck['student_course_check'];

                if($studentCourseCheck == 0) {
                    $queryStudentCheck = "SELECT COUNT(1) AS student_check FROM students WHERE studentsID=$id";
                    $resultStudentCheck = mysqli_fetch_array(mysqli_query($con, $queryStudentCheck));
                    $studentCheck = $resultStudentCheck['student_check'];

                    if($studentCheck == 0) {
                        $queryUserCheck = "SELECT COUNT(1) AS user_check FROM user WHERE id=$id";
                        $resultUserCheck = mysqli_fetch_array(mysqli_query($con, $queryUserCheck));
                        $userCheck = $resultUserCheck['user_check'];

                        if($userCheck == 0) {
                            $sql = "INSERT INTO user (userEmail,id,password,role)
                                    VALUES ('$mail','$id','a1b2c3','student')";
                            if(mysqli_query($con, $sql)) {
                                $success[] = "user(id=" . $id . ") ได้ถูกเพิ่มเข้าระบบ";
                            } else {
                                $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม user(id=" . $id . ")ใหม่ :" . mysqli_error($con);
                            }
                        }

                        $sql = "INSERT INTO students (studentsID,fname,lname,title)
                                VALUES ('$id','$fname','$lname','$title')";
                        if(mysqli_query($con, $sql)) {
                            $success[] = "student(id=" . $id . ") ได้ถูกเพิ่มเข้าระบบ";
                        } else {
                            $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม student(id=" . $id . ")ใหม่ :" . mysqli_error($con);
                        }
                    }
                    
                    $sql = "INSERT into student_course (studentsID,coursesID,section,status) 
                            values ('$id','$cid','$sec','1')";
                    if(mysqli_query($con, $sql)) {
                        $success[] = "student_course(id=" . $id . ") ได้ถูกเพิ่มเข้าระบบ";
                    } else {
                        $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม student_course(id=" . $id . ")ใหม่ :" . mysqli_error($con);
                    }

                    $queryAssessmentCheck = "SELECT status FROM assessment_count WHERE coursesID='$cid' AND userEmail='$_SESSION[email]'";
                    $resultAssessmentCheck = mysqli_fetch_array(mysqli_query($con, $queryAssessmentCheck));
                    $assessmentCheck = $resultAssessmentCheck['status'];
                    if ($assessmentCheck != 1) {
                        $sql = "REPLACE assessment_count SET coursesID='$cid',userEmail='$mail',semesterPeriod='$semID',status=0";
                        if(mysqli_query($con, $sql)) {
                            $success[] = "assessment_count(id=" . $id . ") ได้ถูกเพิ่มเข้าระบบ";
                        } else {
                            $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม assessment_count(id=" . $id . ") :" . mysqli_error($con);
                        }
                        
                        $sql = "INSERT INTO tassessment_count (teachersID,userEmail,status,coursesID)
                                SELECT teachersID,'$mail','0','$cid'
                                FROM teacher_course
                                WHERE coursesID='$cid' AND section = '$sec' AND status = 1";
                        if(mysqli_query($con, $sql)) {
                            $success[] = "tassessment_count(id=" . $id . ") ได้ถูกเพิ่มเข้าระบบ";
                        } else {
                            $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม tassessment_count(id=" . $id . ") :" . mysqli_error($con);
                        }
                    }

                    $queryNotiCheck = "SELECT status FROM noti_count WHERE coursesID='$cid'";
                    $resultNotiCheck = mysqli_fetch_array(mysqli_query($con, $queryNotiCheck));
                    $notiCheck = $resultNotiCheck['status'];
                    if ($notiCheck != 1) {
                        $sql = "REPLACE notifications SET coursesID='$cid', userID='$id', type= 'assess', requestmentID= '0', message= '', status= 'unread', date= CURRENT_TIMESTAMP";
                        if(mysqli_query($con, $sql)) {
                            $success[] = "notification ประเมินวิชา(id=" . $id . ") ได้ถูกเพิ่มเข้าระบบ";
                        } else {
                            $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม notification ประเมินวิชา(id=" . $id . ") :" . mysqli_error($con);
                        }
                        
                        $sql = "REPLACE notifications SET coursesID='$cid', userID='$id', type= 'tassess', requestmentID= '0', message= '', status= 'unread', date= CURRENT_TIMESTAMP";
                        if(mysqli_query($con, $sql)) {
                            $success[] = "notification ประเมินอาจารย์(id=" . $id . ") ได้ถูกเพิ่มเข้าระบบ";
                        } else {
                            $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม notification ประเมินอาจารย์(id=" . $id . ") :" . mysqli_error($con);
                        }
                    }

                } else {
                    $warning[] = "มีนักเรียน(id=" . $id . ") อยู่ในวิชา(id=" . $sid . ") แล้ว";
                }
            }
            fclose($file);  
        }
    }

    $_SESSION['error'] = $error;
    $_SESSION['warning'] = $warning;
    $_SESSION['success'] = $success;
            
    if ($_SESSION['userlevel'] == "admin") { 
        header('location: home_admin.php');
    }
?>