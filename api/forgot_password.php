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
     
    if (isset($_POST['email'])) {
     
        $email = $_POST['email'];
        
        $user = "SELECT user.id, user.status, user.is_deleted, user.name, user.phone, user.email, user.image, user.firebase_id, role.name AS role FROM user LEFT OUTER JOIN user_role ON user_role.user_id = user.id LEFT OUTER JOIN role ON role.id = user_role.role_id WHERE user.email = '$email'";
        

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
                        
                        $user_id = $row_user['id'];

                        $to = $email;
                        $from = 'admin@gmail.com';
                        $subject = 'Password Recovery';
                        $headers  = 'MIME-Version: 1.0' . "\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        $headers .= 'From: '.$from."\r\n".
                            'Reply-To: '.$from."\r\n" .
                            'X-Mailer: PHP/' . phpversion();
                        $message = '<html><body>';
                        $message .= '<h1 style="color:#f40;">Hi '.$row_user['name'].'</h1>';
                        $message .= '<p style="color:#080;font-size:18px;">Please click the <a href="https://www.g7technologies.com/crouch_end/api/recover_password.php?id='.$user_id.'">link</a> to reset your password.</p>';
                        $message .= '</body></html>';
                        
                        if(mail($to, $subject, $message)){
                            
                            $response["error"] = FALSE;
                            $response["success_msg"] = "Email Sent Successfully";
                            header('Content-Type: application/json');
                            echo json_encode($response);
                        }
                        else{
                            $response["error"] = TRUE;
                    	    $response["error_msg"] = "Unable to send email!";
                            header('Content-Type: application/json');
                    	    echo json_encode($response);
                        }

                        
                    }
                }
                
            }
            else {
        	    $response["error"] = TRUE;
        	    $response["error_msg"] = "Email Not Found!";
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