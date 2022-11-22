<?php
include('db/connection.php');
if(!isset($_SESSION['login_user'])){ 
    header("location: index.php"); // Redirecting To Home Page 
}
include_once('includes/header_start.php');

$post_id = $_GET['id'];

if(isset($_POST["change_description"])){
    
    $post_desc = $_POST["description"];
    
    $query = mysqli_query($conn,"UPDATE posts SET description = '$post_desc' WHERE id = '$post_id'");
    if($query){
        echo "<script>window.location.href='edit_post.php?id='".$post_id.";</script>";
    }else{
        $msg = 'Unable to update post..!';
    }
}

if(isset($_POST['submit'])){

    extract($_POST);
    $error=array();
    $extension=array("jpeg","jpg","png");
    foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name) {
        $file_name=$_FILES["files"]["name"][$key];
        $file_tmp=$_FILES["files"]["tmp_name"][$key];
        $ext=pathinfo($file_name,PATHINFO_EXTENSION);
    
        if(in_array($ext,$extension)) {
            
            $filename=basename($file_name,$ext);
            $newFileName= uniqid().".".$ext;
            
            if(move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key],"postImages/".$txtGalleryName."/".$newFileName)){
                
                $query = "INSERT INTO post_image (post_id,image,status) VALUES ('$post_id','$newFileName',1)";
                $result = mysqli_query($conn,$query);
                
                if($result){
                    echo "<script>window.location.href='edit_post.php?id='".$post_id.";</script>";
                }else{
                    $msg = 'Unable to upload all files';
                }
            }else {
                $msg = 'Unable to upload all files';
            }
        }
        else {
            $msg = 'Unable to upload all files';
        }
    }
}

$requests = mysqli_query($conn,"SELECT posts.id AS post_id,posts.status,posts.is_deleted, posts.description, user.name AS user_name, user.image AS profile_pic, user.id, role.name FROM posts LEFT OUTER JOIN user ON posts.user_id = user.id LEFT OUTER JOIN user_role ON user_role.user_id = user.id LEFT OUTER JOIN role ON user_role.role_id = role.id WHERE posts.id = '$post_id'");
$counter = 0;
$row = mysqli_fetch_assoc($requests);

$images_query = mysqli_query($conn,"SELECT * FROM post_image WHERE post_id = '$post_id'");


include_once('includes/header_end.php');
?>

<style>
    .img-wrap {
    position: relative;
}
.img-wrap .close {
    position: absolute;
    top: 2px;
    right: 2px;
    z-index: 100;
}
</style>


                            <!-- Page title -->
                            <ul class="list-inline menu-left mb-0">
                                <li class="list-inline-item">
                                    <button type="button" class="button-menu-mobile open-left waves-effect">
                                        <i class="ion-navicon"></i>
                                    </button>
                                </li>
                                <li class="hide-phone list-inline-item app-search">
                                    <h3 class="page-title">Edit Post</h3>
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
                                        <?php if($msg != ""){
                                          echo '<center><p class="alert alert-danger" role="alert">'.$msg.'</p></center>';
                                        } ?>
                                            <h4 class="mt-0 header-title">Edit</h4>
            
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card m-b-20">
                                                        <div class="card-body">
                                                            
                                                            <div class="media m-b-30">
                                                                <?php if($row['profile_pic'] == null){
                                                                    
                                                                }else{
                                                                    ?> <img class="d-flex mr-3 rounded-circle" src="profileImages/<?php echo $row['profile_pic'] ?>" alt="profile image" height="64"> <?php
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
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p class="text-muted m-b-30 font-14"><?php echo $row['description'] ?></p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p class="text-right m-b-30 font-14"><button class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center"><i class="fa fa-edit"></i></button> </i></p>
                                                                </div>
                                                            </div>
                                                            <diV class="row">
                                                                <?php while($row_images = mysqli_fetch_array($images_query)){ ?>
                                                                <div class="img-wrap ml-3">
                                                                    <a href="delete_image.php?post_id=<?php echo $post_id; ?>&&id=<?php echo $row_images['id']; ?>&&name=<?php echo $row_images['image']; ?>"><span class="close" style="color:#ff0000;">&times;</span></a>
                                                                    <img src="postImages/<?php echo $row_images['image'] ?>" width="150px" height="150px"/>
                                                                </div>
                                                                <?php } ?>
                                                                <div class="img-wrap ml-3">
                                                                    <button class="btn btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center1"><img src="assets/images/icon_add_image.png" width="138px" height="136px"/></button>
                                                                </div>
                                                            </diV>
                                                        </div>
                                                    </div>
                                                </div> <!-- end col -->
                                            </div> <!-- end row -->
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->

                </div> <!-- content -->
                
                <!------------------------MODAL ADD IMAGES----------------------------------------->
                <div class="modal fade bs-example-modal-center1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0">Add Post Images</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="modal-body ml-4">
                                    <div class="form-group row">
                                        <label>Select Files</label>
                                        <input type="file" class="filestyle" data-input="false" name="files[]" multiple data-buttonname="btn-secondary">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" value="Save changes" name="submit"/>
                                </div>
                            </form> 
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                
                <!------------------------MODAL UPDATE DESCCRIPTION----------------------------------------->
                <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0">Edit Description</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="POST" action="">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="description" class="col-sm-2 col-form-label">Description</label>
                                        <div class="col-sm-10">
                                            <textarea name="description" id="description" class="form-control" rows="5" required><?php echo $row['description'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" value="Save changes" name="change_description"/>
                                </div>
                            </form> 
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

<?php include_once('includes/footer_start.php'); ?>

<script src="assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>

<?php include_once('includes/footer_end.php'); ?>