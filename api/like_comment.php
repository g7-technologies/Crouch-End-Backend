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
     
    if (isset($_POST['user_id']) && isset($_POST['comment_id']) && isset($_POST['post_id'])) {
     
        $user_id = $_POST['user_id'];
        $comment_id = $_POST['comment_id'];
        $post_id = $_POST['post_id'];
        
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

                        $like_comment = "INSERT INTO comment_like (user_id, comment_id ) VALUES ('$user_id', '$comment_id')";
                        $result_like_comment = mysqli_query($conn,$like_comment);
                        
                        if($result_like_comment){
                            
                            $response["error"] = FALSE;
                    	    $response["success_msg"] = "Liked successfully!";
                    	    
                    	    $posts_comments = "SELECT post_comments.id, post_comments.user_id, post_comments.comment, user.name, user.image, role.name AS role_name FROM post_comments INNER JOIN user ON post_comments.user_id = user.id LEFT OUTER JOIN user_role ON user.id = user_role.user_id LEFT OUTER JOIN role ON user_role.role_id = role.id WHERE post_comments.status = '1' AND user.status = '1' AND post_comments.post_id = '$post_id' ORDER BY post_comments.created_at";
                            $result_posts_comments = mysqli_query($conn,$posts_comments);
                            
                            $response["all_comments"] = array();
                            
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
                
                                $comment_reply_query = "SELECT comment_reply.id, role.name AS role_name, comment_reply.user_id AS user_id, comment_reply.reply, DATE_FORMAT(comment_reply.created_at,'%M %d, %Y') AS created_at, user.name AS user_name, user.image AS user_image FROM comment_reply LEFT OUTER JOIN user ON comment_reply.user_id = user.id  LEFT OUTER JOIN user_role ON user.id = user_role.user_id LEFT OUTER JOIN role ON user_role.role_id = role.id WHERE comment_id = '$comment_id'";
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
                                
        
                                array_push($response["all_comments"], $tem);
        
                            }
                            
                            $q1 = mysqli_query($conn,"SELECT post_comments.user_id FROM post_comments WHERE post_comments.id = '$comment_id'");
                            $q2 = mysqli_fetch_assoc($q1);
                            $q3 = $q2['user_id'];
                            $user_nam = $row_user['name'];
                            $statement = $user_nam." liked your comment";
                            if($q3 != $user_id){
                                $q4 = mysqli_query($conn,"INSERT INTO notifications (notification_from,user_id,notification,post_id,comment_id) VALUES ('$user_id','$q3','$statement','$post_id','$comment_id')");
                            }
                            header('Content-Type: application/json');
                    	    echo json_encode($response);
                    	    
                        }else{
                            
                            $response["error"] = TRUE;
                    	    $response["error_msg"] = "Unable to like comment!";
                            header('Content-Type: application/json');
                    	    echo json_encode($response);
                            
                        }
                    }
                }
                
            }
            else {
        	    $response["error"] = TRUE;
        	    $response["error_msg"] = "No User found!";
                header('Content-Type: application/json');
        	    echo json_encode($response);
        	}
        }
        else{
            $response["error"] = TRUE;
            $response["error_msg"] = "Unable to Like..! Try Again Later";
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