    <div class="modal fade" id="leavemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!--เชื่อมกับphpในการรับข้อมูลต่างๆ-->
                            <div class="mb-4">
                                <label for="coursecode" class="form-label">ต้องการยืนยันที่จะออกจากรายวิชานี้เนื่องจากท่านได้ลด/ถอนรายวิชาจากระบบแล้ว</label>
                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a type="button" class="btn btn-danger" href="..\withdraw.php?id=<?php echo $coursesID;?>">Leave</a>
                </div>
            </div>
        </div>
    </div>