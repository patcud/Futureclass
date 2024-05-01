<?php
    include('../../dbconnect.php');
    
    if (!isset($_SESSION['email'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
    }

    $abbreviation = $_POST['abbreviation'];
    $title = $_POST['title'];
    $fname = $_POST['name'];
    $lname = $_POST['surname'];
    $mail = $_POST['email'];

    if (empty($abbreviation)) {
        $_SESSION['error'] = ["กรุณากรอก รหัสรายวิชา"];
        header('location: ../../home_admin_teacher.php');
    } else if (empty($title)) {
        $_SESSION['error'] = ["กรุณากรอก คำนำหน้า"];
        header('location: ../../home_admin_teacher.php');
    } else if (empty($fname)) {
        $_SESSION['error'] = ["กรุณากรอก ชื่อ"];
        header('location: ../../home_admin_teacher.php');
    } else if (empty($lname)) {
        $_SESSION['error'] = ["กรุณากรอก ชื่อนามสกุล"];
        header('location: ../../home_admin_teacher.php');
    } else if (empty($mail)) {
        $_SESSION['error'] = ["กรุณากรอก อีเมล"];
        header('location: ../../home_admin_teacher.php');
    } else {
        $sql = "SELECT teachersID,is_deleted FROM teachers WHERE abbreviation='$abbreviation'";
        $queryTeacher = mysqli_fetch_assoc(mysqli_query($con, $sql));

        if($queryTeacher > 0) {
            $id = $queryTeacher['teachersID'];
            $isDeleted = $queryTeacher['is_deleted'];
            $error[] = "มีอักษรย่อ(" . $abbreviation . ")อยู่ในระบบแล้ว กรุณาทำการแก้ไขข้อมูลรายบุคคลแทน";
            
            if ($isDeleted == 1) {
                $sql = "UPDATE teachers SET is_deleted=0 WHERE teachersID=$id";
                if(mysqli_query($con, $sql)) {
                    $warning[] = "teacher(id=" . $id . ") ได้ถูกนำกลับเข้าระบบ";
                } else {
                    $error[] = "มีข้อผิดพลาดเกิดขึ้นในการนำ teacher(id=" . $id . ") กลับเข้าระบบ :" . mysqli_error($con);
                }
            }

            $_SESSION['error'] = $error;
            $_SESSION['warning'] = $warning;
            $_SESSION['success'] = $success;
            header('location: ../../home_admin_teacher.php');
        }else{
            $sql = "SELECT COUNT(*) AS user_check FROM user WHERE userEmail=$mail";
            $queryUserCheck = mysqli_fetch_array(mysqli_query($con, $sql));
            $userCheck = $queryUserCheck['user_check'];

            if($userCheck > 0) {
                $error[] = "มีอีเมลนี้อยู่ในระบบแล้ว";
                header('location: ../../home_admin_teacher.php');
            } else {
                $sql = "SELECT MAX(CAST(teachersID AS UNSIGNED)) as max_id FROM teachers";
                $queryMaxID = mysqli_fetch_assoc(mysqli_query($con, $sql));
                $id = $queryMaxID['max_id']+1;

                $sql = "INSERT INTO user (userEmail,id,password,role)
                        VALUES ('$mail','$id','a1b2c3','teacher')";
                if(mysqli_query($con, $sql)) {
                    $success[] = "user(id=" . $id . ") ได้ถูกเพิ่มเข้าระบบ";
                } else {
                    $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม user(id=" . $id . ")ใหม่ :" . mysqli_error($con);
                }
            }

            $sql = "INSERT INTO teachers (teachersID,abbreviation,fname,lname,title)
                    VALUES ('$id','$abbreviation','$fname','$lname','$title')";
            if(mysqli_query($con, $sql)) {
                $success[] = "teacher(id=" . $id . ") ได้ถูกเพิ่มเข้าระบบ";
            } else {
                $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม teacher(id=" . $id . ")ใหม่ :" . mysqli_error($con);
            }
        }

        $_SESSION['error'] = $error;
        $_SESSION['warning'] = $warning;
        $_SESSION['success'] = $success;

        if ($_SESSION['userlevel'] == "admin") { 
            header('location: ../../home_admin_teacher.php');
        }
    }
?>