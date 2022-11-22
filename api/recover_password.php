<?php 
include_once('includes/header_account.php'); 
include_once('../db/connection.php');

    $id = $_GET['id'];

    $error_msg="";
    $success_msg="";

    if(isset($_POST['submit'])){
        
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if($new_password == $confirm_password){

            $update_pass = mysqli_query($conn,"UPDATE user SET password = '$new_password' WHERE user.id = '$id'");
            
            if($update_pass){

                $success_msg = "Password updated successfully...!";
            
            }else{

                $error_msg = "Unable to updated password...!";    
            }
        }
        else{
            
            $error_msg = "New Password doesn't match confirm new password...!";
        }
    }

?>

        <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">

            <div class="card" style="background: #f1f21b; border-radius: 50px;">
                <div class="card-body">
                    <?php if($error_msg != ""){
                      echo '<center><p class="alert alert-danger" role="alert">'.$error_msg.'</p></center>';
                    } ?>
                    <?php if($success_msg != ""){
                      echo '<center><p class="alert alert-success" role="alert">'.$success_msg.'</p></center>';
                    } ?>
                    <h3 class="text-center m-0">
                        <a href="#" class="logo logo-admin"><img src="assets/images/logo_dark.png" height="90" alt="logo"></a>
                    </h3>

                    <div class="p-3">
                        <h4 class="text-muted font-18 mb-3 text-center">Reset Password</h4>
                        
                        <form class="form-horizontal m-t-30" action="recover_password.php" method="post">

                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" class="form-control" name="new_password" placeholder="Enter new password" required>
                            </div>

                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" name="confirm_password" placeholder="Enter new password again" required>
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-sm-12 text-center">
                                    <button class="btn btn-success w-md waves-effect waves-light" type="submit" name="submit">Reset</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="m-t-40 text-center">
                <p>© 2020 Social App. Crafted with <i class="mdi mdi-heart text-danger"></i> <a href="http://g7technologies.com/">G7 Technologies</a></p>
            </div>

        </div>

<?php include_once('includes/footer_account.php'); ?>