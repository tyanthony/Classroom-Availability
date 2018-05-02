
<?php

/*
Pass.php

This file handles user input that is passed and
sends the values to the Buffet Database. 

The four cases that are associated with this file are:
(1) User Login
(2) Adding a Registration
(3) Update a Reservation
(4) Registration (adding an account)- we were unable to get to this
													on the front end, however the php
													has been completed for this additional
													functionality
 */
  header("Content-Type: application/json");
  $result = array();
  if( !isset($_POST['functionname']) ) { $result['error'] = 'No function name!'; }
  if( !isset($result['error']) ) {
    switch($_POST['functionname']) {
			 
    //If the user is logging into system  
		case "login": 
        if( !isset($_POST['user_name']) || !isset($_POST['pass_word'])) {
          $result['error'] = 'Error in arguments!';
        }
        else {
          $serverName = "130.127.201.224";
          $serverUsername = "ub73ryi3";
          $serverPassword = "exponent-impulsive-panama-doorframe-cause";
          $databaseName = "Classroom_Availability_241v";
          $username = $_POST['user_name'];
          $password = $_POST['pass_word'];
          $conn = mysqli_connect($serverName, $serverUsername, $serverPassword, $databaseName) or die("Connection failed:(" . mysql_error($conn));
          $sql = "SELECT pass_hash FROM User WHERE username = '" . $username . "'";
          $queryResult = $conn->query($sql);
          if ($queryResult->num_rows > 0) {
            while ($row = $queryResult->fetch_assoc()) {
              $hash = $row["pass_hash"];
              $auth = password_verify($password, $hash);
              if ($auth) $result["result"] = TRUE;
            }
          } else {
            $result["error"] = $queryResult;
          }
          $conn->close();
        }
        break;
			 
		//if the user is adding a reservation
      case "addRes":
        $serverName = "130.127.201.224";
        $serverUsername = "ub73ryi3";
        $serverPassword = "exponent-impulsive-panama-doorframe-cause";
        $databaseName = "Classroom_Availability_241v";
        if (!isset($_POST['room']) ||
            !isset($_POST['start_dt']) ||
            !isset($_POST['end_dt']) ||
            !isset($_POST['start_tm']) ||
            !isset($_POST['end_tm']) ||
            !isset($_POST['day']) ||
            !isset($_POST['reason']) ||
            !isset($_POST['comment']))
        {
            $result['error'] = "Argument Error";    
        }
        else {
            $room = $_POST['room'];
            $start_date = $_POST['start_dt'];
            $end_date = $_POST['end_dt'];
            $start_time = $_POST['start_tm'];
            $end_time = $_POST['end_tm'];
            $days = $_POST['day'];
            $reason = $_POST['reason'];
            $comment = $_POST['comment'];
			  	$event = $_POST['event_id'];
            $conn = mysqli_connect($serverName, $serverUsername, $serverPassword, $databaseName) or die("Connection failed:(" . mysql_error($conn));  
            $days_len = strlen($days);
            for($i = 0; $i < $days_len; $i++) {
                $day = substr($days, $i, 1);
                $checkQuery = "SELECT * FROM Reservations 
                                WHERE classroom_fk='$room' 
                                AND ((start_time < '$start_time' AND end_time > '$start_time') OR (start_time < '$end_time' AND end_time > '$end_time'))
                                AND day_of_the_week LIKE '%$day%'
                                AND ((start_date < '$start_date' AND end_date > '$start_date') OR (start_date < '$end_date' AND end_date > '$end_date'))";
                $checkResult = $conn->query($checkQuery);
                //echo $queryResult;
                if ($checkResult->num_rows > 0) {
                    $result['error'] = 'Conflicting Reservation';
                }
            }
            if (!isset($result['error'])) {
                $query = "INSERT INTO Reservations (classroom_fk, user_fk, start_time, end_time, start_date, end_date, day_of_the_week, reason, comment, is_private, event_id) VALUES ('$room' , '1', '$start_time', '$end_time', '$start_date', '$end_date', '$days','$reason', '$comment', '0', '$event')";
                $queryResult = $conn->query($query);
                if($queryResult) {
                    $result['result'] = TRUE;
                }
                else {
                    $result['error'] = 'Insert ERROR?';
                }
            }
            $conn->close();
        }
        break;
		
	  //if the user is updating a reservation
    case "update_res":
        $serverName = "130.127.201.224";
        $serverUsername = "ub73ryi3";
        $serverPassword = "exponent-impulsive-panama-doorframe-cause";
        $databaseName = "Classroom_Availability_241v";
        if (!isset($_POST['resId']) ||
            !isset($_POST['room']) ||
            !isset($_POST['start_dt']) ||
            !isset($_POST['end_dt']) ||
            !isset($_POST['start_tm']) ||
            !isset($_POST['end_tm']) ||
            !isset($_POST['day']) ||
            !isset($_POST['reason']) ||
            !isset($_POST['comment']))
        {
            $result['error'] = "Argument Error";    
        }
        else {
            $resId = $_POST['resId'];
            $room = $_POST['room'];
            $start_date = $_POST['start_dt'];
            $end_date = $_POST['end_dt'];
            $start_time = $_POST['start_tm'];
            $end_time = $_POST['end_tm'];
            $days = $_POST['day'];
            $reason = $_POST['reason'];
            $comment = $_POST['comment'];            
            
            $conn = mysqli_connect($serverName, $serverUsername, $serverPassword, $databaseName) or die("Connection failed:(" . mysql_error($conn));  
            $days_len = strlen($days);
            for($i = 0; $i < $days_len; $i++) {
                $day = substr($days, $i, 1);
                $checkQuery = "SELECT * FROM Reservations 
                                WHERE classroom_fk='$room' 
                                AND ((start_time < '$start_time' AND end_time > '$start_time') OR (start_time < '$end_time' AND end_time > '$end_time'))
                                AND day_of_the_week LIKE '%$day%'
                                AND ((start_date < '$start_date' AND end_date > '$start_date') OR (start_date < '$end_date' AND end_date > '$end_date'))";
                $checkResult = $conn->query($checkQuery);
                //echo $queryResult;
                if ($checkResult->num_rows > 1) {
                    $result['error'] = 'Conflicting Reservation';
                }
                else if ($checkResult->num_rows > 0) {
                    $row = $checkResult->fetch_assoc();
                    if ($row['res_ID'] != $resId) {
                        $result['error'] = 'Conflicting Reservation';
                    }
                }
            }
            if (!isset($result['error'])) {
                $query = "UPDATE Reservations 
                                SET classroom_fk = '$room', start_time = '$start_time', end_time = '$end_time', start_date = '$start_date', 
                                    end_date = '$end_date', day_of_the_week = '$days', reason = '$reason', comment = '$comment' 
                                WHERE res_ID = '$resId'";
                $queryResult = $conn->query($query);
                if($queryResult) {
                    $result['result'] = TRUE;
						 	$eventQuery="SELECT event_id FROM Reservations WHERE res_ID = '$resId'";
						 	$eventResult = $conn->query($eventQuery);
						 	$rowEvent = $eventResult->fetch_assoc();
						 	$result['event_id'] = $rowEvent['event_id'];
                }
                else {
                    $result['error'] = 'Update ERROR?';
                    $result['query'] = $query;
                    $result['resid'] = $resId;
                    $result['classroom'] = $room;
                    $result['startdate'] = $start_date;
                    $result['enddate'] = $end_date;
                    $result['starttime'] = $start_time;
                    $result['end_time'] = $end_time;
                    $result['days'] = $days;
                    $result['reason'] = $reason;
                    $result['comment'] = $comment;
                    $result['message'] = mysqli_error($conn);
                }
            }
            $conn->close();
        }
        break;
			 
		//if the user is registering for the system (creating an account)
      case "registration":
        //Assumes password is the confirmed passsword choice
        if(!isset($_POST['user_name']) ||
           !isset($_POST['pass_word']) ||
           !isset($_POST['email'])) {
            $result['error'] = "Error in Arguments";
           }
        else {
            $username = $_POST['user_name'];
            $password = $_POST['pass_word'];
            $email = $_POST['email'];
            $serverName = "130.127.201.224";
            $serverUsername = "ub73ryi3";
            $serverPassword = "exponent-impulsive-panama-doorframe-cause";
            $databaseName = "Classroom_Availability_241v";
            
            //Make sure there is not a username like this in the database
            $checkUsernameQuery = "SELECT * FROM User WHERE username = '$username'";
            $conn = mysqli_connect($serverName, $serverUsername, $serverPassword, $databaseName) or die("Connection failed:(" . mysql_error($conn));  
            $usernameResult = $conn->query($checkUsernameQuery);
            if ($usernameResult->num_rows > 0) {
                $result['error'] = 'Username Already Taken';
            }
            $checkEmailQuery = "SELECT * FROM User WHERE username = '$email'";
            $emailResult = $conn->query($checkEmailQuery);
            if ($emailResult->num_rows > 0) {
                $result['error'] = 'Email Already Taken';
            } 
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $result['error'] = 'Invalid Email';
            }
            if (!isset($result['error'])) {
                $createUserQuery = "INSERT INTO User (username, password, email) VALUES ('$username', '". password_hash($password)."'";
                $conn->query($createUserQuery);
            }
            
        }
        break;
      default:
        $result['error'] = 'Not found function '.$_POST['functionname'].'!';
        break;
    }
    echo json_encode($result);
  }
?>
