<?php 
    include_once('db/connection.php');
    $msg="";
  
    if(isset($_POST['submit'])){
      
      $email = $_POST['email'];
      $password = $_POST['password'];

      $check = "SELECT email , password FROM admin WHERE email = '$email'";
      $result = mysqli_query($conn,$check);

      if($result->num_rows > 0 ){

        $data = $result->fetch_array();

        if($password == $data['password']){
          $msg = "You have logged In";
          $_SESSION['login_user'] = $email;
          echo "<script>window.location.href='Dashboard.php';</script>";
        }
        else{
          $msg = "Wrong Password...!";  
        }

      } else {
        $msg = "No username found...!";  
      }
 
    }
    include_once('includes/header_account.php'); 
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
                        <h4 class="text-dark font-18 m-b-5 text-center">Welcome!</h4>
                        <p class="text-muted text-center">Sign in to continue.</p>

                        <form class="form-horizontal m-t-30" action="" method="post">

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email">
                            </div>

                            <div class="form-group">
                                <label for="userpassword">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-sm-12 text-center">
                                    <button class="btn btn-success w-md waves-effect waves-light" type="submit" name="submit">Log In</button>
                                </div>
                            </div>

                            <div class="form-group m-t-10 mb-0 row">
                                <div class="col-12 m-t-20 text-center">
                                    <a href="forgot_password.php" class="text-muted"><i class="mdi mdi-lock"></i> Forgot your password?</a>
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