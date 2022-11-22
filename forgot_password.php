<?php 

include_once('db/connection.php'); 
if(!isset($_SESSION['login_user'])){ 
    header("location: index.php"); // Redirecting To Home Page 
}
include_once('includes/header_account.php'); 
    $msg="";

    if(isset($_POST['submit'])){

        $email = $_POST['email'];

        $check = "SELECT * FROM admin WHERE email = '$email'";
        $result = mysqli_query($conn,$check);

        if($result->num_rows > 0 ){

            $row  = mysqli_fetch_assoc($result_exists);
            $pass = $row['password'];

            $to = $email;
            $from = 'support@socialapp.com';
            $subject = 'Forgot Password';
            $message = 'This is a system generated email. You requested for password retrieval '.$pass.' is your password. If you have not requested for password Kindly change your password and contact Admin.';
            $headers = 'From: '. $from . "\r\n" .
            'Reply-To: '.$from . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
        
            $msg = 'Kindly Check your Email..!';

        } else {
            $msg = "No User found...!";  
        }
 
    }

?>

        <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">

            <div class="card" style="background: #f1f21b; border-radius: 50px;">
                <div class="card-body">
                    <?php if($msg != ""){
                      echo '<center><p class="alert alert-danger" role="alert">'.$msg.'</p></center>';
                    } ?>
                    <h3 class="text-center m-0">
                        <a href="#" class="logo logo-admin"><img src="assets/images/logo_dark.png" height="90" alt="logo"></a>
                    </h3>

                    <div class="p-3">
                        <h4 class="text-muted font-18 mb-3 text-center">Reset Password</h4>
                        <div class="alert alert-info" role="alert">
                            Enter your Email and instructions will be sent to you!
                        </div>

                        <form class="form-horizontal m-t-30" action="forgot_password.php" method="post">

                            <div class="form-group">
                                <label for="useremail">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter email">
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-sm-12 text-center">
                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit" name="submit">Reset</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="m-t-40 text-center">
                <p>Remember It ? <a href="index.php" class="font-500 font-14 text-primary font-secondary"> Sign In Here </a> </p>
                <p>© 2020 Social App. Crafted with <i class="mdi mdi-heart text-danger"></i> <a href="http://g7technologies.com/">G7 Technologies</a></p>
            </div>

        </div>

<?php include_once('includes/footer_account.php'); ?>