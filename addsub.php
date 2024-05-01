<?php

    include('dbconnect.php');

    if (!isset($_SESSION['email'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
    }

    $subjectsID = $_POST['subjectcode'];
    $nameTH = $_POST['subjectnameTH'];
    $nameENG = $_POST['subjectnameENG'];
    $credit = $_POST['credit'];
    $regis = $_POST['courseregistotal'];
    $sec = $_POST['sectiontype'];
    $class = $_POST['subjectclass'];
    $active = $_POST['sem'];
    $content = $_POST['subjectcontent'];
    $password = $_POST['coursepassword'];

    if (empty($subjectsID)) {
        $_SESSION['error'] = ["กรุณากรอก รหัสรายวิชา"];
        header('location: home_admin.php');
    } else if (!filter_var($subjectsID, FILTER_VALIDATE_INT)) {
        $_SESSION['error'] = ["กรุณากรอก รหัสรายวิชาเป็นตัวเลข"];
        header('location: home_admin.php');
    } else if (empty($nameTH)) {
        $_SESSION['error'] = ["กรุณากรอก ชื่อวิชาภาษาไทย"];
        header('location: home_admin.php');
    } else if (empty($nameENG)) {
        $_SESSION['error'] = ["กรุณากรอก ชื่อวิชาภาษาอังกฤษ"];
        header('location: home_admin.php');
    } else if (empty($credit)) {
        $_SESSION['error'] = ["กรุณากรอก จำนวนหน่วยกิต"];
        header('location: home_admin.php');
    } else if (empty($regis)) {
        $_SESSION['error'] = ["กรุณากรอก จำนวนนิสิตที่เปิดรับ"];
        header('location: home_admin.php');
    } else if (empty($active)) {
        $_SESSION['error'] = ["กรุณาเลือก ภาคการเรียนที่เปิดสอน"];
        header('location: home_admin.php');
    } else if (empty($content)) {
        $_SESSION['error'] = ["กรุณากรอก คำอธิบายรายวิชา"];
        header('location: home_admin.php');
    } else if (empty($password)) {
        $_SESSION['error'] = ["กรุณากรอก รหัสสำหรับลงธะเบียน"];
        header('location: home_admin.php');
    } else {
        // เพิ่ม subjectsID ใน table courses และ subjects
        $sql = "SELECT COUNT(*) AS course_check FROM courses WHERE subjectsID=$subjectsID AND semesterID=$semID";
        $queryCourseCheck = mysqli_fetch_array(mysqli_query($con, $sql));
        $courseCheck = $queryCourseCheck['course_check'];

        if ($courseCheck > 0) {
            $error[] = "มีวิชาเรียนอยู่ในระบบแล้ว กรุณาเพิ่มวิชาเรียนผ่านการอัพโหลดไฟล์ CSV";
            header('location: home_admin.php');
        } else {
            $sql = "SELECT COUNT(*) AS subCheck FROM subjects WHERE subjectsID=$subjectsID";
            $querySubCheck = mysqli_fetch_array(mysqli_query($con, $sql));
            $subCheck = $querySubCheck['subCheck'];
            
            if($subCheck == 0) {
                $sql = "INSERT INTO subjects (subjectsID,nameTH,nameEN,content,class,credit) 
                        VALUES ('$subjectsID','$nameTH','$nameENG','$content','$class','$credit')";
                if(mysqli_query($con, $sql)) {
                    $success[] = "วิชา(id=" . $subjectsID . ") ได้ถูกลงทะเบียนแล้ว";
                } else {
                    $error[] = "มีข้อผิดพลาดเกิดขึ้นในการลงทะเบียน วิชา(id=" . $subjectsID . ") :" . mysqli_error($con);
                }
            }
            $sql = "INSERT INTO courses (registotal,semesterID,subjectsID,sectiontype,password) 
                    VALUES ('$regis','$semID','$subjectsID','$sec','$password')";
            if(mysqli_query($con, $sql)) {
                $success[] = "วิชา(id=" . $subjectsID . ") ได้ถูกเพิ่มแล้ว";
            } else {
                $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม วิชา(id=" . $subjectsID . ") :" . mysqli_error($con);
            }
        }

        // เพิ่ม picture ใน table courses
        if (isset($_FILES['file'])) {
            $afile = $_FILES['file']['tmp_name'];
            $type = $_FILES['file']['type'];

            if ($_FILES['file']['error'] == '0') {
                $file = addslashes(file_get_contents($afile));
                $sql= "UPDATE courses SET picture='$file',pictype='$type' 
                        WHERE subjectsID='$subjectsID' AND semesterID=$semID";
                $result = mysqli_query($con,$sql);
            } 
        }
        
        // ใส่ actionperiod table subjects
        if (isset($active[0])) {
            if (isset($active[1])) {
                if (isset($active[2])) {
                    $allsem = $active[0].",".$active[1].",".$active[2];
                } else {
                    $allsem = $active[0].",".$active[1];
                }
            } else $allsem = $active[0]; 
            $sql= "UPDATE courses SET actionperiod = '$allsem' 
                    WHERE subjectsID='$subjectsID' AND semesterID=$semID";
            $result = mysqli_query($con,$sql);
        }

        $_SESSION['error'] = $error;
        $_SESSION['success'] = $success;

        if ($_SESSION['userlevel'] == "admin") { 
            header('location: home_admin.php');
        }
    }

?>