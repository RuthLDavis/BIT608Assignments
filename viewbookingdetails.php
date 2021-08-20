<?php
    include "checksession.php";
    checkUser();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Booking Details View</title>

    <!-- Bootstrap core CSS and Scripts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     
    <!-- Custom styles for this page -->
    <link href='./styles/ongaongabandb.css' rel="stylesheet">

  </head>
  <body>
    <?php
        include "config.php"; //load in any variables
        $DBC = mysqli_connect(DBHOST, DBUSER , DBPASSWORD, DBDATABASE);
 
        //check if the connection was good
        if (!$DBC) {
            echo "Error: Unable to connect to MySQL.\n". mysqli_connect_errno()."=".mysqli_connect_error() ;
            exit; //stop processing the page further
        }

        //do some simple validation to check if id exists
        $id = $_GET['id'];
        if (empty($id) or !is_numeric($id)) {
            echo "<h2>Invalid Booking ID</h2>"; //simple error feedback
            exit;
        }
        //do some simple validation to check if id exists
 
      
        //send query to the server to get the booking details for the display
        $query = 'SELECT bookingID, checkin, checkout, contactphone, bookingextras, roomreview, R.roomname, C.lastname, C.firstname
                    FROM bookings B
                    INNER JOIN customer C
                    ON B.customerID = C.customerID
                    INNER JOIN room R
                    ON B.roomID = R.roomID
                    WHERE bookingID='.$id;
        $result = mysqli_query($DBC,$query);
        $rowcount = mysqli_num_rows($result); 
        // turnoff PHP to use some HTML 
    ?>


    <header class ="container-fluid">
        <h1>Ongaonga Bed &amp; Breakfast</h1>
        <p>Make yourself at home is our slogan. We offer some of the best beds on the East Coast. Sleep well and rest well.</p>
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
                            <a class="dropdown-item" href="makebooking.php">Make A New Booking</a>
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


        <h1>Booking Details View</h1>
        <h5><a href='currentbookings.php' class="link-secondary">Return to the booking list</a></h5> 
        <h5><a href='index.php' class="link-secondary">Return to the main page</a></h5>                  <!-- assume that this is home page -->
        <br>
        <?php
         //makes sure we have the booking
        if ($rowcount > 0) {  
            echo "<fieldset><legend>Booking detail #$id</legend><dl>"; 
            $row = mysqli_fetch_assoc($result);
            echo "<dt>Room Name:</dt><dd>".$row['roomname']."</dd>".PHP_EOL;        
            echo "<dt>Check in:</dt><dd>".$row['checkin']."</dd>".PHP_EOL;
            echo "<dt>Check out:</dt><dd>".$row['checkout']."</dd>".PHP_EOL;
            echo "<dt>Guest Name:</dt><dd>".$row['lastname'].", ".$row['firstname']."</dd>".PHP_EOL;
            echo "<dt>Contact Number:</dt><dd>".$row['contactphone']."</dd>".PHP_EOL;
            echo "<dt>Extras:</dt><dd>".$row['bookingextras']."</dd>".PHP_EOL;
            echo "<dt>Room Review:</dt><dd>".$row['roomreview']."</dd>".PHP_EOL;
            echo '</dl></fieldset>'.PHP_EOL;     
        } else echo "<h2>No booking found, possibly deleted</h2>"; //suitable feedback
 
        mysqli_free_result($result); //free any memory used by the query
        mysqli_close($DBC); //close the connection once done
        ?>

        <br/>
    </main><!-- /.container -->
    
    <footer class = "container-fluid fixed">
      <br>
      <p>&copy; Copyright 2021 Ongaonga Bed &amp; Breakfast. All rights reserved.</p>
      <p><a href="privacy.php">Privacy Statement</a></p>
    </footer>

</body>
</html>
