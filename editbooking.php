<?php
    include "checksession.php";
    checkUser();
    $user=passUser();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Edit a booking</title>
    <!-- Bootstrap core CSS and Scripts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Custom styles for this page -->
    <link href='./styles/ongaongabandb.css' rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


    <!-- Custom scripts for this page -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src='./js/datepicker.js'></script>

  </head>

  <body>
    <header class ="container-fluid">
        <h1>Ongaonga Bed &amp; Breakfast</h1>
        <p>Make yourself at home is our slogan. We offer some of the best beds on the East Coast. Sleep well and rest well.</p>
        <br/>
    </header>

    <nav class="navbar navbar-expand-sm bg-black navbar-dark">   
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>   
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav"  id="navigation">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>           
                </li>
                <?php 
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {     
                    echo '<li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="listrooms.php" id="navbardrop" data-toggle="dropdown">Rooms</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="addroom.php">Add A New Room</a>
                            <a class="dropdown-item" href="listrooms.php">Room List</a>
                            </div>
                            </li>';
                    echo '<li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="makebooking.php" id="navbardrop" data-toggle="dropdown">Bookings</a>
                            <div class="dropdown-menu">
                            <a class="dropdown-item" href="makebooking.php">Make A New Booking</a>
                            <a class="dropdown-item active" href="currentbookings.php">Current Bookings List</a>
                            </div>
                            </li>';
                    
                    echo '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="registercustomer.php" id="navbardrop" data-toggle="dropdown">Register</a>
                            <div class="dropdown-menu">
                            <a class="dropdown-item" href="registercustomer.php">Register A New Customer</a>
                            <a class="dropdown-item" href="listcustomers.php">Customer List</a>
                            </div>
                            </li>';
                }
                else {
                    echo '<li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="listrooms.php" id="navbardrop" data-toggle="dropdown">Rooms</a>
                            <div class="dropdown-menu">
                            <a class="dropdown-item" href="listrooms.php">Room List</a>
                            </div>
                            </li>';   
                        
                    echo '<li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="makebooking.php" id="navbardrop" data-toggle="dropdown">Bookings</a>
                            <div class="dropdown-menu">
                            <a class="dropdown-item" href="makebooking.php">Make A New Booking</a>
                            </div>
                            </li>'; 
                    
                    echo '<li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="registercustomer.php" id="navbardrop" data-toggle="dropdown">Register</a>
                            <div class="dropdown-menu">
                            <a class="dropdown-item" href="registercustomer.php">Register A New Customer</a>
                            </div>
                            </li>'; 
                }
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </nav>


    <main role="main" class="container">

    <?php
        include "config.php"; //load in any variables
        $DBC = mysqli_connect(DBHOST, DBUSER , DBPASSWORD, DBDATABASE);
 
        //check if the connection was good
        if (!$DBC) {
            echo "Error: Unable to connect to MySQL.\n". mysqli_connect_errno()."=".mysqli_connect_error() ;
            exit; //stop processing the page further
        }

        // function to clean input but not to validate type and content
        function cleanInput($data) {  
            return htmlspecialchars(stripslashes(trim($data)));
        }

        //retrieve the memberid from the URL
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $id = $_GET['id'];
            if (empty($id) or !is_numeric($id)) {
                echo "<h2>Invalid Booking ID</h2>"; //simple error feedback
                exit;
            } 
        }

        //the data was sent using a form therefore we use the $_POST instead of $_GET
        //check if we are saving data first by checking if the submit button exists in the array
        if (isset($_POST['submit']) and !empty($_POST['submit']) 
        and ($_POST['submit'] == 'Update')) {     
            //validate incoming data - 
            $error = 0; //clear our error flag
            $msg = 'Error: ';

            //bookingID (sent via a form it is a string not a number so we try a type conversion)    
            if (isset($_POST['id']) and !empty($_POST['id']) 
            and is_integer(intval($_POST['id']))) {
                $id = cleanInput($_POST['id']); 
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid booking ID '; //append error message
                $id = 0;  
            } 

            // room ID
            if (isset($_POST['roomID']) and !empty($_POST['roomID'])) {
              $roomID = cleanInput($_POST['roomID']); 
            } else {
              $error++; //bump the error flag
              $msg .= 'Invalid Room ID '; //append error message
              $roomID = 0;
            } 

            // Check In Date
            if (isset($_POST['checkin']) and !empty($_POST['checkin'])) {      
              $checkin = cleanInput($_POST['checkin']);  
            } else {
              $error++; //bump the error flag
              $msg .= 'Invalid Check In Date '; //append error message
            } 

            // Check Out Date
            if (isset($_POST['checkout']) and !empty($_POST['checkout'])) { 
              $checkout = cleanInput($_POST['checkout']);  
            } else {
              $error++; //bump the error flag
            $msg .= 'Invalid Check Out Date '; //append error message
            } 
      
            // Contact phone number
            if (isset($_POST['contactphone']) and !empty($_POST['contactphone'])) {
              $contactphone = cleanInput($_POST['contactphone']);  
            } else {
              $error++; //bump the error flag
              $msg .= 'Invalid Phone Number'; //append error message
              $contactphone = '';
            } 

            // Bookings Extras
            if (isset($_POST['bookingextras']) and is_string($_POST['bookingextras'])) {
              if(empty($_POST['bookingextras']) or $_POST['bookingextras'] == 'nothing'){
                  $bookingextras = null;
              } else {
                  $fn = cleanInput($_POST['bookingextras']);
                  $bookingextras = $fn;
              }
          } else {
              $error++; //bump the error flag
              $msg .= 'Invalid Booking Extras '; //append error message
              $bookingextras = '';
          } 

            // Room review
            if (isset($_POST['roomreview']) and is_string($_POST['roomreview'])) {
                if(empty($_POST['roomreview']) or $_POST['roomreview'] == 'nothing'){
                    $roomreview = null;
                } else {
                    $fn = cleanInput($_POST['roomreview']);
                    $roomreview = $fn;
                }
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid Room Review '; //append error message
                $bookingextras = '';
            } 

            //save the booking data if the error flag is still clear and member id is > 0
            if ($error == 0 and $id > 0) {                  
                $query = "UPDATE bookings SET roomreview=?,bookingextras=?,checkin=?,checkout=?,contactphone=?,roomID=? WHERE bookingID=?";
                $stmt = mysqli_prepare($DBC,$query); //prepare the query
                mysqli_stmt_bind_param($stmt,'ssssssi', $roomreview,$bookingextras,$checkin,$checkout,$contactphone,$roomID,$id); 
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);    
                echo "<h2>Booking details updated.</h2>";     
            } else { 
                echo "<h2>$msg</h2>".PHP_EOL;
            }      
        }        
        // turnoff PHP to use some HTML 
    ?>
      
        <h1>Edit A Booking.</h1>
        <h5><a href='currentbookings.php' class="link-secondary">Return to Bookings Listing</a></h5> 
        <h5><a href='index.php' class="link-secondary">Return to the main page</a></h5>                 <!-- Assume main page means the home page -->

        <?php 
          //get the customer name for the booking
          $query3 = 'SELECT bookingID, C.lastname, C.firstname
                      FROM bookings B
                      INNER JOIN customer C
                      ON B.customerID = C.customerID
                      WHERE bookingID='.$id;
                      
          $result3 = mysqli_query($DBC,$query3);
          while ($row = mysqli_fetch_assoc($result3)) {
            echo '<h3>Booking for '.$row['lastname'].', '.$row['firstname'].'</h3>';
                      }
        ?>
        <br>
        <form method="POST" action="editbooking.php">                     
          <input type="hidden" name="id" value="<?php echo $id; ?>"> 
            <div class="form-group row">
              <label for="roomID" class="col-sm-2 col-form-label">Room (name, type, beds):</label>
                <div class="col-sm-10">
                  <select id="roomID" name="roomID" required >   
                  <?php 
                    //get the room details for the booking
                    $query4 = 'SELECT B.roomID, R.roomname, R.roomtype, R.beds
                              FROM bookings B
                              INNER JOIN room R
                              ON B.roomID = R.roomID
                              WHERE bookingID='.$id;
                    $result4 = mysqli_query($DBC,$query4);
                    while ($row = mysqli_fetch_assoc($result4)) {
                      echo '<option selected value='.$row['roomID'].'>'.$row['roomname'].', '.$row['roomtype'].', '.$row['beds'].'</option>';
                    }
                  ?>                    
                  
                  <?php
                      //get the room details from the room table for the dropdown list.
                      $query2 = 'SELECT roomID, roomname, roomtype, beds FROM room';
                      $result2 = mysqli_query($DBC,$query2);
                      while ($row = mysqli_fetch_assoc($result2)) {
                      echo '<option value='.$row['roomID'].'>'.$row['roomname'].', '.$row['roomtype'].', '.$row['beds'].'</option>'; // displaying data in option menu'
                      }
                  ?>
                  </select></p>
                </div>
            </div> 

            <?php 
            //locate the member to edit by using the memberID
            //we also include the member ID in our form for sending it back for saving the data                
            $query = 'SELECT bookingID,roomreview,bookingextras,checkin,checkout,contactphone,roomID FROM bookings WHERE bookingID='.$id;
            $result = mysqli_query($DBC,$query);
            $rowcount = mysqli_num_rows($result);
            if ($rowcount > 0) {
            $row = mysqli_fetch_assoc($result);
            ?>
              <div class="form-group row">
                <label for="checkin" class="col-sm-2 col-form-label">Check In Date:</label>
              <div class="col-sm-10">
                <input type="text" id="checkin" name="checkin" required value="<?php echo $row['checkin'];?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="checkout" class="col-sm-2 col-form-label">Check Out Date:</label>
              <div class="col-sm-10">
                <input type="text" id="checkout" name="checkout" required value="<?php echo $row['checkout'];?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="contactphone" class="col-sm-2 col-form-label">Contact phone number:</label>
              <div class="col-sm-10">
                <input type="text" id="contactphone" name="contactphone" required pattern ="[(]{1}[0-9]{2,3}[)]{1}[\s]{1}[0-9]{3}[\-]{1}[0-9]{4,5}" 
                title ="Must be in the format of (12) 345-6789 or (123) 456-7890 or (123) 456-78901" value="<?php echo $row['contactphone'];?>">  
              </div>
            </div>
            <div class="form-group row">
              <label for="bookingextras" class="col-sm-2 col-form-label">Booking Extras:</label>
              <div class="col-sm-10">
                <textarea class="form-control" id="bookingextras" name="bookingextras" rows="3" maxlength="500"><?php echo $row['bookingextras']; ?></textarea>   <!--500 characters approx. 100 words, should be adequate for users to enter special requests-->
              </div>
            </div>
            <br/>
            <div class="form-group row">
              <label for="roomreview" class="col-sm-2 col-form-label">Room Review:</label>
              <div class="col-sm-10">
                <textarea class="form-control" id="roomreview" name="roomreview" rows="3" maxlength="750"><?php echo $row['roomreview']; ?></textarea>   <!--750 characters approx. 150 words, should be adequate for users to write a review-->
              </div>
            </div>
        
            <br/>
            <div class="form-group row">
              <div class="col-sm-10 offset-sm-2">
                <input type="submit" name="submit" value="Update">
                <a href='currentbookings.php'>[Cancel]</a>
              </div>
            </div>
        </form>  
        <?php    
        } else {
            echo "<h2>No booking found with that ID</h2>"; //suitable feedback
        }
 
        mysqli_free_result($result); //free any memory used by the query
        mysqli_free_result($result2); //free any memory used by the query
        mysqli_free_result($result3); //free any memory used by the query
        mysqli_free_result($result4); //free any memory used by the query
        mysqli_close($DBC); //close the connection once done
        ?>  
            

    </main><!-- /.container -->
    
    <footer class = "container-fluid fixed">
      <br>
      <p>&copy; Copyright 2021 Ongaonga Bed &amp; Breakfast. All rights reserved.</p>
      <p><a href="privacy.php">Privacy Statement</a></p>
    </footer>
  </body>
</html>
