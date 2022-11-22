<?php
    include('db/connection.php');
    if(!isset($_SESSION['login_user'])){ 
        header("location: index.php"); // Redirecting To Home Page 
    }
    include_once('includes/header_start.php');
    include_once('includes/header_end.php');

    $user = mysqli_query($conn,"SELECT * FROM user WHERE status = 1 AND is_deleted = 0");
    $role = mysqli_query($conn,"SELECT * FROM role WHERE status = 1");

    if(isset($_POST['assign_role'])){
        $user_id = $_POST['user_id'];
        $role_type = $_POST['role_type'];

        $check_role = mysqli_query($conn,"SELECT * FROM user_role WHERE user_id = '$user_id'");
        if(mysqli_num_rows($check_role) > 0){
            $update_role = mysqli_query($conn,"UPDATE user_role SET role_id = '$role_type' WHERE user_id = '$user_id'");
        }
        else{
            $insert_role = mysqli_query($conn,"INSERT INTO user_role (user_id,role_id) VALUES ('$user_id',$role_type)");
        }
        
        echo "<script>window.location.href='activate_user.php';</script>";
    }

?>
                            <!-- Page title -->
                            <ul class="list-inline menu-left mb-0">
                                <li class="list-inline-item">
                                    <button type="button" class="button-menu-mobile open-left waves-effect">
                                        <i class="ion-navicon"></i>
                                    </button>
                                </li>
                                <li class="hide-phone list-inline-item app-search">
                                    <h3 class="page-title">Assign Role to User</h3>
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
                                    <div class="card m-b-30">
                                        <div class="card-body">
            
                                            <h4 class="mt-0 header-title">Assign Role to User</h4>
                                            
                                            <form action="" method="POST">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Select User</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" required name="user_id">
                                                            <option value="">Select</option>
                                                            <?php
                                                                while($row_user=mysqli_fetch_array($user)){
                                                            ?>
                                                                    <option value="<?php echo $row_user['id']; ?>"><?php echo $row_user['name']; ?></option>
                                                            <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Select Role</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="role_type">
                                                            <option value="">Select</option>
                                                            <?php
                                                                while($row_role=mysqli_fetch_array($role)){
                                                            ?>
                                                                    <option value="<?php echo $row_role['id']; ?>"><?php echo $row_role['name']; ?></option>
                                                            <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-3">
                                                        <button class="btn btn-outline-success waves-effect waves-light" name="assign_role" style="width: 100%">Assign Role</button>
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
<?php include_once('includes/footer_end.php'); ?>