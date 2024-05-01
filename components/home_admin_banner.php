<div class="d-flex mt-5 justify-content-center" style="gap:80px;">
    <a class="w-30 p-3 border shadow rounded" style="text-decoration:none; background-color:#FFF; cursor:pointer; color:rgb(0,0,0);" href="./home_admin.php">
        <div class="d-flex gap-4 justify-content-center align-items-center">
            <div>
                <?php
                    $queryCourse = "SELECT COUNT(*) AS num_course FROM courses WHERE semesterID = $semID";
                    $resultCourse = mysqli_fetch_array(mysqli_query($con, $queryCourse));
                    $course = $resultCourse['num_course'];
                ?>
                <p class="mb-0"><?php echo $course; ?></p>
                <p class="mb-0">Courses</p>
            </div>
            <div class="mb-0">
                <i class="fa-solid fa-book" style="font-size:2rem; color:#983132;"></i>
            </div>
        </div>
    </a>
    <a class="w-30 p-3 border shadow rounded" style="text-decoration:none; background-color:#FFF; cursor:pointer; color:rgb(0,0,0);" href="./home_admin_teacher.php">
        <div class="d-flex gap-4 justify-content-center align-items-center">
            <div>
                <?php
                    $queryTeacher = "SELECT COUNT(*) AS num_teacher FROM teachers WHERE is_deleted=0";
                    $resultTeacher = mysqli_fetch_array(mysqli_query($con, $queryTeacher));
                    $teacher = $resultTeacher['num_teacher'];
                ?>
                <p class="mb-0"><?php echo $teacher; ?></p>
                <p class="mb-0">Teachers</p>
            </div>
            <div class="mb-0">
                <i class="fa-solid fa-graduation-cap" style="font-size:2rem; color:#983132;"></i>
            </div>
        </div>
    </a>
    <a class="w-30 p-3 border shadow rounded" style="text-decoration:none; background-color:#FFF; cursor:pointer; color:rgb(0,0,0);" href="./home_admin_student.php">
        <div class="d-flex gap-4 justify-content-center align-items-center">
            <div>
                <?php
                    $queryStudent = "SELECT COUNT(*) AS num_student FROM students WHERE is_deleted=0";
                    $resultStudent = mysqli_fetch_array(mysqli_query($con, $queryStudent));
                    $student = $resultStudent['num_student'];
                ?>
                <p class="mb-0"><?php echo $student; ?></p>
                <p class="mb-0">Students</p>
            </div>
            <div class="mb-0">
                <i class="fa-solid fa-user-group" style="font-size:2rem; color:#983132;"></i>
            </div>
        </div>
    </a>
</div>