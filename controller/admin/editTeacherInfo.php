<?php

    include('../../dbconnect.php');

    $id= $_GET["id"];
    
    if (isset($_POST["abbr"]) && ($_POST["abbr"] != "")) {
        $abbr=$_POST["abbr"];
        $sql = "UPDATE teachers SET abbreviation='$abbr' WHERE teachersID = '$id'";
        if(mysqli_query($con,$sql)){
            $success[]="ผู้สอน(id=" . $id . ") ถูกแก้ไขตัวย่อแล้ว";
        } else {
            $error[]="มีข้อผิดพลาดเกิดขึ้นในแก้ไขตัวย่อ ผู้สอน(id=" . $id . ") :" . mysqli_error($con);
        }
    }
    
    if (isset($_POST["name"]) && ($_POST["name"] != "")) {
        $name=$_POST["name"];
        $sql = "UPDATE teachers SET fname='$name' WHERE teachersID = '$id'";
        if(mysqli_query($con,$sql)){
            $success[]="ผู้สอน(id=" . $id . ") ถูกแก้ไขชื่อแล้ว";
        } else {
            $error[]="มีข้อผิดพลาดเกิดขึ้นในแก้ไขชื่อ ผู้สอน(id=" . $id . ") :" . mysqli_error($con);
        }
    }
    
    if (isset($_POST["surname"]) && ($_POST["surname"] != "")) {
        $surname=$_POST["surname"];
        $sql = "UPDATE teachers SET lname='$surname' WHERE teachersID = '$id'";
        if(mysqli_query($con,$sql)){
            $success[]="ผู้สอน(id=" . $id . ") ถูกแก้ไขนามสกุลแล้ว";
        } else {
            $error[]="มีข้อผิดพลาดเกิดขึ้นในแก้ไขนามสกุล ผู้สอน(id=" . $id . ") :" . mysqli_error($con);
        }
    }

    if (isset($_POST["email"]) && ($_POST["email"] != "")) {
        $email=$_POST["email"];
        $sql = "UPDATE user SET userEmail='$email' WHERE id = '$id'";
        if(mysqli_query($con,$sql)){
            $success[]="ผู้สอน(id=" . $id . ") ถูกแก้ไขอีเมล";
        } else {
            $error[]="มีข้อผิดพลาดเกิดขึ้นในแก้ไขอีเมล ผู้สอน(id=" . $id . ") :" . mysqli_error($con);
        }
    }

    $_SESSION['error'] = $error;
    $_SESSION['success'] = $success;

    if ($_SESSION['userlevel'] == "admin") { 
        header('location: ../../home_admin_teacher.php');
    }

?>