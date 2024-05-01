<?php
    if(isset($_SESSION['success'])) { 
?>
    <div class="alert alert-success alert-dismissible fade show row" role="alert">
<?php
        for($i=0;$i<count($_SESSION["success"]);$i++) {
?>
            <div>    
                <?php
                    echo $_SESSION["success"][$i];
                ?>
            </div>
<?php 
        }
?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
<?php
        unset($_SESSION['success']);
    } 
?>

<?php
    if(isset($_SESSION['warning'])) { 
?>
    <div class="alert alert-warning alert-dismissible fade show row" role="alert">
<?php
        for($i=0;$i<count($_SESSION["warning"]);$i++) {
?>
            <div>
                <?php
                    echo $_SESSION["warning"][$i];
                ?>
            </div>
<?php 
        }
?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
<?php
        unset($_SESSION['warning']);
    } 
?>

<?php 
    if(isset($_SESSION['error'])) {
?>
        <div class="alert alert-danger alert-dismissible fade show row" role="alert">
<?php
        for($i=0;$i<count($_SESSION["error"]);$i++) {
?>
            <div>
                <?php
                    echo $_SESSION['error'][$i];
                ?>
            </div>
<?php 
        }
?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
<?php
        unset($_SESSION['error']);
    } 
?>