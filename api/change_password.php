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
     
    if (isset($_POST['user_id']) &&isset($_POST['password']) && isset($_POST['new_password'])) {
     
        $user_id = $_POST['user_id'];
        $password = $_POST['password'];
        $new_password = $_POST['new_password'];
        
        $user = "SELECT * FROM user WHERE id = '$user_id'";
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

                        if($row_user['password'] == $password){

                            $chng_pass = "UPDATE user SET password = '$new_password' WHERE id = '$user_id'";
                            $result_chng_pass = mysqli_query($conn,$chng_pass);

                            if($result_chng_pass){

                                $response["error"] = FALSE;
                                $response["success_msg"] = "Password Changed Successfully";
                                header('Content-Type: application/json');
                                echo json_encode($response);

                            }
                            else{

                                $response["error"] = TRUE;
                                $response["error_msg"] = "Unable to change password!";
                                header('Content-Type: application/json');
                                echo json_encode($response);
                            }

                        }else{

                            $response["error"] = TRUE;
                            $response["error_msg"] = "Wrong Password!";
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