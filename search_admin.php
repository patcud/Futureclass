<?php
    include('dbconnect.php');

    // get the text parameter from URL
    $text = $_REQUEST["text"];
?>   
    <tr class="text-start text-gray-800 fw-bolder fs-6 text-uppercase">
        <th class="w-10">Icon</th>
        <th class="text-center w-30">Subject ID</th>
        <th class="text-center w-30">Name(EN)</th>
        <th class="text-center w-30">Action</th>
    </tr>
<?php
    $text = strtolower($text);
    $sql = "SELECT *
            FROM courses
            LEFT JOIN subjects ON courses.subjectsID = subjects.subjectsID
            WHERE semesterID = $semID
            ORDER BY courses.subjectsID ASC"; 
    $subs = mysqli_query($con,$sql);
    while($sub = mysqli_fetch_assoc($subs)) {
        $cid = $sub['coursesID'];
        $subNameEN = $sub['nameEN']; 
        $subid = $sub['subjectsID'];
        if ((strpos($subid, $text) !== FALSE) OR (strpos(strtolower($subname), $text) !== FALSE) OR ($text=="")) {
?>
            <tr class="fs-6">
                <td class="text-center w-10"><img src="read_subject_pic.php?id=<?= $cid ?>" class="w-25px" style="width:25px;"></td>
                <td class="text-gray-400 w-30"><?= $subid ?></td>
                <td class="text-gray-400 w-30"><?= $subNameEN ?></td>
                <td class="text-center w-30 gap-1 d-block">
                    <a href="subject_admin.php?id=<?= $cid ?>" class="btn btn-light text-muted fw-bolder btn-sm">View</a>
                    <button type="button" class="btn btn-danger text-white fw-bolder btn-sm px-2 addstudent">Add student</button>
                    <button type="button" class="btn btn-danger text-white fw-bolder btn-sm px-2 deletebutton">Delete</button>
                </td>
            </tr>
<?php
        }
    }
?>