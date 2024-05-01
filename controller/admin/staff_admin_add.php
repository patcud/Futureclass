<?php
    include('../../dbconnect.php');
    
    $coursesID = $_GET['coursesID'];
    $teachersID = $_POST['teachersID'];
    $section = $_POST['section'];

    if (empty($section)) {
        $_SESSION['error'] = ["กรุณากรอก section"];
        header('location: ../../staff_admin.php?id='.$coursesID.'');
    } else {
        $sql = "INSERT INTO teacher_course (teachersID,coursesID,section,status) VALUES ('$teachersID',$coursesID,'$section',1)";
        if(mysqli_query($con,$sql)) {
            $success[]="อาจารย์(id=" . $teachersID . ") ได้ถูกเพิ่มในวิชาเรียบร้อย";
        } else {
            $error[]="มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม อาจารย์(id=" . $teachersID . ") :" . mysqli_error($con);
        }

        $sql = "INSERT INTO tassessment_count (teachersID,userEmail,status,coursesID)
                SELECT '$teachersID',u.userEmail,'0','$coursesID'
                FROM student_course
                LEFT JOIN (
                    SELECT userEmail,id
                    FROM user) u
                ON student_course.studentsID = u.id
                WHERE coursesID=$coursesID";
        if(mysqli_query($con,$sql)) {
            $success[]="เพิ่มการประเมินอาจารย์(id=" . $teachersID . ") ในแบบประเมินของนิสิตแล้ว";
        } else {
            $error[]="มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม การประเมินอาจารย์(id=" . $teachersID . ") :" . mysqli_error($con);
        }
        
        $_SESSION['error'] = $error;
        $_SESSION['success'] = $success;

        if ($_SESSION['userlevel'] == "admin") { 
            header('location: ../../staff_admin.php?id='.$coursesID.'');
        }
    }
?>