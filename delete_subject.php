<?php
    include('dbconnect.php');
    
    if(isset($_POST['delete_sub'])) {
        $sid = $_POST['delete_sub'];
        
        $sql = "DELETE FROM courses WHERE subjectsID=$sid AND semesterID=$semID";
        if(mysqli_query($con,$sql)) {
            $success[]="วิชา(id=" . $sid . ") ถูกลบเรียบร้อยแล้ว";
        } else {
            $error[]="มีข้อผิดพลาดเกิดขึ้นในการลบ วิชา(id=" . $sid . ") :" . mysqli_error($con);
        }
    }

    $_SESSION['error'] = $error;
    $_SESSION['success'] = $success;

    if ($_SESSION['userlevel'] == "admin") { 
        header('location: home_admin.php');
    }
?>