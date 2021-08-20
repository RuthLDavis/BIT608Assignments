<!doctype html>
<?php
    include 'checksession.php';
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <title>Ongaonga Bed and Breakfast - Login</title>

    <!-- Bootstrap core CSS and scripts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Custom styles for this page -->
    <link href='./styles/ongaongabandb.css' rel="stylesheet">

  </head>

  <body>
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
                            <a class="nav-link dropdown-toggle" href="makebooking.php" id="navbardrop" data-toggle="dropdown">Bookings</a>
                            <div class="dropdown-menu">
                            <a class="dropdown-item" href="makebooking.php">Make A New Booking</a>
                            <a class="dropdown-item" href="currentbookings.php">Bookings List</a>
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
                            <a class="nav-link dropdown-toggle" href="makebooking.php" id="navbardrop" data-toggle="dropdown">Bookings</a>
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
                    <a class="nav-link active" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <main role="main" class="container">
    <h1>Welcome to the Ongaonga Bed &amp; Breakfast.</h1>

    <h2>Login/Logout</h2>
    <br>
    <?php
        loginStatus();

        // simple logout
        if (isset($_POST['logout'])) logout();
        
        
        if (isset($_POST['login']) and !empty($_POST['login']) and ($_POST['login'] == 'Login')) { 

        include "config.php"; //load in any variables
        $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die();

        //validate incoming data 
        $error = 0; //clear our error flag
        $msg = 'Error: ';
        if (isset($_POST['username']) and !empty($_POST['username']) and is_string($_POST['username'])) {
            $un = htmlspecialchars(stripslashes(trim($_POST['username'])));  
            $username = (strlen($un)>32)?substr($un,1,32):$un; //check length and clip if too big       
        } else {
            $error++; //bump the error flag
            $msg .= 'Invalid username '; //append error message
            $username = '';  
        } 

        //password  - normally we avoid altering a password apart from whitespace on the ends   
        $password = trim($_POST['password']);     

        //write a query and send it to the server         
        if ($error == 0) {
            $query = "SELECT customerID,password FROM customer WHERE username = '$username'";
            $result = mysqli_query($DBC,$query);     
            if (mysqli_num_rows($result) == 1) { //found the user
                $row = mysqli_fetch_assoc($result);
                mysqli_free_result($result);
                mysqli_close($DBC); //close the connection once done
                if ($password === $row['password']) //using plaintext for demonstration only!            
                login($row['customerID'],$username);
          } echo "<h2>Login fail</h2>".PHP_EOL;   
      } else { 
        echo "<h2>$msg</h2>".PHP_EOL;
      }      
  }
        // turnoff PHP to use some HTML 
    ?>
        <br>
        <form method="POST" action="login.php">
            <div class="form-group row">
                <label for="username" class="col-sm-2 col-form-label">Username: </label>
                <div class="col-sm-10">
                <input type="text" id="username" name="username" maxlength="32"> 
                </div>
            </div> 
            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">Password: </label>
                <div class="col-sm-10">
                <input type="password" id="password" name="password" maxlength="32"> 
                </div>
            </div> 
  
            <input type="submit" name="login" value="Login">
            <input type="submit" name="logout" value="Logout">   
        </form>
        <br> <br>
        <h4>Click <a href='registercustomer.php'>here</a> for new customer registrations</h4>
    
    </main>

    <footer class = "container-fluid">
        <br>
        <p>&copy; Copyright 2021 Ongaonga Bed &amp; Breakfast. All rights reserved.</p>
        <p><a href="privacy.php">Privacy Statement</a></p>
    </footer>
    
</body>
</html>
