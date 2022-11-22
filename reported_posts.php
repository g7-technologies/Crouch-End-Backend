<?php
include('db/connection.php');
if(!isset($_SESSION['login_user'])){ 
    header("location: index.php"); // Redirecting To Home Page 
}
include_once('includes/header_start.php');


$requests = mysqli_query($conn,"SELECT posts.id AS post_id, posts.status, posts.is_deleted, posts.description, user.name AS user_name, user.id AS user_id, role.name FROM post_reports LEFT OUTER JOIN posts ON post_reports.post_id = posts.id LEFT OUTER JOIN user ON posts.user_id = user.id LEFT OUTER JOIN user_role ON user_role.user_id = user.id LEFT OUTER JOIN role ON user_role.role_id = role.id ORDER BY post_reports.created_at");
$counter = 0;

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
                                    <h3 class="page-title">Reported Posts</h3>
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
            
                                            <h4 class="mt-0 header-title">View Reported Posts</h4>
            
                                            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                        			    <th>Sr#</th>
													    <th>User Name</th>
													    <th>Role</th>
                                                        <th>Description</th>
                                                        <th>Post Status</th>
                                                        <th>No of People Reported</th>
													    <th>Action</th>
                                                    </tr>
                                                </thead>
            
            
                                                <tbody>
                                        			<?php while($row = mysqli_fetch_array($requests)){ $counter++;  $post_id = $row['post_id']; ?>
														<tr>
														    <td><?php echo $counter; ?></td>
                                                            <td><?php if($row['user_name'] == null){ echo 'Admin'; }else{ 
                                                                ?><a href="user_detail.php?id=<?php echo $row['user_id']?>"><?php echo htmlentities ($row['user_name']); ?></a><?php
                                                            } ?></td>
														    <td><?php 
														            if($row['user_name'] == null){ 
														                echo "Admin"; 
													                }
													                else{ 
    														            if($row['name'] == null){ 
    														                echo "No Role Assigned"; 
    													                }
    													                else{ 
    													                    echo htmlentities ($row['name']); 
    												                    }
												                    }?></td>
														    <td><?php echo htmlentities ($row['description']);?></td>
														    <td><?php
                                                                    if($row['status'] == 1 && $row['is_deleted'] == 0 ){ ?>
                                                                        <center><span class="badge badge-pill badge-success">Live</span></center>
                                                                    <?php }
                                                                    else if($row['status'] == 0 && $row['is_deleted'] == 0 ){ ?>
                                                                        <center><span class="badge badge-pill badge-info">Pending for Approval</span></center>
                                                                   <?php }
                                                                    else if($row['status'] == 0 && $row['is_deleted'] == 1 ){ ?>
                                                                        <center><span class="badge badge-pill badge-danger">Deleted</span></center>
                                                                   <?php }
                                                                ?></td>
														    <td><?php $queryc = mysqli_query($conn,"SELECT * FROM post_reports WHERE post_id = '$post_id'"); 
														                ?> <center><?php echo mysqli_num_rows($queryc); ?></center>
											                </td>
														    <td>
														        <a href="post_details.php?id=<?php echo $post_id; ?>" class="btn btn-outline-primary waves-effect waves-light"><span class="mdi mdi-information-variant"></span></a>
														        <a href="block_post.php?id=<?php echo $post_id; ?>" class="btn btn-outline-danger waves-effect waves-light"><i class="mdi mdi-delete-forever"></i></a>
														        <!--<a href="activate_post.php?id=<?php echo $post_id; ?>" class="btn btn-outline-success waves-effect waves-light"><i class="mdi mdi-shield-outline"></i></a>-->
														    </td>
													    </tr>
												    <?php } ?>
                                                </tbody>
                                            </table>
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