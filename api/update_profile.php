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
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $image = $_POST['image'];
        
        $user = "SELECT user.id, user.status, user.is_deleted, user.name, user.phone, user.email, user.image, user.firebase_id, role.name AS role_name FROM user LEFT OUTER JOIN user_role ON user_role.user_id = user.id LEFT OUTER JOIN role ON role.id = user_role.role_id WHERE user.id = '$user_id'";
        
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
                        
                        if($image == ''){
                            $update = "UPDATE user SET name = '$name', phone = '$phone' WHERE id = '$user_id'";
                            $result_update = mysqli_query($conn,$update);
                            
                        }else{
                            $folderPath = "../profileImages/";
                            $image_parts = explode(";base64,", $image);
                            $image_type_aux = explode("image/", $image_parts[0]);
                            $image_type = $image_type_aux[1];
                            $image_base64 = base64_decode($image_parts[1]);
                            $imageName = uniqid() .'.'.$image_type;
                            $file = $folderPath.$imageName;
                            file_put_contents($file, $image_base64);
    
                            $update = "UPDATE user SET name = '$name', phone = '$phone' , image = '$imageName' WHERE id = '$user_id'";
                            $result_update = mysqli_query($conn,$update);
                        }

                        if($result_update){

                            $response["error"] = FALSE;
                            $response["success_msg"] = "Profile Updated Successfully";
                            $response["id"] = $row_user['id'];
                            $response["name"] = $name;
                            $response["phone"] = $row_user['phone'];
                            $response["email"] = $row_user['email'];
                            $response["image"] = $url_profile_images.''.$row_user['image'];
                            $response["firebase_id"] = $row_user['firebase_id'];
                            $response["role_name"] = $row_user['role_name'];

                            header('Content-Type: application/json');
                            echo json_encode($response);
                        }
                        else{

                            $response["error"] = TRUE;
                            $response["success_msg"] = "Unable To Update Profile";
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