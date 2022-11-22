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

                        $all_user = "SELECT user.id,user.status,user.is_deleted,user.password, user.name, user.phone, user.email, user.image, user.firebase_id, role.name AS role FROM user LEFT OUTER JOIN user_role ON user_role.user_id = user.id LEFT OUTER JOIN role ON role.id = user_role.role_id WHERE user.id NOT IN (SELECT id FROM user WHERE id = '$user_id') ";
                        $result_all_users = mysqli_query($conn,$all_user);
                        
                        $response["error"] = FALSE;
                        $response["success_msg"] = "Logged In Successfully";
                        $response['users'] = array();
                                                
                        while($row_users=mysqli_fetch_array($result_all_users)){
                            
                            $temp = array();
                            
                            $temp["id"] = $row_users['id'];
                            $temp["name"] = $row_users['name'];
                            $temp["image"] = $url_profile_images.''.$row_users['image'];
                            $temp["role_name"] = $row_users['role'];
                            
                            array_push($response["users"], $temp);
                        }
                        
                        header('Content-Type: application/json');
                        echo json_encode($response);
                    }
                }
                
            }
            else {
        	    $response["error"] = TRUE;
        	    $response["error_msg"] = "User not found!";
                header('Content-Type: application/json');
        	    echo json_encode($response);
        	}
        }
        else{
            $response["error"] = TRUE;
            $response["error_msg"] = "Unable to get Data..! Try Again Later";
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