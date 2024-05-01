<?php
    include('dbconnect.php');

    if(isset($_POST['teacherID']) and isset($_GET['id'])) {
        $tid = $_POST['teacherID'];
        $cid = $_GET['id'];
        
        $sql = "DELETE FROM teacher_course WHERE teachersID=$tid AND coursesID=$cid";
        $result = mysqli_query($con,$sql);
    }

    if ($_SESSION['userlevel'] == "admin") { 
        header('location: staff_admin.php?id='.$cid);
    }
?>