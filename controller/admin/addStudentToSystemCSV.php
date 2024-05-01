<?php
    include('../../dbconnect.php');
    
    if(isset($_POST["upload"])) {
        $filename=$_FILES["file"]["tmp_name"];    
        
        if($_FILES["file"]["size"] > 0) {
            $file = fopen($filename, "r");
            $flag = true;
            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
                if($flag) { $flag = false; continue; }

                $studentName = explode(" ",$getData[3]);
                $title = substr($studentName[0],0,3);
                $id = $getData[1];
                $fname = substr($studentName[0],3);
                $lname = $studentName[1];
                $mail = $id . "@student.chula.ac.th";

                $sql = "SELECT COUNT(*) AS student_check FROM students WHERE studentsID=$id";
                $queryStudentCheck = mysqli_fetch_assoc(mysqli_query($con, $sql));
                $studentCheck = $queryStudentCheck['student_check'];

                if($studentCheck == 0) {
                    $sql = "SELECT COUNT(*) AS user_check FROM user WHERE id=$id";
                    $queryUserCheck = mysqli_fetch_array(mysqli_query($con, $sql));
                    $userCheck = $queryUserCheck['user_check'];

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
            }
            fclose($file);  
        }

        if(!isset($success)){
            $error[] = "มีข้อมูลนักเรียนอยู่แล้ว";
        }
    }

    $_SESSION['error'] = $error;
    $_SESSION['success'] = $success;
            
    if ($_SESSION['userlevel'] == "admin") { 
        header('location: ../../home_admin_student.php');
    }
?>