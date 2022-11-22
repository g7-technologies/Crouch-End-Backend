<?php
include('db/connection.php');
if(!isset($_SESSION['login_user'])){ 
    header("location: index.php"); // Redirecting To Home Page 
}
include_once('includes/header_start.php');

$user_id = $_GET['id'];

$requests = mysqli_query($conn,"SELECT user.id, user.name AS user_name, user.phone, user.email, user.image, user.status, user.is_deleted, DATE_FORMAT(user.created_at,'%M %d, %Y') AS user_joining, role.name FROM user LEFT OUTER JOIN user_role ON user.id = user_role.user_id LEFT OUTER JOIN role ON user_role.role_id = role.id WHERE user.id = '$user_id'");
$counter = 0;
$row = mysqli_fetch_assoc($requests);

$posts = mysqli_query($conn,"SELECT * FROM posts WHERE user_id = '$user_id' AND status = 1 AND is_deleted = 0");
$count = mysqli_num_rows($posts);

include_once('includes/header_end.php');
?>


        <!-- DataTables -->
        <link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />


                            <!-- Page title -->
                            <ul class="list-inline menu-left mb-0">
                                <li class="list-inline-item">
                                    <button type="button" class="button-menu-mobile open-left waves-effect">
                                        <i class="ion-navicon"></i>
                                    </button>
                                </li>
                                <li class="hide-phone list-inline-item app-search">
                                    <h3 class="page-title">User Details</h3>
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
                                    
                                
            
                                            <h4 class="mt-0 header-title">View User Details</h4>
                                            
                                            <div class="row">
                                                <div class="col-lg-4"></div>
                                                <div class="col-lg-4">
                                                    <div class="card m-b-20">
                                                        <div class="card-body">
                            
                                                            <div class="media">
                                                                <img class="d-flex mr-3 rounded-circle thumb-lg" src="profileImages/<?php echo $row['image']; ?>" alt="Generic placeholder image">
                                                                <div class="media-body">
                                                                    <h5 class="m-t-10 font-18 mb-1"><?php echo $row['user_name']; ?></h5>
                                                                    <p class="text-muted m-b-5"><?php echo $row['email']; ?></p>
                                                                    <p class="text-muted font-14 font-500 font-secondary"><?php echo $row['name']; ?></p>
                                                                    <p class="text-muted font-14 font-500 font-secondary"><?php
                                                                    if($row['status'] == 1 && $row['is_deleted'] == 0 ){ ?>
                                                                        <span class="badge badge-pill badge-success">Live</span>
                                                                    <?php }
                                                                    else if($row['status'] == 0 && $row['is_deleted'] == 0 ){ ?>
                                                                        <span class="badge badge-pill badge-info">Pending for Approval</span>
                                                                   <?php }
                                                                    else if($row['status'] == 0 && $row['is_deleted'] == 1 ){ ?>
                                                                        <span class="badge badge-pill badge-danger">Deleted</span>
                                                                   <?php }
                                                                    ?></p>
                                                                </div>
                                                            </div>
                            
                                                            <div class="row text-center m-t-20">
                                                                <div class="col-12">
                                                                    <h5 class="mb-0"><?php echo $count; ?></h5>
                                                                    <p class="text-muted font-14">Active Posts</p>
                                                                </div>
                                                            </div>
                                                            <div class="row text-center m-t-20">
                                                                <div class="col-12">
                                                                    <h5 class="mb-0"></h5>
                                                                    <p class="text-muted font-14">Joined On <?php echo $row['user_joining']; ?></p>
                                                                </div>
                                                            </div>
                                                            
                            
                                                            <ul class="social-links text-center list-inline mb-0 mt-3">
                                                                
                                                                
                                                                <li class="list-inline-item">
                                                                    <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="<?php echo $row['phone']; ?>"><i class="fa fa-phone"></i></a>
                                                                </li>
                                                                
                                                            </ul>
                                                            <ul class="social-links text-center list-inline mb-0 mt-3">
                                                                <?php if($row['status'] == 1 && $row['is_deleted'] == 0 ){ ?>
                                                                        <li class="list-inline-item">
                                                                            <a data-placement="top" data-toggle="tooltip" class="tooltips" href="block_user.php?id=<?php echo $user_id; ?>" data-original-title="Block User"><i class="mdi mdi-account-off"></i></a>
                                                                        </li>
                                                                        <li class="list-inline-item">
                                                                            <a data-placement="top" data-toggle="tooltip" class="tooltips" href="delete_user.php?id=<?php echo $user_id; ?>" data-original-title="Delete User"><i class="mdi mdi-delete-forever"></i></a>
                                                                        </li>
                                                                    <?php } ?>
                                                                
                                                                
                                                                
                                                            </ul>
                            
                                                        </div>
                                                    </div>
                                                    
                                            </div>
                                            
                                        </div>
                            </div> <!-- end row -->
            
                            
                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->

                </div> <!-- content -->

<?php include_once('includes/footer_start.php'); ?>


        <!-- Datatable init js -->
        <script src="assets/pages/datatables.init.js"></script>

<?php include_once('includes/footer_end.php'); ?>