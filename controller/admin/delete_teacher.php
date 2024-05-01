<?php
    include('../../dbconnect.php');

    if(isset($_POST['delete_teacher'])) {
        $abbr = $_POST['delete_teacher'];
        
        $sql = "UPDATE teachers
                SET is_deleted=1
                WHERE abbreviation='$abbr';";
        if(mysqli_query($con,$sql)) {
            $success[]="ผู้สอน(abbr=" . $abbr . ") ถูกลบเรียบร้อยแล้ว";
        } else {
            $error[]="มีข้อผิดพลาดเกิดขึ้นในการลบ ผู้สอน(id=" . $id . ") :" . mysqli_error($con);
        }
    }

    $_SESSION['error'] = $error;
    $_SESSION['success'] = $success;

    if ($_SESSION['userlevel'] == "admin") { 
        header('location: ../../home_admin_teacher.php');
    }
?>