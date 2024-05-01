<?php

    include('../../dbconnect.php');

    $id= $_GET["id"];
    
    if (isset($_POST["name"]) && ($_POST["name"] != "")) {
        $name=$_POST["name"];
        $sql = "UPDATE students SET fname='$name' WHERE studentsID = '$id'";
        if(mysqli_query($con,$sql)){
            $success[]="นิสิต(id=" . $id . ") ถูกแก้ไขชื่อแล้ว";
        } else {
            $error[]="มีข้อผิดพลาดเกิดขึ้นในแก้ไขชื่อ นิสิต(id=" . $id . ") :" . mysqli_error($con);
        }
    }
    
    if (isset($_POST["surname"]) && ($_POST["surname"] != "")) {
        $surname=$_POST["surname"];
        $sql = "UPDATE students SET lname='$surname' WHERE studentsID = '$id'";
        if(mysqli_query($con,$sql)){
            $success[]="นิสิต(id=" . $id . ") ถูกแก้ไขนามสกุลแล้ว";
        } else {
            $error[]="มีข้อผิดพลาดเกิดขึ้นในแก้ไขนามสกุล นิสิต(id=" . $id . ") :" . mysqli_error($con);
        }
    }

    if (isset($_POST["email"]) && ($_POST["email"] != "")) {
        $email=$_POST["email"];
        $sql = "UPDATE user SET userEmail='$email' WHERE id = '$id'";
        if(mysqli_query($con,$sql)){
            $success[]="นิสิต(id=" . $id . ") ถูกแก้ไขอีเมล";
        } else {
            $error[]="มีข้อผิดพลาดเกิดขึ้นในแก้ไขอีเมล นิสิต(id=" . $id . ") :" . mysqli_error($con);
        }
    }

    $_SESSION['error'] = $error;
    $_SESSION['success'] = $success;

    if ($_SESSION['userlevel'] == "admin") { 
        header('location: ../../home_admin_student.php');
    }

?>