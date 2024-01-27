<?php  
//Connect to database
require 'connectDB.php';

if (isset($_POST['FingerID'])) {
	
	$fingerID = $_POST['FingerID'];

	$sql = "SELECT * FROM fingerprint WHERE fingerprint_id=?";
    $result = mysqli_stmt_init($conn1);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error_Select_card";
        exit();
    }
    else{
    	mysqli_stmt_bind_param($result, "s", $fingerID);
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)){
        	//*****************************************************
            //An existed fingerprint has been detected for Login or Logout
            if (!empty($row['emp_id'])){
            	$emp_id = $row['emp_id'];
            	$user_name = $row['user_name'];
                $sql = "SELECT * FROM time_keeping WHERE fingerprint_id=? AND timekeep_day=CURDATE() AND out_afternoon=''";
                $result = mysqli_stmt_init($conn1);
                if (!mysqli_stmt_prepare($result, $sql)) {
                    echo "SQL_Error_Select_logs";
                    exit();
                }
                else{
                	mysqli_stmt_bind_param($result, "i", $fingerID);
                    mysqli_stmt_execute($result);
                    $resultl = mysqli_stmt_get_result($result);
                    //*****************************************************
                    //Login
                    if (!$row = mysqli_fetch_assoc($resultl)){

                    	$sql = "INSERT INTO time_keeping (emp_id, fingerprint_id, timekeep_day, in_morning, out_afternoon) VALUES (?, ?, CURDATE(), CURTIME(), ?)";
                        $result = mysqli_stmt_init($conn1);
                        if (!mysqli_stmt_prepare($result, $sql)) {
                            echo "SQL_Error_Select_login1";
                            exit();
                        }
                        else{
                        	$timeout = "";
                            mysqli_stmt_bind_param($result, "iis", $emp_id, $fingerID, $timeout);
                            mysqli_stmt_execute($result);

                            echo "login".$user_name;
                            exit();
                        }
                    }
                    //*****************************************************
                    //Logout
                    else{

                            $update_sql = "UPDATE time_keeping SET out_afternoon = CURTIME() WHERE fingerprint_id = ? AND timekeep_day = CURDATE()";
                            $update_result = mysqli_stmt_init($conn1);
                
                            if (!mysqli_stmt_prepare($update_result, $update_sql)) {
                                echo "SQL_Error_insert_logout1";
                                exit();
                            }
                            else {
                                mysqli_stmt_bind_param($update_result, "i", $fingerID);
                                mysqli_stmt_execute($update_result);

                                // Display the time difference
                                echo "logout" . $user_name;
                                mysqli_stmt_close($update_result);


                                $select_sql = "SELECT in_morning, out_afternoon FROM time_keeping WHERE fingerprint_id = $fingerID AND timekeep_day = CURDATE()";
                                $select_result = mysqli_query($conn1, $select_sql);

                                if (!$select_result) {
                                    echo "SQL_Error_fetch_time_in: " . mysqli_error($conn1);
                                    exit();
                                } 
                                else {

                                    
                                    $time_in_row = mysqli_fetch_assoc($select_result);
                                    $time_in = $time_in_row['in_morning'];
                                    $time_out = $time_in_row['out_afternoon'];
                                    
                                    $time_in_obj = new DateTime($time_in);
                                    $time_out_obj = new DateTime($time_out);
                                    $time_difference = $time_in_obj->diff($time_out_obj);
                                    $hours_work = $time_difference->h + ($time_difference->i / 60);

                                    // echo "" . $time_in;
                                    // echo "" . $time_out_obj->format('H:i:s');
                                    // echo "" . $hours_work;


                                    $check_holiday_sql = "SELECT * FROM holidays WHERE holiday_DATE = CURDATE()";
                                    $check_holiday_result = mysqli_query($conn1, $check_holiday_sql);

                                    if ($check_holiday_result && mysqli_num_rows($check_holiday_result) > 0) {
                                        $holiday_row = mysqli_fetch_assoc($check_holiday_result);
                                        $holiday_type = $holiday_row['holiday_TYPE'];
                                    } else {
                                        $holiday_type = 'NORMAL'; // Assume it's a regular day if not a holiday
                                    }


                

                                    switch ($holiday_type) {
                                        case 'Special Holiday':
                                            // Update sh_hours for special holiday
                                            $update_sh_hours_sql = "UPDATE time_keeping SET hours_work=$hours_work, sh_hours = $hours_work WHERE fingerprint_id = $fingerID AND timekeep_day = CURDATE()";
                                            $update_sh_hours_result = mysqli_query($conn1, $update_sh_hours_sql);
                                    
                                            if (!$update_sh_hours_result) {
                                                echo "error sh hours " . mysqli_error($conn1);
                                                exit();
                                            }
                                           
                                            break;
                                    
                                        case 'Regular Holiday':
                                            // Update rh_hours for regular holiday
                                            $update_rh_hours_sql = "UPDATE time_keeping SET hours_work=$hours_work, rh_hours = $hours_work WHERE fingerprint_id = $fingerID AND timekeep_day = CURDATE()";
                                            $update_rh_hours_result = mysqli_query($conn1, $update_rh_hours_sql);

                                            if (!$update_rh_hours_result) {
                                                echo "error rh hours " . mysqli_error($conn1);
                                                exit();
                                            }

                                            break;
                                    
                                        default: 
                                            // Update hours_work for the default case
                                            $update_work_hours_sql = "UPDATE time_keeping SET hours_work=$hours_work WHERE fingerprint_id = $fingerID AND timekeep_day = CURDATE()";
                                            $update_work_hours_result = mysqli_query($conn1, $update_work_hours_sql);

                                            if (!$update_work_hours_result) {
                                                echo "SQL_Error_update_work_hours: " . mysqli_error($conn1);
                                                exit();
                                            }
                                            break;
                                    }


                                    $expected_start_time = new DateTime('08:00:00');
                                    $late_hours = $time_in_obj->diff($expected_start_time);

                                    // Calculate late minutes and ensure late_hours_decimal is not negative
                                    $late_minutes = $late_hours->h * 60 + $late_hours->i;
                                    $late_hours_decimal = max($late_minutes / 60, 0);

                                    $expected_hours = 9.0;
                                    $undertime_hours = max($expected_hours - $hours_work, 0);

                                    // Update the database with late hours
                                    $update_query = "UPDATE time_keeping SET late_hours = $late_hours_decimal, undertime_hours = $undertime_hours WHERE fingerprint_id = $fingerID AND timekeep_day = CURDATE()";
                                    $update_result = mysqli_query($conn1, $update_query);

                                    if (!$update_result) {
                                        echo "SQL_Error_update_late_hours: " . mysqli_error($conn1);
                                        exit();
                                    }

                                    $sqldtr = "INSERT INTO dtr (emp_id, fingerprint_id, in_morning, out_afternoon, hours_worked, dtr_day) VALUES ($emp_id, $fingerID, '$time_in', '$time_out', $hours_work, CURDATE())";

                                    $result_dtr = mysqli_query($conn1, $sqldtr);

                                    if (!$result_dtr) {
                                        echo "SQL_Error: " . mysqli_error($conn1);
                                        exit();
                                    }

                                    
    
                        }


            



                    	// $sql="UPDATE time_keeping SET out_afternoon=CURTIME() WHERE fingerprint_id=? AND timekeep_day=CURDATE()";
                        // $result = mysqli_stmt_init($conn1);
                        // if (!mysqli_stmt_prepare($result, $sql)) {
                        //     echo "SQL_Error_insert_logout1";
                        //     exit();
                        // }
                        // else{
                        //     mysqli_stmt_bind_param($result, "i", $fingerID);
                        //     mysqli_stmt_execute($result);

                        //     echo "logout".$user_name;
                        //     exit();
                        // }
                    }
                }
            }

        }
        
        //*****************************************************
        //New Fingerprint has been added
        
    }
    
}
}
if (isset($_POST['Get_Fingerid'])) {
    
    if ($_POST['Get_Fingerid'] == "get_id") {
        $sql= "SELECT fingerprint_id FROM fingerprint WHERE add_fingerid=1";
        $result = mysqli_stmt_init($conn1);
        if (!mysqli_stmt_prepare($result, $sql)) {
            echo "SQL_Error_Select";
            exit();
        }
        else{
            mysqli_stmt_execute($result);
            $resultl = mysqli_stmt_get_result($result);
            if ($row = mysqli_fetch_assoc($resultl)) {
                echo "add-id".$row['fingerprint_id'];
                exit();
            }
            else{
                echo "Nothing";
                exit();
            }
        }
    }
    else{
        exit();
    }
}
if (!empty($_POST['confirm_id'])) {

    $fingerid = $_POST['confirm_id'];

    // $sql="UPDATE fingerprint SET fingerprint_select=0 WHERE fingerprint_select=1";
    // $result = mysqli_stmt_init($conn);
    // if (!mysqli_stmt_prepare($result, $sql)) {
    //     echo "SQL_Error_Select";
    //     exit();
    // }
    // else{
        // mysqli_stmt_execute($result);
        
        $sql="UPDATE fingerprint SET add_fingerid=0 WHERE fingerprint_id=?";
        $result = mysqli_stmt_init($conn1);
        if (!mysqli_stmt_prepare($result, $sql)) {
            echo "SQL_Error_Select";
            exit();
        }
        else{
            mysqli_stmt_bind_param($result, "s", $fingerid);
            mysqli_stmt_execute($result);
            echo "Fingerprint has been added!";

            exit();
        // }
    }  
}
if (isset($_POST['DeleteID'])) {

	if ($_POST['DeleteID'] == "check") {
        $sql = "SELECT fingerprint_id FROM users WHERE del_fingerid=1";
        $result = mysqli_stmt_init($conn1);
        if (!mysqli_stmt_prepare($result, $sql)) {
            echo "SQL_Error_Select";
            exit();
        }
        else{
            mysqli_stmt_execute($result);
            $resultl = mysqli_stmt_get_result($result);
            if ($row = mysqli_fetch_assoc($resultl)) {
                
                echo "del-id".$row['fingerprint_id'];

                $sql = "DELETE FROM users WHERE del_fingerid=1";
                $result = mysqli_stmt_init($conn1);
                if (!mysqli_stmt_prepare($result, $sql)) {
                    echo "SQL_Error_delete";
                    exit();
                }
                else{
                    mysqli_stmt_execute($result);
                    exit();
                }
            }
            else{
                echo "nothing";
                exit();
            }
        }
	}
	else{
		exit();
	}
}
?>