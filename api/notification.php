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
     
    if (isset($_POST['user_id'])) {
     
        $user_id = $_POST['user_id'];
        
        $user = "SELECT user.id,user.status,user.is_deleted,user.password, user.name, user.phone, user.email, user.image, user.firebase_id, role.name AS role FROM user LEFT OUTER JOIN user_role ON user_role.user_id = user.id LEFT OUTER JOIN role ON role.id = user_role.role_id WHERE user.id = '$user_id'";
        

        $result_user = mysqli_query($conn,$user);

        if($result_user){
     
            if( mysqli_num_rows($result_user) > 0 ){

                $row_user = mysqli_fetch_assoc($result_user);
                
                if($row_user['is_deleted'] == 1){
                
                    $response["error"] = TRUE;
                    $response["error_msg"] = "User Deleted!";
                    header('Content-Type: application/json');
                    echo json_encode($response);
                
                }else{

                    if($row_user['status'] == 0){
                        
                        $response["error"] = TRUE;
                        $response["error_msg"] = "User Blocked!";
                        header('Content-Type: application/json');
                        echo json_encode($response);
                    
                    }else{
                        
                        $response["error"] = FALSE;
                        $response["success_msg"] = "got it!";
                        
                        $notification_query = "SELECT notifications.id , notifications.post_id,notifications.comment_id, notifications.notification, user.id AS user_id, user.name, user.image FROM notifications LEFT OUTER JOIN user ON notifications.notification_from = user.id WHERE notifications.user_id = '$user_id'";
                        $result_query = mysqli_query($conn,$notification_query);
                        
                        $response['notification'] = array();

                        while($row_noti = mysqli_fetch_array($result_query)){
                            
                            $temp = array();
                            
                            $post_id = $row_noti['post_id'];
                            
                            $temp['id'] = $row_noti['id'];
                            $temp['notification'] = $row_noti['notification'];
                            $temp['user_id'] = $row_noti['user_id'];
                            $temp['name'] = $row_noti['name'];
                            $temp['comment_index'] = $row_noti['comment_id'];
                            $temp['image'] = $url_profile_images.''.$row_noti['image'];
                            
                           $get_all_posts = "SELECT posts.id AS post_id, role.name AS role_name, posts.title AS post_title, posts.user_id AS post_created_by, posts.description AS post_description, DATE_FORMAT(posts.created_at, '%d %M, %Y %h:%m') AS post_time,IF(posts.user_id IS NULL, 'Admin', user.name) AS user_name, user.image AS user_image FROM posts LEFT OUTER JOIN user ON posts.user_id = user.id LEFT OUTER JOIN user_role ON user.id = user_role.user_id LEFT OUTER JOIN role ON user_role.role_id = role.id WHERE posts.status = 1 AND posts.id = '$post_id' AND posts.is_deleted = 0 AND (user.status = 1 || user.status IS NULL) AND (user.is_deleted = 0 || user.is_deleted IS NULL) ORDER BY posts.featured DESC, posts.created_at DESC";
        
                            $result_get_all_posts = mysqli_query($conn,$get_all_posts);
                    
                            if($result_get_all_posts){
                    
                                $temp['posts'] = array();
                    
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
                                        
                                    $tempoo = array();
                                    $tempoo["all_comments"] = array();
                                    $tempoo["post_images"] = array();
                
                                    $tempoo["post_id"] = $row_posts['post_id'];
                                    $tempoo["post_title"] = $row_posts['post_title'];
                                    $tempoo["post_description"] = $row_posts['post_description'];
                                    $tempoo["post_time"] = $row_posts['post_time'];
                                    $tempoo["post_likes"] = $count_likes;
                                    $tempoo["post_comments"] = $count_comments;
                                    $tempoo["post_created_by"] = $row_posts['post_created_by'];
                                    $tempoo["user_name"] = $row_posts['user_name'];
                                    $tempoo["role_name"] = $row_posts['role_name'];
                                    
                                    if($row_posts['post_created_by'] == $user_id){
                                        $tempoo["editable"] = 1;
                                    }
                                    else{
                                        $tempoo["editable"] = 2;
                                    }
                                    
                                    if ($row_posts['user_name'] != "Admin") {
                                        
                                        $tempoo["user_image"] = $url_profile_images.''.$row_posts['user_image'];
                                    }else{
                                        $tempoo["user_image"] = $url_profile_images.'5f5dfc36bd8f7.jpg';
                                    }
                                    
                                    if($liked_check > 0){
                                        $tempoo['liked'] = TRUE;
                                    }else{
                                        $tempoo['liked'] = FALSE;
                                    }
                
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
                                        
                
                                        array_push($tempoo["all_comments"], $tem);
                
                                    }
                                    while($row_images=mysqli_fetch_array($result_posts_images)){
                
                                        array_push($tempoo["post_images"], $url_post_images.''.$row_images['image']);
                
                                    }
                
                                    array_push($temp["posts"], $tempoo);
                                    
                                }
                    
                            }
                            else{
                    
                                $response["error"] = TRUE;
                                $response["error_msg"] = "Network Error. Unable to get posts!";
                                header('Content-Type: application/json');
                                echo json_encode($response);
                            } 
                          
                            array_push($response["notification"], $temp);
                            
                        }
                    
                        header('Content-Type: application/json');
                        echo json_encode($response);
                        
                    }
                }
                
            }
            else {
        	    $response["error"] = TRUE;
        	    $response["error_msg"] = "No User Found!";
                header('Content-Type: application/json');
        	    echo json_encode($response);
        	}
        }
        else{
            $response["error"] = TRUE;
            $response["error_msg"] = "Unable to notify..! Try Again Later";
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
    else{
        $response["error"] = TRUE;
        $response["error_msg"] = "Missing Parameters";
        header('Content-Type: application/json');
        echo json_encode($response);
    }

?>