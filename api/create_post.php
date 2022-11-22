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
     
    if (isset($_POST['user_id']) && isset($_POST['description']) && isset($_POST['image'])) {
     
        $user_id = $_POST['user_id'];
        $description = $_POST['description'];
        $image = $_POST['image'];
        
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

                        $create_post = "INSERT INTO posts (user_id,title,description,featured) VALUES ('$user_id','title','$description',0)";
                        $result_create_post = mysqli_query($conn,$create_post);
                        
                        if($result_create_post){
                            
                            $get_last_post = "SELECT * FROM posts WHERE user_id = '$user_id' AND description = '$description' ORDER BY id DESC LIMIT 1";
                            $get_last_post = mysqli_query($conn,$get_last_post);
                            
                            $post_data = mysqli_fetch_assoc($get_last_post);
                            $post_id = $post_data['id'];
                            
                            $arrayofimages = explode("##", $image);
                            $ll = sizeof($arrayofimages)-1;
                            
                            for($i = 0; $i<$ll ; $i++ ){
                                
                                $folderPath = "../postImages/";
                                $image_parts = explode(";base64,", $arrayofimages[$i]);
                                $image_type_aux = explode("image/", $image_parts[0]);
                                $image_type = $image_type_aux[1];
                                $image_base64 = base64_decode($image_parts[1]);
                                $imageName = uniqid() .'.'.$image_type;
                                $file = $folderPath.$imageName;
                                file_put_contents($file, $image_base64);
                                
                                $save_image = "INSERT INTO post_image (post_id, image, status) VALUES ('$post_id','$imageName',1)";
                                $result_save_image = mysqli_query($conn,$save_image);
                                
                            }
                            
                            $response["error"] = FALSE;
                            $response["success_msg"] = "Post Created Successfully!";
                            header('Content-Type: application/json');
                            echo json_encode($response);
                        }
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
            $response["error_msg"] = "Unable to create post..! Try Again Later";
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