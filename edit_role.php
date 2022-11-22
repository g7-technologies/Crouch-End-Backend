<?php
include('db/connection.php');
if(!isset($_SESSION['login_user'])){ 
    header("location: index.php"); // Redirecting To Home Page 
}
include_once('includes/header_start.php');
include_once('includes/header_end.php');

$role_id = $_GET['id'];

$query_role = "SELECT * FROM role WHERE id = '$role_id'";
$result_role = mysqli_query($conn,$query_role);
$role_name = mysqli_fetch_assoc($result_role);

if(isset($_POST['create_role'])){

    $role_type = $_POST['role_type'];
    $query = "UPDATE role SET name = '$role_type' WHERE id = '$role_id'";
    $result = mysqli_query($conn,$query); 
    echo "<script>window.location.href='all_roles.php';</script>";
}

?>


        <link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

                            <ul class="list-inline menu-left mb-0">
                                <li class="list-inline-item">
                                    <button type="button" class="button-menu-mobile open-left waves-effect">
                                        <i class="ion-navicon"></i>
                                    </button>
                                </li>
                                <li class="hide-phone list-inline-item app-search">
                                    <h3 class="page-title">Edit Role</h3>
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
            
                                            <h4 class="mt-0 header-title">Edit Role</h4>
                                            <form method="POST" action="">
                                                
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="col-sm-2 col-form-label">Role</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="role_type" required="required" placeholder="Role" value="<?php echo $role_name['name']; ?>" id="example-text-input"/>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-3">
                                                        <button class="btn btn-outline-success waves-effect waves-light" name="create_role" style="width: 100%">Update Role</button>
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

        <!-- Required datatable js -->
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
        <script src="assets/plugins/datatables/jszip.min.js"></script>
        <script src="assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="assets/plugins/datatables/buttons.colVis.min.js"></script>
        <!-- Responsive examples -->
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

        <!-- Datatable init js -->
        <script src="assets/pages/datatables.init.js"></script>

<?php include_once('includes/footer_end.php'); ?>