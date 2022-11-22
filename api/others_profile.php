<?php

    include_once('../db/connection.php');

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        die();
            $response["error"] = TRUE;
            $response["error_msg"] = "Failed to connect to DB";
            header('Content-Type: application/json');
            echo json_encode($response);
    }
     
    $response = array("error" => FALSE);
     
    if (isset($_POST['user_id']) && isset($_POST['other_id'])) {
     
        $user_id = $_POST['user_id'];
        $other_id = $_POST['other_id'];
        
        $get_all_posts = "SELECT posts.id AS post_id, role.name AS role_name, posts.title AS post_title, posts.user_id AS post_created_by, posts.description AS post_description, DATE_FORMAT(posts.created_at, '%d %M, %Y %h:%m') AS post_time,IF(posts.user_id IS NULL, 'Admin', user.name) AS user_name, user.image AS user_image FROM posts LEFT OUTER JOIN user ON posts.user_id = user.id LEFT OUTER JOIN user_role ON user.id = user_role.user_id LEFT OUTER JOIN role ON user_role.role_id = role.id WHERE posts.status = 1 AND posts.is_deleted = 0 AND (user.status = 1 || user.status IS NULL) AND (user.is_deleted = 0 || user.is_deleted IS NULL) AND posts.user_id = '$other_id' ORDER BY posts.featured DESC, posts.created_at DESC";
        
        $result_get_all_posts = mysqli_query($conn,$get_all_posts);

        if($result_get_all_posts){

            $response["error"] = FALSE;
            $response["success_msg"] = "Posts retrieved successfully";
            $response['posts_count'] = mysqli_num_rows($result_get_all_posts);
            $response['posts'] = array();

            while($row_posts=mysqli_fetch_array($result_get_all_posts)){

                $post_id = $row_posts['post_id'];

                $liked_posts = "SELECT * FROM liked_posts WHERE user_id = '$user_id' AND post_id = '$post_id'";
                $result_liked_posts = mysqli_query($conn,$liked_posts);
                $liked_check = mysqli_num_rows($result_liked_posts);

                $posts_likes = "SELECT * FROM liked_posts WHERE post_id = '$post_id'";
                $result_posts_likes = mysqli_query($conn,$posts_likes);
                $count_likes = mysqli_num_rows($result_posts_likes);
                
                $posts_comments = "SELECT post_comments.id, role.name AS role_name, post_comments.user_id, post_comments.comment, user.name, user.image FROM post_comments INNER JOIN user ON post_comments.user_id = user.id LEFT OUTER JOIN user_role ON user.id = user_role.user_id LEFT OUTER JOIN role ON user_role.role_id = role.id WHERE post_comments.status = '1' AND user.status = '1' AND post_comments.post_id = '$post_id' ORDER BY post_comments.created_at";
                $result_posts_comments = mysqli_query($conn,$posts_comments);
                $count_comments = mysqli_num_rows($result_posts_comments);
                
                $posts_images = "SELECT * FROM post_image WHERE post_id = '$post_id'";
                $result_posts_images = mysqli_query($conn,$posts_images);
                
                if($liked_check > 0){
                    
                    $temp = array();
                    $temp["all_comments"] = array();
                    $temp["post_images"] = array();

                    $temp["post_id"] = $row_posts['post_id'];
                    $temp["post_title"] = $row_posts['post_title'];
                    $temp["post_description"] = $row_posts['post_description'];
                    $temp["post_time"] = $row_posts['post_time'];
                    $temp["post_likes"] = $count_likes;
                    $temp["post_comments"] = $count_comments;
                    $temp["post_created_by"] = $row_posts['post_created_by'];
                    $temp["user_name"] = $row_posts['user_name'];
                    $temp["role_name"] = $row_posts['role_name'];
                    
                    if($row_posts['post_created_by'] == $user_id){
                        $temp["editable"] = 1;
                    }
                    else{
                        $temp["editable"] = 2;
                    }
                    
                    if ($row_posts['user_name'] != "Admin") {
                        
                        $temp["user_image"] = $url_profile_images.''.$row_posts['user_image'];
                    }else{
                        $temp["user_image"] = $url_profile_images.'5f5dfc36bd8f7.jpg';
                    }
                    
                    $temp['liked'] = TRUE;

                    while($row_comments=mysqli_fetch_array($result_posts_comments)){
                        
                        $tem = array();
                        $comment_id = $row_comments['id'];

                        $tem['comment_id'] = $comment_id; 
                        $tem['user_id'] = $row_comments['user_id']; 
                        $tem['comment'] = $row_comments['comment']; 
                        $tem['user_name'] = $row_comments['name']; 
                        $tem['role_name'] = $row_comments['role_name']; 
                        $tem['user_image'] = $url_profile_images.''.$row_comments['image'];
                        $tem['comment_reply'] = array();
                        
                        $liked_comments = "SELECT * FROM comment_like WHERE user_id = '$user_id' AND comment_id = '$comment_id'";
                        $result_liked_comments = mysqli_query($conn,$liked_comments);
                        $liked_comment_check = mysqli_num_rows($result_liked_comments);
                        
                        $liked_comments_count = "SELECT * FROM comment_like WHERE comment_id = '$comment_id'";
                        $result_liked_comments_count = mysqli_query($conn,$liked_comments_count);
                        $liked_comment_check_count = mysqli_num_rows($result_liked_comments_count);
                        
                        if($liked_comment_check > 0){
                            $tem['liked_comment'] = TRUE; 
                        }else{
                            $tem['liked_comment'] = FALSE; 
                        }
                        
                        $tem['count_liked_comment'] = $liked_comment_check_count;
        
                        $comment_reply_query = "SELECT comment_reply.id, role.name AS role_name, comment_reply.user_id AS user_id, comment_reply.reply, DATE_FORMAT(comment_reply.created_at,'%M %d, %Y') AS created_at, user.name AS user_name, user.image AS user_image FROM comment_reply LEFT OUTER JOIN user ON comment_reply.user_id = user.id LEFT OUTER JOIN user_role ON user.id = user_role.user_id LEFT OUTER JOIN role ON user_role.role_id = role.id WHERE comment_id = '$comment_id'";
                        $result_comment_reply = mysqli_query($conn,$comment_reply_query);
                        
                        while($row_comment_reply = mysqli_fetch_array($result_comment_reply)){
                        
                            $tempo = array();
    
                            $tempo['comment_id'] = $row_comment_reply['id']; 
                            $tempo['user_id'] = $row_comment_reply['user_id']; 
                            $tempo['reply'] = $row_comment_reply['reply']; 
                            $tempo['created_at'] = $row_comment_reply['created_at'];
                            $tempo['user_name'] = $row_comment_reply['user_name'];
                            $tempo['role_name'] = $row_comment_reply['role_name'];
                            $tempo['user_image'] = $url_profile_images.''.$row_comment_reply['user_image'];
    
                            array_push($tem['comment_reply'], $tempo);
    
                        }
                        

                        array_push($temp["all_comments"], $tem);

                    }
                    while($row_images=mysqli_fetch_array($result_posts_images)){

                        array_push($temp["post_images"], $url_post_images.''.$row_images['image']);

                    }

                    array_push($response["posts"], $temp);
                }
                else{

                    $temp = array();
                    $temp["all_comments"] = array();
                    $temp["post_images"] = array();
                    
                    $temp["post_id"] = $row_posts['post_id'];
                    $temp["post_title"] = $row_posts['post_title'];
                    $temp["post_description"] = $row_posts['post_description'];
                    $temp["post_time"] = $row_posts['post_time'];
                    $temp["post_likes"] = $count_likes;
                    $temp["post_comments"] = $count_comments;
                    $temp["post_created_by"] = $row_posts['post_created_by'];
                    $temp["user_name"] = $row_posts['user_name'];
                    $temp["role_name"] = $row_posts['role_name'];
                    
                    if($row_posts['post_created_by'] == $user_id){
                        $temp["editable"] = 1;
                    }
                    else{
                        $temp["editable"] = 2;
                    }
                    
                    if ($row_posts['user_name'] != "Admin") {
                        
                        $temp["user_image"] = $url_profile_images.''.$row_posts['user_image'];
                    }else{
                        $temp["user_image"] = $url_profile_images.'5f5dfc36bd8f7.jpg';
                    }

                    $temp['liked'] = FALSE;

                    while($row_comments=mysqli_fetch_array($result_posts_comments)){
                        
                        $tem = array();
                        $comment_id = $row_comments['id'];

                        $tem['comment_id'] = $comment_id; 
                        $tem['user_id'] = $row_comments['user_id']; 
                        $tem['comment'] = $row_comments['comment']; 
                        $tem['user_name'] = $row_comments['name']; 
                        $tem['role_name'] = $row_comments['role_name']; 
                        $tem['user_image'] = $url_profile_images.''.$row_comments['image'];
                        $tem['comment_reply'] = array();
                        
                        $liked_comments = "SELECT * FROM comment_like WHERE user_id = '$user_id' AND comment_id = '$comment_id'";
                        $result_liked_comments = mysqli_query($conn,$liked_comments);
                        $liked_comment_check = mysqli_num_rows($result_liked_comments);
                        
                        $liked_comments_count = "SELECT * FROM comment_like WHERE comment_id = '$comment_id'";
                        $result_liked_comments_count = mysqli_query($conn,$liked_comments_count);
                        $liked_comment_check_count = mysqli_num_rows($result_liked_comments_count);
                        
                        if($liked_comment_check > 0){
                            $tem['liked_comment'] = TRUE; 
                        }else{
                            $tem['liked_comment'] = FALSE; 
                        }
                        
                        $tem['count_liked_comment'] = $liked_comment_check_count;
        
                        $comment_reply_query = "SELECT comment_reply.id, role.name AS role_name, comment_reply.user_id AS user_id, comment_reply.reply, DATE_FORMAT(comment_reply.created_at,'%M %d, %Y') AS created_at, user.name AS user_name, user.image AS user_image FROM comment_reply LEFT OUTER JOIN user ON comment_reply.user_id = user.id LEFT OUTER JOIN user_role ON user.id = user_role.user_id LEFT OUTER JOIN role ON user_role.role_id = role.id WHERE comment_id = '$comment_id'";
                        $result_comment_reply = mysqli_query($conn,$comment_reply_query);
                        
                        while($row_comment_reply = mysqli_fetch_array($result_comment_reply)){
                        
                            $tempo = array();
    
                            $tempo['comment_id'] = $row_comment_reply['id']; 
                            $tempo['user_id'] = $row_comment_reply['user_id']; 
                            $tempo['reply'] = $row_comment_reply['reply']; 
                            $tempo['created_at'] = $row_comment_reply['created_at'];
                            $tempo['user_name'] = $row_comment_reply['user_name'];
                            $tempo['role_name'] = $row_comment_reply['role_name'];
                            $tempo['user_image'] = $url_profile_images.''.$row_comment_reply['user_image'];
    
                            array_push($tem['comment_reply'], $tempo);
    
                        }
                        

                        array_push($temp["all_comments"], $tem);

                    }
                    
                    while($row_images=mysqli_fetch_array($result_posts_images)){
                        
                        array_push($temp["post_images"], $url_post_images.''.$row_images['image']);

                    }

                    array_push($response["posts"], $temp);
                }
            }
            header('Content-Type: application/json');
            echo json_encode($response);

        }
        else{

            $response["error"] = TRUE;
            $response["error_msg"] = "Network Error. Unable to get posts!";
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
    else{

        $response["error"] = TRUE;
        $response["error_msg"] = "Missing Parameters!";
        header('Content-Type: application/json');
        echo json_encode($response);
    }

?>