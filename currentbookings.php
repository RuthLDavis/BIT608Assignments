<!doctype html>
<?php
    include 'checksession.php';
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Current Bookings</title>

    <!-- Bootstrap core CSS and Scripts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Custom styles for this page -->
    <link href='./styles/ongaongabandb.css' rel="stylesheet">

    <!-- Custom scripts for this page -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      
    <!-- script for the search funtion -->
    <script src='./js/currentbookings.js'></script>

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

        //write a query and send it to the server          
        $query = 'SELECT bookingID, checkin, checkout, R.roomname, C.firstname, 
                    C.lastname
                    FROM bookings B
                    INNER JOIN room R
                      ON B.roomID = R.roomID
                    INNER JOIN customer C
                      ON B.customerID = C.customerID
                     ORDER BY B.bookingID';   
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


        <h1>Current Bookings</h1>
        <h5><a href='makebooking.php' class="link-secondary">Make a booking</a></h5>   
        <h5><a href='index.php' class="link-secondary">Return to the main page</a></h5>                  <!-- Assume this is the home page -->


        <label for="myInput">Search by room name, dates, or customer: </label>
        <input class="form-control" id="myInput" type="text" placeholder="Search...">
        <br/>

        <table class="table table-bordered table-striped">
            <thead><tr><th>Booking ID</th><th>Booking (room, dates)</th><th>Customer</th><th>actions</th></tr></thead>
            <tbody id="myTable">
                <?php
                //makes sure we have bookings
                    if ($rowcount > 0) {  
                        while ($row = mysqli_fetch_assoc($result)) {
	                        $id = $row['bookingID'];	
	                        echo '<tr><td>'.$row['bookingID'].'</td><td>'.$row['roomname'].','.$row['checkin'].','.$row['checkout'].'</td><td>'.$row['lastname'].','.$row['firstname'].'</td>';
                          echo     '<td><a href="viewbookingdetails.php?id='.$id.'">[view]</a>';
	                        echo     '<a href="editbooking.php?id='.$id.'">[edit]</a>';  
                          echo     '<a href="roomreview.php?id='.$id.'">[manage reviews]</a>'; 
	                        echo     '<a href="deletebooking.php?id='.$id.'">[delete]</a></td>';
                           echo '</tr>'.PHP_EOL;
                        }
                    } else echo "<h2>No bookings found!</h2>"; //suitable feedback

                    mysqli_free_result($result); 
                    mysqli_close($DBC);
                ?>
            </tbody>
        </table>
    
    </main>

    <footer class = "container-fluid">
      <br>
      <p>&copy; Copyright 2021 Ongaonga Bed &amp; Breakfast. All rights reserved.</p>
      <p><a href="privacy.php">Privacy Statement</a></p>
    </footer>

</body>
</html>
