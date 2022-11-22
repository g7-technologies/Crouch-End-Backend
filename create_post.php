<?php 

    include_once('db/connection.php');
    if(!isset($_SESSION['login_user'])){ 
        header("location: index.php"); // Redirecting To Home Page 
    }
    include_once('includes/header_start.php');
    $msg="";
    
if(isset($_POST['submit'])){

    $title = 'title';
    $description = $_POST['description'];
    $query = "INSERT INTO posts (title,description,featured) VALUES ('$title','$description',1)";
    $result = mysqli_query($conn,$query);
    
    $que = "SELECT * FROM posts WHERE user_id IS NULL AND description = '$description' AND featured = 1 ORDER BY created_at DESC LIMIT 1";
    $po = mysqli_query($conn,$que);
    $r = mysqli_fetch_assoc($po);
    $post_id = $r['id'];
    
    
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
                    echo "<script>window.location.href='active_posts.php';</script>";
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
    
      

?>


<?php include_once('includes/header_end.php'); ?>

                            <ul class="list-inline menu-left mb-0">
                                <li class="list-inline-item">
                                    <button type="button" class="button-menu-mobile open-left waves-effect">
                                        <i class="ion-navicon"></i>
                                    </button>
                                </li>
                                <li class="hide-phone list-inline-item app-search">
                                    <h3 class="page-title">Create Post</h3>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </nav>

                    </div>
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
                                            <h4 class="mt-0 header-title">Create Post</h4>
                                            <form method="POST" action="" enctype="multipart/form-data">
                                                <div class="form-group row">
                                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                                    <div class="col-sm-10">
                                                        <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Select Files</label>
                                                    <input type="file" class="filestyle" data-input="false" name="files[]" multiple data-buttonname="btn-secondary">
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4"></div>
                                                    <div class="col-sm-2 text-right">
                                                        <Button class="btn btn-outline-success waves-effect waves-light" name="submit" style="width: 100%">Create Post</Button>
                                                    </div>
                                                    <div class="col-sm-2 text-left">
                                                        <a  style="width: 100%" href="Dashboard.php" class="btn btn-outline-danger waves-effect waves-light">Cancel</a>
                                                    </div>
                                                    <div class="col-sm-4"></div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                        </div><!-- container -->
                    </div> <!-- Page content Wrapper -->

<?php include_once('includes/footer_start.php'); ?>

        <script src="assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>
<?php include_once('includes/footer_end.php'); ?>