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

                        $post = "SELECT * FROM posts WHERE id = '$post_id'";
                        $result_post = mysqli_query($conn,$post);
                        
                        if($result_post){
     
                            $row_post = mysqli_fetch_assoc($result_post);
                            
                            if($row_post['is_deleted'] == 1){
                            
                                $response["error"] = TRUE;
                                $response["error_msg"] = "Post Already Deleted!";
                                header('Content-Type: application/json');
                                echo json_encode($response);
                            
                            }else{
            
                                if($row_user['status'] == 0){
                                    
                                    $response["error"] = TRUE;
                                    $response["error_msg"] = "Post Blocked!";
                                    header('Content-Type: application/json');
                                    echo json_encode($response);
                                
                                }else{
            
                                    $report = "SELECT * FROM post_reports WHERE post_id = '$post_id' AND user_id = '$user_id'";
                                    $result_report = mysqli_query($conn,$report);
                                    
                                    if(mysqli_num_rows($result_report) > 0){
                                        
                                        $response["error"] = TRUE;
                                        $response["error_msg"] = "Post already reported!";
                                        header('Content-Type: application/json');
                                        echo json_encode($response);  
                                    }
                                    else{
                                        
                                        $submit_report = "INSERT INTO post_reports (post_id,user_id) VALUES ('$post_id','$user_id')";
                                        $result_submit_report = mysqli_query($conn,$submit_report);
                                        
                                        if($result_submit_report){
                                            
                                            $response["error"] = FALSE;
                                            $response["success_msg"] = "Post reported..!";
                                            header('Content-Type: application/json');
                                            echo json_encode($response);
                                            
                                        }else{
                                            
                                            $response["error"] = TRUE;
                                            $response["error_msg"] = "Unable to Report..!";
                                            header('Content-Type: application/json');
                                            echo json_encode($response);
                                            
                                        }
                                    }
                                }
                            }
                        }
                        else{
                            $response["error"] = TRUE;
                            $response["error_msg"] = "Unable to Report..! Try Again Later";
                            header('Content-Type: application/json');
                            echo json_encode($response);
                        }
                    }
                }
                
            }
            else {
        	    $response["error"] = TRUE;
        	    $response["error_msg"] = "No user found!";
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