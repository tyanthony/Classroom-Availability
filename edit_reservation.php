<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <!--Title for the webpage-->
  <title>Classroom Availability</title>

  <!--Sets the sylesheet-->
  <link rel="stylesheet" href="css/edit_reservation.css?v=1.0">
  <link rel="icon" href="clemson_paw.ico">

  <!-- Bootstrap stuff -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
  <!--Creates the link back to the main page-->
  <nav class="navbar navbar-custom">
    <span class="navbar-text">
      <a class="home-link" href="index.html"><h2>Classroom Availability</h2></a>
    </span>
  </nav>
  <p>
<!--PHP code to pull all of the reservations from the database for the user to select one.-->
<form method="post" action="edit_reservation_form.php">
 <?php
    try {
      $con= new PDO('mysql:host=130.127.201.224;dbname=Classroom_Availability_241v', "ub73ryi3", "exponent-impulsive-panama-doorframe-cause");
      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = "SELECT res_ID as 'Res ID', classroom_fk as 'Room', start_time as 'Start Time', end_time as 'End Time', start_date as 'Start Date', end_date as 'End Date', day_of_the_week as 'Day of the Week' FROM Reservations";
      //first pass just gets the column names
      print "<table>";
      $result = $con->query($query);
      //return only the first row (we only need field names)
      $row = $result->fetch(PDO::FETCH_ASSOC);
      print " <tr><td>Select</td>";
      foreach ($row as $field => $value){
        print " <th>$field</th>";
      } // end foreach
      print " </tr>";
      //second query gets the data
      $data = $con->query($query);
      $data->setFetchMode(PDO::FETCH_ASSOC);
      foreach($data as $row){
        print " <tr><td><input type='radio' name='res_id' value='".$row['Res ID']."'></td>";
        foreach ($row as $name=>$value){
          print " <td>$value</td> ";
        } // end field loop
        print " </tr> ";
      } // end record loop
      print "<tr><td><input type='submit' value='submit'></td></tr>";
      print "</table> ";
      } catch(PDOException $e) {
       echo 'ERROR: ' . $e->getMessage();
      } // end try
 ?>
 </form>
 </p>
</body>
</html>
