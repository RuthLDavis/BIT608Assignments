<?php
  // this line is to include the QuantumPHP file for sending logs to the F12 developer tools in the browser
  include 'quantumphp.php';

  // set the operation mode to 1 for Chrome and Firefox (2 is just for FireFox and 3 is just for Chrome)
  QuantumPHP::$MODE = 1;
  
  include "checksession.php";
  checkUser();
  $user=passUser();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Make a booking</title>

    <!-- Bootstrap core CSS and Scripts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Custom styles for this page -->
    <link href='./styles/ongaongabandb.css' rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
     

    <!-- Custom scripts for this page -->
    <script src=" https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>  
    <script src='./js/datepicker.js'></script>
  </head>

  <body>

  <header class ="container-fluid">
        <h1>Ongaonga Bed &amp; Breakfast</h1>
        <p>Make yourself at home is our slogan. We offer some of the best beds on the East Coast. Sleep well and rest well...</p>
        <br/>
        <hr class="solid">
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
                            <a class="dropdown-item active" href="makebooking.php">Make A New Booking</a>
                            <a class="dropdown-item" href="currentbookings.php">Current Bookings List</a>
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
                            <a class="dropdown-item active" href="makebooking.php">Make A New Booking</a>
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

        // Customer ID
        $id = $user;

        // function to clean input but not to validate type and content
        function cleanInput($data) {  
            return htmlspecialchars(stripslashes(trim($data)));
        }

        //the data was sent using a form therefore we use the $_POST instead of $_GET
        //check if we are saving data first by checking if the submit button exists in the array
        if (isset($_POST['submit']) and !empty($_POST['submit']) 
        and ($_POST['submit'] == 'Add Booking')) {     
            //validate incoming data - 
            $error = 0; //clear our error flag
            $msg = 'Error: ';

            // Customer ID
            // add the variable to the QuantumPHP buffer
            QuantumPHP::add($customerID = $id); 

            // $customerID = $id;                                    // this line left in code for it the QuantumPHP needs to be removed
        
            // room ID
            if (isset($_POST['roomID']) and !empty($_POST['roomID'])) {
              // add the variable to the QuantumPHP buffer
              QuantumPHP::add($roomID = cleanInput($_POST['roomID'])); 

              //  $roomID = cleanInput($_POST['roomID']);             // this line left in code for it the QuantumPHP needs to be removed
            } else {
              $error++; //bump the error flag
              $msg .= 'Invalid Room ID '; //append error message
              $roomID = 0;
            } 

            // Check In Date
            if (isset($_POST['checkin']) and !empty($_POST['checkin'])) {      
              // add the variable to the QuantumPHP buffer
              QuantumPHP::add($checkin = cleanInput($_POST['checkin']));  
              
              // $checkin = cleanInput($_POST['checkin']);           // this line left in code for it the QuantumPHP needs to be removed
            } else {
              $error++; //bump the error flag
              $msg .= 'Invalid Check In Date '; //append error message
            } 

            // Check Out Date
            if (isset($_POST['checkout']) and !empty($_POST['checkout'])) { 
              // add the variable to the QuantumPHP buffer
              QuantumPHP::add($checkout = cleanInput($_POST['checkout']));  

              // $checkout = cleanInput($_POST['checkout']);         // this line left in code for it the QuantumPHP needs to be removed
            } else {
              $error++; //bump the error flag
            $msg .= 'Invalid Check Out Date '; //append error message
            } 
        
            // Contact phone number
            if (isset($_POST['contactphone']) and !empty($_POST['contactphone'])) {
              // add the variable to the QuantumPHP buffer
              QuantumPHP::add($contactphone = cleanInput($_POST['contactphone']));  
              
              //$contactphone = cleanInput($_POST['contactphone']);   // this line left in code for it the QuantumPHP needs to be removed
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
                    // add the variable to the QuantumPHP buffer
                    QuantumPHP::add($bookingextras = $fn);
                    // $bookingextras = $fn;          // this line left in code for it the QuantumPHP needs to be removed
                }
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid Booking Extras '; //append error message
                $bookingextras = '';
            } 

            // send the QuantumPHP to the broswer
            QuantumPHP::send();

            //save the member data if the error flag is still clear
            if ($error == 0) {
                $query = "INSERT INTO bookings (roomID,customerID,checkin,checkout,contactphone,bookingextras) VALUES (?,?,?,?,?,?)";
                $stmt = mysqli_prepare($DBC,$query); //prepare the query
                mysqli_stmt_bind_param($stmt,'ssssss', $roomID, $customerID, $checkin, $checkout, $contactphone, $bookingextras); 
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);    
                echo "<h2>New Booking Saved</h2><br>";        
            } else { 
                echo "<h2>$msg</h2>".PHP_EOL;
            }   
        }

         //get the customer name for the booking
         $query = 'SELECT lastname, firstname FROM customer WHERE customerID='.$id;
         $result = mysqli_query($DBC,$query);
        // turnoff PHP to use some HTML 
    ?>


        <h1>Make A Booking</h1>
        <h5><a href='currentbookings.php' class="link-secondary">Return to Bookings Listing</a></h5>     
        <h5><a href='index.php' class="link-secondary">Return to the main page</a></h5>                 <!-- assume this is the home page-->

        <?php 
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<h3>Booking for '.$row['lastname'].', '.$row['firstname'].'</h3>';
            }  
        ?>
        <br>
        <form method="POST" action="makebooking.php">
                  <div class="form-group row">
                    <label for="roomID" class="col-sm-2 col-form-label">Room (number, name, type, beds):</label>
                    <div class="col-sm-10">
                    <?php 
                            //prepare a query to get date from the room table for the drop down list           
                            $query2 = 'SELECT roomID, roomname, roomtype, beds FROM room';
                            $result2 = mysqli_query($DBC,$query2);
                            $rowcount = mysqli_num_rows($result2); 
                            echo '<select id="roomID" name="roomID" required>';
                            echo 'option selected disabled value="">Select...</option>';
                        
                            while ($row = mysqli_fetch_assoc($result2)) {
                                    echo '<option value='.$row['roomID'].'>'.$row['roomname'].', '.$row['roomtype'].', '.$row['beds'].'</option>'; // displaying data in option menu'
                                }
                        ?>
                        </select></p>
                    </div>
                    </div>         
            <div class="form-group row">
              <label for="checkin" class="col-sm-2 col-form-label">Check In Date:</label>
              <div class="col-sm-10">
                <input type="text" id="checkin" name="checkin" required placeholder="yyyy-mm-dd"> 
              </div>
            </div>
            <div class="form-group row">
              <label for="checkout" class="col-sm-2 col-form-label">Check Out Date:</label>
              <div class="col-sm-10">
                <input type="text" id="checkout" name="checkout" required placeholder="yyyy-mm-dd"> 
              </div>
            </div>
            <div class="form-group row">
              <label for="contactphone" class="col-sm-2 col-form-label">Contact phone number:</label>
              <div class="col-sm-10">
                <input type="text" id="contactphone" name="contactphone" required placeholder="(###) ###-####"
                pattern ="[(]{1}[0-9]{2,3}[)]{1}[\s]{1}[0-9]{3}[\-]{1}[0-9]{4,5}" title ="Must be in the format of (12) 345-6789 or (123) 456-7890 or (123) 456-78901">  
              </div>
            </div>
            <div class="form-group row">
              <label for="bookingextras" class="col-sm-2 col-form-label">Booking Extras:</label>
              <div class="col-sm-10">
                <textarea class="form-control" id="bookingextras" name="bookingextras" rows="3" maxlength="500"></textarea>   <!-- 500 characters approx. 100 words, should be adequate for users to enter special requests-->
              </div>
            </div>
            <br/>
            <div class="form-group row">
              <div class="col-sm-10 offset-sm-2">
                <input type="submit" name="submit" value="Add Booking">
                <a href='currentbookings.php'>[Cancel]</a>                         
              </div>
            </div>
        </form>   
        <br/>    
        <hr class="dotted"> 
        <br/>    

        <h3>Search for room availability</h3>
        <br/>

        <form method="POST">
          <div class="form-group row">
            <label for="from" class="col-sm-2 col-form-label">Start Date:</label>
            <div class="col-sm-10">
              <input type="text" id="from" name="from" required placeholder="yyyy-mm-dd">  
            </div>
          </div>
          <div class="form-group row">
            <label for="to" class="col-sm-2 col-form-label">End Date:</label>
            <div class="col-sm-10">
              <input type="text" id="to" name="to" required placeholder="yyyy-mm-dd"> 
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-10 offset-sm-2">
              <input type="button" name="search" id="search" value="Search Availability">
            </div>
          </div>
        </form>
        <br/>
        <div id="room_table">
        <table class="table table-bordered"> 
            <thead><tr><th>Room Number</th><th >Room Name</th><th>Room Type</th><th>Beds</th></tr></thead>
            <?php
                $query1 = 'SELECT roomID, roomname, roomtype, beds FROM room';
                $result1 = mysqli_query($DBC,$query1);
                $rowcount = mysqli_num_rows($result1); 
                //makes sure we have the member
                if ($rowcount > 0) {  
                    while ($row = mysqli_fetch_array($result1)) {
	                    $id = $row['roomID'];	
	                    echo '<tr><td>'.$row['roomID'].'</td><td>'.$row['roomname'].'</td><td>'.$row['roomtype'].'</td><td>'.$row['beds'].'</td>';
	                    echo '</tr>'.PHP_EOL;
                    }
                } else echo "<h2>No rooms found!</h2>"; //suitable feedback

            ?>
        </table>
        </div>
        <br>

        <script>
            /* create variables for the two dates and get the dates from the text fields */
            $('#search').click(function(){
              var start_date = $('#from').val();
              var end_date = $('#to').val();
              if(start_date != '' && end_date != '')
              {
                  $.ajax({
                    url:"roomsearch.php",      /* this php file and database does yet exist so will not return any data until the backend development is done*/
                    method: "POST",
                    //dataType: "jsonp",              //required for the same-origin policy 
                    data: {start_date:start_date, end_date:end_date},    /* variables to send to the server*/
                    success: function(data)
                    {
                      $('#room_table').html(data)
                    }
                  })
              }
              else
              {
                alert("Please select start and end dates");
              }
            });

            </script>

            
    <?php
                    mysqli_free_result($result); //free any memory used by the query
                    mysqli_free_result($result1); //free any memory used by the query
                    mysqli_free_result($result2); //free any memory used by the query
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