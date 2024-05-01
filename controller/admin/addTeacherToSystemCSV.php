<?php
    include('../../dbconnect.php');
    
    if(isset($_POST["upload"])) {
        $filename=$_FILES["file"]["tmp_name"];    
        
        if($_FILES["file"]["size"] > 0) {
            $file = fopen($filename, "r");
            $flag = true;
            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
                if($flag) { $flag = false; continue; }
                
                // Check Title, FirstName, LastName
                $full_name = $getData[2];
                // Array of common titles
                $titles = array("Mr.", "Mrs.", "Ms.", "Dr.", "Prof.", "Assist.", "Sir");
                // Split the full name into an array of words
                $name_array = explode(" ", $full_name);
                // Check for Titles and remove them from the array
                $title = "";
                while (in_array($name_array[0], $titles)) {
                    $title .= array_shift($name_array);
                }
                // Separate last Title and FirstName
                if (strpos($name_array[0],".") !== FALSE) {
                    $title_name = array_shift($name_array);
                    $title .= substr($title_name,0,strpos($title_name,".")+1);
                    $fname = substr($title_name,strpos($title_name,".")+1);
                } else {
                    $fname = array_shift($name_array);
                }
                // Set the remaining words as LastName
                $lname = array_pop($name_array);
                
                $mail = $getData[1];
                $abbr = $getData[3];
                
                $sql = "SELECT teachersID,is_deleted FROM teachers WHERE abbreviation='$abbr'";
                $queryTeacher = mysqli_fetch_assoc(mysqli_query($con, $sql));

                if($queryTeacher > 0) {
                    $id = $queryTeacher['teachersID'];
                    $isDeleted = $queryTeacher['is_deleted'];
                    $error[] = "มีอักษรย่อ(" . $abbr . ")อยู่ในระบบแล้ว กรุณาทำการแก้ไขข้อมูลรายบุคคลแทน";
                    
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
                            VALUES ('$id','$abbr','$fname','$lname','$title')";
                    if(mysqli_query($con, $sql)) {
                        $success[] = "teacher(id=" . $id . ") ได้ถูกเพิ่มเข้าระบบ";
                    } else {
                        $error[] = "มีข้อผิดพลาดเกิดขึ้นในการเพิ่ม teacher(id=" . $id . ")ใหม่ :" . mysqli_error($con);
                    }
                }
            }
            fclose($file);  
        }

        if(!isset($success)){
            $error[] = "มีข้อมูลอาจารย์อยู่แล้ว";
        }
    }

    $_SESSION['error'] = $error;
    $_SESSION['success'] = $success;
            
    if ($_SESSION['userlevel'] == "admin") { 
        header('location: ../../home_admin_teacher.php');
    }
?>