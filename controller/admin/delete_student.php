<?php
    include('../../dbconnect.php');

    if(isset($_POST['delete_student'])) {
        $id = $_POST['delete_student'];
        
        $sql = "UPDATE students
                SET is_deleted=1
                WHERE studentsID=$id;";
        if(mysqli_query($con,$sql)) {
            $success[]="นิสิต(id=" . $id . ") ถูกลบเรียบร้อยแล้ว";
        } else {
            $error[]="มีข้อผิดพลาดเกิดขึ้นในการลบ นิสิต(id=" . $id . ") :" . mysqli_error($con);
        }
    }

    $_SESSION['error'] = $error;
    $_SESSION['success'] = $success;

    if ($_SESSION['userlevel'] == "admin") { 
        header('location: ../../home_admin_student.php');
    }
?>