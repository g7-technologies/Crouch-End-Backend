<?php
include('db/connection.php');
if(!isset($_SESSION['login_user'])){ 
    header("location: index.php"); // Redirecting To Home Page 
}
include_once('includes/header_start.php');

$post_id = $_GET['id'];
$requests = mysqli_query($conn,"SELECT posts.id AS post_id,posts.status,posts.is_deleted, posts.description, user.name AS user_name, user.image AS profile_pic, user.id, role.name FROM posts LEFT OUTER JOIN user ON posts.user_id = user.id LEFT OUTER JOIN user_role ON user_role.user_id = user.id LEFT OUTER JOIN role ON user_role.role_id = role.id WHERE posts.id = '$post_id'");
$counter = 0;
$row = mysqli_fetch_assoc($requests);

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
                                    <h3 class="page-title">Post Details</h3>
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
            
                                            <h4 class="mt-0 header-title">Details</h4>
            
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="card m-b-20">
                                                        <div class="card-body">
                            
                                                            <div class="media m-b-30">
                                                                <?php if($row['profile_pic'] == null){
                                                                    
                                                                }else{
                                                                    ?> <img class="d-flex mr-3 rounded-circle" src="profileImages/<?php echo $row['profile_pic'] ?>" alt="Generic placeholder image" height="64"> <?php
                                                                }?>
                                                                <div class="media-body">
                                                                    <?php if($row['user_name'] == null){ ?>
                                                                        <h5 class="mt-3 font-18">Admin</h5>
                                                                        <?php echo 'Admin'; ?>
                                                                    <?php }else{
                                                                        ?> <h5 class="mt-3 font-18"><?php echo $row['user_name'] ?></h5>
                                                                    <?php echo $row['name'] ?> <?php
                                                                    }?>
                                                                    
                                                                </div>
                                                            </div>
                                                            <p class="text-muted m-b-30 font-14"><?php echo $row['description'] ?></p>
                            
                                                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                                
                                                                    <ol class="carousel-indicators">
                                                                        <?php 
                                                                            $images_querys = mysqli_query($conn,"SELECT * FROM post_image WHERE post_id = '$post_id'");
                                                                            $counter = 0;
                                                                            while($row_imagess = mysqli_fetch_array($images_querys)){ 
                                                                                
                                                                                if($counter == 0){
                                                                            ?>
                                                                            
                                                                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                                                            <?php } else { ?>
                                                                                <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $scounter ?>"></li>
                                                                             
                                                                            <?php } $scounter++; } ?>
                                                                    </ol>
                                                                    <div class="carousel-inner" role="listbox">
                                                                        
                                                                        <?php 
                                                                            $images_query = mysqli_query($conn,"SELECT * FROM post_image WHERE post_id = '$post_id'");
                                                                            $counter = 0;
                                                                            while($row_images = mysqli_fetch_array($images_query)){ 
                                                                                
                                                                                if($counter == 0){
                                                                            ?>
                                                                                <div class="carousel-item active">
                                                                                    <img class="d-block img-fluid" src="postImages/<?php echo $row_images['image']; ?>">
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <div class="carousel-item">
                                                                                    <img class="d-block img-fluid" src="postImages/<?php echo $row_images['image']; ?>">
                                                                                </div>
                                                                            <?php } $counter++; } ?>
                                                                    </div>
                                                                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                        <span class="sr-only">Previous</span>
                                                                    </a>
                                                                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                        <span class="sr-only">Next</span>
                                                                    </a>
                                                                        
                                                                    
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- end col -->
                                                <div class="col-lg-6">
                                                    <?php
                                                        if($row['status'] == 1 && $row['is_deleted'] == 0 ){ ?>
                                                            <a href="edit_post.php?id=<?php echo $post_id; ?>" class="btn btn-outline-success waves-effect waves-light"><i class="fa fa-edit"></i></a> 
                                                            <a href="post_details.php?id=<?php echo $post_id; ?>" class="btn btn-outline-primary waves-effect waves-light"><span class="mdi mdi-information-variant"></span></a>
													        <a href="block_post.php?id=<?php echo $post_id; ?>" class="btn btn-outline-danger waves-effect waves-light"><i class="mdi mdi-delete-forever"></i></a>
														      
                                                        <?php }
                                                        else if($row['status'] == 0 && $row['is_deleted'] == 0 ){ ?>
                                                            <a href="post_details.php?id=<?php echo $post_id; ?>" class="btn btn-outline-primary waves-effect waves-light"><span class="mdi mdi-information-variant"></span></a>
													        <a href="block_post.php?id=<?php echo $post_id; ?>" class="btn btn-outline-danger waves-effect waves-light"><i class="mdi mdi-delete-forever"></i></a>
													        <a href="activate_post.php?id=<?php echo $post_id; ?>" class="btn btn-outline-success waves-effect waves-light"><i class="mdi mdi-shield-outline"></i></a>
														    
                                                       <?php }
                                                        else if($row['status'] == 0 && $row['is_deleted'] == 1 ){ ?>
                                                            <a href="post_details.php?id=<?php echo $post_id; ?>" class="btn btn-outline-primary waves-effect waves-light"><span class="mdi mdi-information-variant"></span></a>
													        <a href="activate_post.php?id=<?php echo $post_id; ?>" class="btn btn-outline-success waves-effect waves-light"><i class="mdi mdi-shield-outline"></i></a>
														    
                                                       <?php }
                                                    ?>
                                                </div>
                            
                                            </div> <!-- end row -->
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
            
                            
                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->

                </div> <!-- content -->

<?php include_once('includes/footer_start.php'); ?>


<?php include_once('includes/footer_end.php'); ?>