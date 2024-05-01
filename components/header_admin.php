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
                            <a href="home_admin.php" class="nav-link"> 
                            COURSE
                            </a>
                        </li>

                        <!--ส่วนเมนูreport-->
                        <li class="nav-item">
                            <a href="report.php" class="nav-link"><span class="material-icons">
                                    warning
                                </span></a>
                        </li>
                        <!--ส่วนเมนูตั้งเวลาประเมิน-->
                        <li class="nav-item">
                            <button type="button" class="btn btn-dark position-relative" data-bs-toggle="modal"
                                data-bs-target="#settime" style="color: rgb(202, 202, 202);"> <span
                                    class="material-icons">
                                    schedule
                                </span>
                            </button>
                        </li>

                        <!-- ส่วนปุ่มแจ้งเตือน -->
                        <li class="nav-item" style="margin-right :20px">
                            <button type="button" class="btn btn-dark position-relative" id="liveToastBtn">
                                <span class="material-icons">notifications_active</span>
                                <?php
                                    $querynotinum = "SELECT COUNT(*) AS numnoti FROM notifications_admin WHERE needAction = 1";
                                    $resultnotinum = mysqli_fetch_array(mysqli_query($con, $querynotinum));
                                    $notinum = $resultnotinum['numnoti'];
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
                                <img src="./images/logo400.jpeg"
                                    width="32" height="32" class="rounded-circle me-2">
                                <!-- ดึงชื่อผู้ใช้งานจากฐานข้อมูล ชื่อที่ถูกดึงอยู่ภายใต้ <strong> ชื่อนิสิต </strong> -->
                                <strong> <?php echo $_SESSION["user"]; ?> ( <?php echo $_SESSION["userid"]; ?> ) </strong>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
                                    <li><a class="dropdown-item" href="home_admin.php?logout='1">Sign out</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>        
                </div>
            </div>
        </nav> 
    <!-- จบ : แถบเมนูด้านบน -->                  
    </div>

<!-- modal ตั้งเวลาประเมิน -->
<?php include('modal_timer_admin.php')?>