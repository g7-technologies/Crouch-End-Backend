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
     
    if (isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['image']) && isset($_POST['firebase_id'])) {
     
        $name = $_POST['name'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$image = $_POST['image'];
		$firebase_id = $_POST['firebase_id'];
		$status = '1';
		$is_deleted = '0';
		
		$duplicate = mysqli_query($conn,"SELECT * FROM user WHERE email = '$email'");
		
		if( mysqli_num_rows($duplicate) == 0 ){
		    
		    $folderPath = "../profileImages/";
            $image_parts = explode(";base64,", $image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $imageName = uniqid() .'.'.$image_type;
            $file = $folderPath.$imageName;
            file_put_contents($file, $image_base64);
            
            $add_user = "INSERT INTO user( name, phone, email, password, image, firebase_id, status, is_deleted ) VALUES ( '$name', '$phone', '$email', '$password', '$imageName', '$firebase_id', '$status', '$is_deleted' )";
            
            $result_add_user = mysqli_query($conn,$add_user);
    
            if($result_add_user){
    
            	$user = "SELECT user.id, user.status, user.is_deleted, user.name, user.phone, user.email, user.image, user.firebase_id, role.name AS role FROM user LEFT OUTER JOIN user_role ON user_role.user_id = user.id LEFT OUTER JOIN role ON role.id = user_role.role_id WHERE user.email = '$email'";
            	$result_user = mysqli_query($conn,$user);
         
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
                            
                            $user_id = $row_user['id'];
                            
                            $response["error"] = FALSE;
                            $response["success_msg"] = "Signed Up Successfully";
                            $response["id"] = $user_id;
                            $response["name"] = $row_user['name'];
                            $response["phone"] = $row_user['phone'];
                            $response["email"] = $row_user['email'];
                            $response["image"] = $url_profile_images.''.$row_user['image'];
                            $response["firebase_id"] = $row_user['firebase_id'];
                            $response["role"] = $row_user['role'];
                            
                            $add_role = "INSERT INTO user_role (user_id,role_id) VALUES ('$user_id','1')";
                            $result_add_role = mysqli_query($conn,$add_role);
                            
                            header('Content-Type: application/json');
                            echo json_encode($response);
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
                $response["error_msg"] = "Unable to Signup..! Try Again Later";
                header('Content-Type: application/json');
                echo json_encode($response);
            }
		}
		else{
		    $response["error"] = TRUE;
            $response["error_msg"] = "Email Already Exists..! Try Again Later";
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