    <div class="modal fade" id="regismodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">REGISTER</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!--เชื่อมกับphpในการรับข้อมูลต่างๆ-->
                        <form action="regis.php" method="POST">
                            <div class="mb-4">
                                <label for="coursecode" class="form-label">Course Number</label>
                                <div class="decinput">
                                    <input type="text" class="form-control" id="coursecode" name="coursecode" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="coursepassword" class="form-label">Course Password</label>
                                <div class="decinput">
                                    <input type="text" class="form-control" id="coursepassword" name="password" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="coursesec" class="form-label">Course Section</label>
                                <div class="decinput">
                                    <input type="text" class="form-control" id="coursesec" name="section" required>
                                </div>
                            </div>
                            <div class="d-grid gap-2 col-6 mx-auto">
                                <button type="submit" class="btn btn-danger">Submit</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>