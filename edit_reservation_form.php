<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <!--Sets title for the webpage-->
  <title>Classroom Availability</title>
  <link rel="icon" href="clemson_paw.ico">

  <!--Sets the style sheet-->
  <link rel="stylesheet" href="css/reservation.css?v=1.0">

  <!-- Bootstrap stuff -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
</head>
<body>
  <!--Creates a link back to the main page-->
  <nav class="navbar navbar-custom">
    <span class="navbar-text">
      <a class="home-link" href="index.html"><h2>Classroom Availability</h2></a>
    </span>
    <!-- Displays certain buttons if user isnt logged in. -->
    <ul class="navbar-nav ml-auto">
      <script type="text/javascript">
          if (window.sessionStorage.getItem('user_type') !== 'logged_in') {
            document.write('<li class="nav-item ">');
            document.write('<a class="nav-link" href="login.html" id="login-button">Login</a>');
            document.write('</li>');
          }
        </script>
    </ul>
  </nav>
  <!-- Pulls the selected reservation information from the database -->
  <div class="row-1 title">
    <label>Room Reservation Edit/Update</label>
  </div>
  <?php
    //establish connection to database to query
    $con= new PDO('mysql:host=130.127.201.224;dbname=Classroom_Availability_241v', "ub73ryi3", "exponent-impulsive-panama-doorframe-cause");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM Reservations WHERE res_ID = '".$_POST['res_id']."'";
    $result = $con->query($query);
    $result->setFetchMode(PDO::FETCH_ASSOC);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    
    //get all variables from record for populating the input fields
    $resId = $_POST['res_id'];
    $classroom_fk = $row['classroom_fk'];
    $start_time = $row['start_time'];
    $end_time = $row['end_time'];
    $start_date = $row['start_date'];
    $end_date = $row['end_date'];
    $dotw = $row['day_of_the_week'];
    $reason = $row['reason'];
    $comment = $row['comment'];
  ?>
  <!-- Populates the edit reservation form with the appropriate values according to the information pulled from the DB -->
  <form id="update_res">
    <input type="hidden" id="resId" value="<?php echo $resId; ?>">
    <div class="padded">
      <div class="form-group row">
        <label for="room-selection" class="col-2 col-form-label">Room</label>
        <div class="col-3">
          <!-- The php here helps auto populate the input form -->
          <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="room" required>
            <option value="1" <?php if ($classroom_fk === '334') { echo "selected";} ?> >McAdams 114</option>
            <option value="2" <?php  if ($classroom_fk === '331') { echo "selected";} ?> >McAdams 110B</option>
            <option value="3" <?php  if( $classroom_fk === '332') { echo "selected";} ?> >McAdams 110D</option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="date-start" class="col-2 col-form-label">Start Date</label>
        <div class="col-3">
          <input class="form-control" type="date" id="s-date" value="<?php echo $start_date; ?>" required>
        </div>
        <label for="date-end" class="col-2 col-form-label">End Date</label>
        <div class="col-3">
          <input class="form-control" type="date" id="e-date" value="<?php echo $end_date; ?>" required>
        </div>
      </div>
      <div class="form-group row">
        <label for="time-start" class="col-2 col-form-label">Start Time</label>
        <div class="col-3">
          <input class="form-control" type="time" id="s-time" value="<?php echo $start_time; ?>" required>
        </div>
        <label for="time-end" class="col-2 col-form-label">End Time</label>
        <div class="col-3">
          <input class="form-control" type="time" id="e-time" value="<?php echo $end_time; ?>" required>
        </div>
      </div>
      <div class="form-group row">
        <label for="repeat-every" class="col-2 col-form-label">Repeat</label>
        <div class="col-3">
          <select multiple class="form-control" id="repeat" required>
            <option value="1"<?php if(strpos($dotw, 'M') !== false) {echo "selected";} ?> >Every Monday</option>
            <option value="2"<?php if(strpos($dotw, 'T') !== false) {echo "selected";} ?> >Every Tuesday</option>
            <option value="3"<?php if(strpos($dotw, 'W') !== false) {echo "selected";} ?> >Every Wednesday</option>
            <option value="4"<?php if(strpos($dotw, 'R') !== false) {echo "selected";} ?> >Every Thursday</option>
            <option value="5"<?php if(strpos($dotw, 'F') !== false) {echo "selected";} ?> >Every Friday</option>
            <option value="6"<?php if(strpos($dotw, 'S') !== false) {echo "selected";} ?> >Every Saturday</option>
            <option value="7"<?php if(strpos($dotw, 'U') !== false) {echo "selected";} ?> >Every Sunday</option>
          </select>
        </div>
        <label for="reason" class="col-2 col-form-label">Reason</label>
        <div class="col-3">
          <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="reason" required>
            <option selected>Select from the following</option>
            <option value="1"<?php if(strpos($reason, 'Class') !== false) {echo "selected";} ?>>Class</option>
            <option value="2"<?php if(strpos($reason, 'Meeting') !== false) {echo "selected";} ?>>Meeting</option>
            <option value="3" <?php if(strpos($dotw, 'Outside Class Activity') !== false) {echo "selected";} ?>>Outside Class Activiity</option>
            <option value="4" <?php if(strpos($dotw, 'Other') !== false) {echo "selected";} ?>>Other</option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="additional-comments" class="col-2 col-form-label">Additional Comments</label>
        <div class="col-10">
          <input class="form-control" type="text" id="a-comments" value="<?php echo $comment; ?>">
        </div>
      </div>
      <!-- Calls the add_res function in the reservation.js file to update the reservation. -->
      <div class="col-0">
        <button type="reset" class="btn btn-primary" style="float: right;" onclick="add_res(this.form, 'update')" id="submit">Submit</button>
      </div>
    </div>
  </form>
  <!-- Sets JS scripts that this HTML file uses -->
  <script src="scripts/reservation.js"></script>
  <script async defer src="https://apis.google.com/js/api.js"
          onload="this.onload=function(){};handleClientLoad()"
          onreadystatechange="if (this.readyState === 'complete') this.onload()">
  </script>
</body>
</html>
