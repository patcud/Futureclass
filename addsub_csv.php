<?php
    include('dbconnect.php');
    
    if(isset($_POST["upload"])) {
    
        $filename=$_FILES["file"]["tmp_name"];    
        
        if($_FILES["file"]["size"] > 0) {
            $file = fopen($filename, "r");
            $header = fgetcsv($file);
            while (($row = fgetcsv($file)) !== FALSE) {
                $data = array_combine($header, $row);
                $subjectsID = $row[0];
                $section = $data['section'];
                $abbreviation = $data['Instructor code'];
                
                $sql = "SELECT COUNT(*) AS sub_check FROM subjects WHERE subjectsID=$subjectsID";
                $querySubCheck = mysqli_fetch_array(mysqli_query($con, $sql));
                $subCheck = $querySubCheck['sub_check'];
                
                if($subCheck == 0) {
                    $error[] = "ไม่มีวิชา(id=" . $subjectsID . ") อยู่ในระบบ";
                    continue;
                }

                $sql = "SELECT COUNT(*) AS course_check FROM courses WHERE subjectsID=$subjectsID AND semesterID=$semID";
                $queryCourseCheck = mysqli_fetch_array(mysqli_query($con, $sql));
                $courseCheck = $queryCourseCheck['course_check'];
                
                if($courseCheck == 0) {
                    $sql = "INSERT INTO courses (registotal,semesterID,subjectsID,password) VALUES (120,'$semID','$subjectsID','123')";
                    if(mysqli_query($con, $sql)) {
                        $success[] = "วิชา(id=" . $subjectsID . ") ได้ถูกเพิ่มแล้ว";
                    } else {
                        $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม วิชา(id=" . $subjectsID . ") :" . mysqli_error($con);
                    }
                }

                $sql = "SELECT COUNT(*) AS teacher_check FROM teachers WHERE abbreviation='$abbreviation'";
                $queryTeacherCheck = mysqli_fetch_array(mysqli_query($con, $sql));
                $teacherCheck = $queryTeacherCheck['teacher_check'];
                
                if($teacherCheck == 0) {
                    $error[] = "ไม่พบอาจารย์(Abbr=" . $abbreviation .") อยู่ในระบบ";
                    continue;
                } else {
                    $sql = "SELECT * FROM teachers WHERE abbreviation='$abbreviation'";
                    $rteach = mysqli_fetch_array(mysqli_query($con, $sql));
                    $teachID = $rteach['teachersID'];
                    
                    $sql = "SELECT * FROM courses WHERE subjectsID=$subjectsID AND semesterID=$semID";
                    $rsub = mysqli_fetch_array(mysqli_query($con, $sql));
                    $cid = $rsub['coursesID'];

                    $sql = "SELECT COUNT(*) AS teacher_course_check FROM teacher_course WHERE teachersID = $teachID AND section = $section AND coursesID=$cid";
                    $queryTeacherCourseCheck = mysqli_fetch_array(mysqli_query($con, $sql));
                    $teacherCourseCheck = $queryTeacherCourseCheck['teacher_course_check'];

                    if($teacherCourseCheck == 0) {
                        $sql = "INSERT into teacher_course (teachersID,coursesID,section,status) 
                                values ('$teachID','$cid','$section','1')";
                        if(mysqli_query($con, $sql)) {
                            $success[] = "อาจารย์(Abbr=" . $abbreviation . ") ได้ถูกเพิ่มในวิชา(id=" . $subjectsID . ") แล้ว";
                        } else {
                            $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่มอาจารย์(Abbr=" . $abbreviation . ") ในวิชา(id=" . $subjectsID . "):" . mysqli_error($con);
                        }
                    } else {
                        $warning[] = "มีอาจารย์(Abbr=" . $abbreviation . ") อยู่ในวิชา(id=" . $subjectsID . ") แล้ว";
                    }
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