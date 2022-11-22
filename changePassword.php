<?php

include_once('db/connection.php');
if(!isset($_SESSION['login_user'])){ 
    header("location: index.php"); // Redirecting To Home Page 
}
include_once('includes/header_start.php');

$msg="";

if(isset($_POST['change_password'])){
      
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_new_pass = $_POST['confirm_new_pass'];

    $check = "SELECT password FROM admin";
    $result = mysqli_query($conn,$check);

        if($result->num_rows > 0 ){

            $data = $result->fetch_array();
            
            if($old_pass == $data['password']){
            
                if($new_pass == $confirm_new_pass){


                    $update_pass = mysqli_query($conn,"UPDATE admin SET password= '$new_pass';");
                    if($update_pass){

                        echo "<script>window.location.href='Dashboard.php';</script>";
                    }
                    else{
                        
                    $msg = "Unable to updated password";    
                    }
                }
                else{
                $msg = "New Password doesn't match confirm new password...!";
                }
            }
            else{
            $msg = "Wrong Password...!";  
            }
        } 
        else {
        $msg = "No user found...!";  
        } 
    }


include_once('includes/header_end.php');
?>


                            <!-- Page title -->
                            <ul class="list-inline menu-left mb-0">
                                <li class="list-inline-item">
                                    <button type="button" class="button-menu-mobile open-left waves-effect">
                                        <i class="ion-navicon"></i>
                                    </button>
                                </li>
                                <li class="hide-phone list-inline-item app-search">
                                    <h3 class="page-title">Change Password</h3>
                                </li>
                            </ul>

                            <div class="clearfix"></div>
                        </nav>

                    </div>
                    <!-- Top Bar End -->

                    <!-- ==================
                         PAGE CONTENT START
                         ================== -->

                    <div class="page-content-wrapper">

                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-12">
                                    <div class="card m-b-20">
                                        <div class="card-body">
                                        <?php if($msg != ""){
                                            echo '<center><p class="alert alert-danger" role="alert">'.$msg.'</p></center>';
                                        } ?>
                                            <h4 class="mt-0 header-title">Change Password</h4>
                                            </br>
										 	<form method="post" action="">
											
												<div class="form-group row">
													<label for="example-text-input" class="col-sm-3 col-form-label">Old Password </label>
													<div class="col-sm-9">
														<input type="password" class="form-control" name="old_pass" required="required" placeholder="Old Password" id="example-text-input"/>
													</div>
												</div>

                                                <div class="form-group row">
                                                    <label for="example-text-input" class="col-sm-3 col-form-label">New Password </label>
                                                    <div class="col-sm-9">
                                                        <input type="password" class="form-control" name="new_pass" required="required" placeholder="New Password" id="example-text-input"/>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="example-text-input" class="col-sm-3 col-form-label">Confirm New Password </label>
                                                    <div class="col-sm-9">
                                                        <input type="password" class="form-control" name="confirm_new_pass" required="required" placeholder="Confirm New Password" id="example-text-input"/>
                                                    </div>
                                                </div>

												<div class="form-group row">
											
													<div class="col-sm-3"></div>
											
													<div class="col-sm-3">
														<button class="btn btn-outline-success waves-effect waves-light" name="change_password" style="width: 100%">Change Password</button>
													</div>
											
													<div class="col-sm-3">
														<a href="Dashboard.php" class="btn btn-outline-danger waves-effect waves-light" style="width: 100%" >Cancel</a>
													</div>
													<div class="col-sm-3"></div>
												</div>
											
											</form>

										</div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
            

                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->

                </div> <!-- content -->

<?php include_once('includes/footer_start.php'); ?>

		<script src="assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
        <script src="assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="assets/plugins/parsleyjs/parsley.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('form').parsley();
            });
        </script>
        <!-- Plugins Init js -->
        <script src="assets/pages/form-advanced.js"></script>

<?php include_once('includes/footer_end.php'); ?>