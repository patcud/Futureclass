<!-- แถบเมนูด้านบน -->
    <div class="row">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top" style="border-radius: 10px;">
            <div class="container-fluid" style="min-height:71px;">
                <!-- ส่วน Logo learn -->
                <a href="#" class="navbar-brand">
                    <img src="./images/full-logo-w.png"  alt="" width="35" height="35" 
                        style="margin-right: 15px; margin-top: 1px;">
                    <div class="d-inline-block" 
                    style="margin-top: 15px;" >
                    <p class=" d-none d-sm-block">Future class | EECU </p>
                    </div>
                </a>

                <!-- สร้างปุ่มที่เมื่อแสดงผลในจอขนาดเล็กจะรวมเมนูไว้ในปุ่มนึง -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- ส่วนเมนูที่ติดขอบด้านขวา -->
                <div class="collapse navbar-collapse mt-1" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <!-- ส่วนปุ่มmycourse -->
                        <li class="nav-item">
                            <a href="home_student.php" class="nav-link"> 
                            MY COURSE
                            </a>
                        </li>

                        <!--ส่วนปุ่มให้นิสิตเพิ่มรายวิชาได้ด้วยตนเอง-->
                        <li class="nav-item">
                            <button type="button" class="btn btn-dark position-relative" style="color:#949494;font-weight:bolder;font-family: 'Mitr', sans-serif;" data-bs-toggle="modal" data-bs-target="#regismodal">REGISTER</button>
                        </li>

                        <!-- ส่วนปุ่มแจ้งเตือน -->
                        <li class="nav-item" style="margin-right :20px">
                            <button type="button" class="btn btn-dark position-relative" id="liveToastBtn">
                                <span class="material-icons">notifications_active</span>
                                <?php
                                    $querynotinum = "SELECT COUNT(*) AS numnoti FROM notifications WHERE (type = 'post' OR type = 'reply') AND userID = '$id' AND status = 'unread'";
                                    $resultnotinum = mysqli_fetch_array(mysqli_query($con, $querynotinum));
                                    $notinum = $resultnotinum['numnoti'];
                                    if (time() >= $_SESSION['startassess'] && time() <= $_SESSION['endassess']) {
                                        $haveAssessNoti = array();
                                        $qAssess = "SELECT * FROM notifications WHERE type = 'assess' AND userID = '$id' AND status = 'unread'";
                                        $rAssess = mysqli_query($con, $qAssess);
                                        if (mysqli_num_rows($rAssess) > 0) {
                                            while ($row = mysqli_fetch_array($rAssess)) {
                                                $notinum++;
                                                $haveAssessNoti[] = $row['coursesID'];
                                            }
                                        }
                                        $qTassess = "SELECT * FROM notifications WHERE type = 'tassess' AND userID = '$id' AND status = 'unread'";
                                        $rTassess = mysqli_query($con, $qTassess);
                                        if (mysqli_num_rows($rTassess) > 0) {
                                            while ($row = mysqli_fetch_array($rTassess)) {
                                                if (!in_array($row['coursesID'], $haveAssessNoti)) {
                                                $notinum++;
                                                }
                                            }
                                        }
                                    }
                                ?>
                                
                                <?php if ($notinum != 0) { ?>
                                    <span class='position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger'> <?php echo $notinum; ?> </span>
                                <?php } ?> 

                            </button>
                        </li>
                        <!-- ส่วนแสดงรูปและชื่อผู้ใช้งาน -->
                        <li class="nav-item">
                            <div class="dropdown">
                                <a href="#"
                                class="d-flex align-items-center fw-light text-white text-decoration-none dropdown-toggle"
                                id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; margin-bottom: 5px;">
                                <!-- ดึงรูปจากฐานข้อมูลแสดงผลที่ img src="ที่เก็บไฟล์รูปต่างๆ" -->
                                <img src="read_profile_pic.php"
                                    width="32" height="32" class="rounded-circle me-2">
                                <!-- ดึงชื่อผู้ใช้งานจากฐานข้อมูล ชื่อที่ถูกดึงอยู่ภายใต้ <strong> ชื่อนิสิต </strong> -->
                                <strong> <?php echo $_SESSION['user']; ?> </strong>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
                                    <li><a class="dropdown-item" href="profile_student.php">Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="home_student.php?logout='1">Sign out</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>        
                </div>
            </div>
        </nav> 
    <!-- จบ : แถบเมนูด้านบน -->                  
    </div>

<!-- modal การเพิ่มรายวิชาด้วยตนเอง -->
<?php include('modal_register_student.php') ?>