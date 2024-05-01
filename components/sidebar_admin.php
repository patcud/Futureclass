    <!--ส่วนเมนูย่อยด้านข้าง เมื่อหน้าจอขนาดเล็กจะแสดงผลเพียง Icon รายการต่างๆ ขนาดเมนู col-md-2-->
    <div class="col col-md-2 px-sm-2 px-0 bg-dark" style="border-radius: 20px; border-left: 5px solid rgb(161, 16,16); margin-top: 20px;">
        <ul class="nav flex-column sticky-top">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100 w-100">
                    <!-- แสดงชื่อวิชาที่ดึงข้อมูลจากฐานข้อมูล โดยชื่อวิชาจะแสดงภายใต้ <span class="fs-5 d-none d-md-inline"> ชื่อวิชา </span>-->
                    <a href="#" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-md-inline" style="font-family: &quot;Gill Sans&quot;, &quot;Gill Sans MT&quot;, Calibri, &quot;Trebuchet MS&quot;, sans-serif;"><?php echo $coursename; ?></span>
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <!--แสดงรายละเอียดเมนูย่อยต่าง ๆ โดย <i class="cil-search"></i> คือการนำ icon ต่างๆมาแสดงหน้าชื่อเมนู -->
                    <li class="list active">
                        <a href="subject_admin.php?id=<?php echo $coursesID; ?>" class="nav-link align-middle px-0">
                                <span class="icon"><i class="cil-search"></i></span>
                                <span class="title ms-1 d-none d-md-inline">About</span>
                        </a>
                    </li>
                    <li class="list">
                        <a href="request_ad.php?id=<?php echo $coursesID; ?>"class="nav-link align-middle px-0">
                            <span class="icon"><i class="cil-dinner"></i></span>
                            <span class="title ms-1 d-none d-md-inline">Request</span></a>
                    </li>
                    <li class="list-A">
                        <a href="#" class="nav-link px-0 align-middle">
                            <span class="icon"><i class="cil-chart"></i></span>
                            <span class="title ms-1 d-none d-md-inline">Assessment</span>
                        </a>                
                    <li class="sub-list" style = "margin-left: 20px;">
                        <a href="assessment_subject_admin.php?id=<?php echo $coursesID; ?>" class="nav-link px-0">
                            <span class="icon"><i class="cil-spreadsheet"></i></span>
                            <span class="title d-none d-md-inline">Subject</span></a>
                    </li>
                    <li class="sub-list" style = "margin-left: 20px;">
                        <a href="assessment_instructor_admin.php?id=<?php echo $coursesID; ?>" class="nav-link px-0">
                            <span class="icon"><i class="cil-people"></i></span>
                            <span class="title d-none d-md-inline">Instructor</span></a>
                    </li>
                    <li class="list">
                        <a href="management_admin.php?id=<?php echo $coursesID; ?>" class="nav-link align-middle px-0">
                            <span class="icon"><i class="cil-settings"></i></span>
                            <span class="title ms-1 d-none d-md-inline">Management</span>
                        </a>
                    </li>

                        <!-- **** -->
                        <!-- เพิ่มเมนูออกจากวิชา -->
                        <!-- <li class="list">

                        </li> -->

                    </li>
                </ul>
            </div>
        </ul>
    </div>