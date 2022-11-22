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
     
    if (isset($_POST['user_id']) && isset($_POST['post_id'])) {
     
        $user_id = $_POST['user_id'];
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

                        $like_post = "INSERT INTO liked_posts (user_id, post_id) VALUES ($user_id, $post_id)";
                        $result_like_post = mysqli_query($conn,$like_post);
                        
                        if($result_like_post){
                            
                            $response["error"] = FALSE;
                    	    $response["success_msg"] = "Liked successfully!";
                    	    
                    	    $q1 = mysqli_query($conn,"SELECT posts.user_id FROM posts WHERE posts.id = '$post_id'");
                            $q2 = mysqli_fetch_assoc($q1);
                            $q3 = $q2['user_id'];
                            $user_nam = $row_user['name'];
                            $statement = $user_nam." liked your post";
                            if($q3 != $user_id){
                                $q4 = mysqli_query($conn,"INSERT INTO notifications (notification_from,user_id,notification,post_id) VALUES ('$user_id','$q3','$statement','$post_id')");
                            }                	    
                            header('Content-Type: application/json');
                    	    echo json_encode($response);
                    	    
                        }else{
                            
                            $response["error"] = TRUE;
                    	    $response["error_msg"] = "Unable to like post!";
                            header('Content-Type: application/json');
                    	    echo json_encode($response);
                            
                        }
                    }
                }
                
            }
            else {
        	    $response["error"] = TRUE;
        	    $response["error_msg"] = "Wrong Email!";
                header('Content-Type: application/json');
        	    echo json_encode($response);
        	}
        }
        else{
            $response["error"] = TRUE;
            $response["error_msg"] = "Unable to Login..! Try Again Later";
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