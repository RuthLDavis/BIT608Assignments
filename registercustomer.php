<!doctype html>
<?php
    include 'checksession.php';
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register Customer</title>

    <!-- Bootstrap core CSS and Scripts -->
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
                    <a class="nav-link dropdown-toggle active" href="registercustomer.php" id="navbardrop" data-toggle="dropdown">Register</a>
                            <div class="dropdown-menu">
                            <a class="dropdown-item active" href="registercustomer.php">Register A New Customer</a>
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
                            <a class="nav-link dropdown-toggle active" href="registercustomer.php" id="navbardrop" data-toggle="dropdown">Register</a>
                            <div class="dropdown-menu">
                            <a class="dropdown-item active" href="registercustomer.php">Register A New Customer</a>
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
        //function to clean input but not validate type and content
        function cleanInput($data) {  
        return htmlspecialchars(stripslashes(trim($data)));
        }

        //the data was sent using a formtherefore we use the $_POST instead of $_GET
        //check if we are saving data first by checking if the submit button exists in the array
        if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Register')) {
            //if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test    
            include "config.php"; //load in any variables
            $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

            if (mysqli_connect_errno()) {
                echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
                exit; //stop processing the page further
            };

            //validate incoming data 
            //firstname
            $error = 0; //clear our error flag
            $msg = 'Error: ';
            if (isset($_POST['firstname']) and !empty($_POST['firstname']) and is_string($_POST['firstname'])) {
                $fn = cleanInput($_POST['firstname']); 
                $firstname = (strlen($fn)>50)?substr($fn,1,50):$fn; //check length and clip if too big
                //we would also do context checking here for contents, etc       
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid first name '; //append eror message
                $firstname = '';  
            } 

            //lastname               
            if (isset($_POST['lastname']) and !empty($_POST['lastname']) and is_string($_POST['lastname'])) {
                $ln = cleanInput($_POST['lastname']); 
                $lastname = (strlen($ln)>50)?substr($ln,1,50):$ln; //check length and clip if too big
                //we would also do context checking here for contents, etc       
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid last name '; //append eror message
                $lastname = '';  
            } 
       
            //email                                                                                 
            if (isset($_POST['email']) and !empty($_POST['email']) and is_string($_POST['email'])) {
                $ea = cleanInput($_POST['email']); 
                $email = (strlen($ea)>50)?substr($ea,1,50):$ea; //check length and clip if too big
                //we would also do context checking here for contents, etc       
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid email '; //append eror message
                $email = '';  
            }                 
    
            //password                                                                             
            if (isset($_POST['password']) and !empty($_POST['password']) and is_string($_POST['password'])) {
                $pw = cleanInput($_POST['password']); 
                $password = (strlen($pw)>32)?substr($pw,1,32):$pw; //check length and clip if too big
                //we would also do context checking here for contents, etc       
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid password '; //append error message
                $password = '';  
            }

            // username
            if (isset($_POST['username']) and !empty($_POST['username']) and is_string($_POST['username'])) {
                $un = cleanInput($_POST['username']); 
                $username = (strlen($un)>8)?substr($un,1,8):$un; //check length and clip if too big
                //we would also do context checking here for contents, etc       
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid username '; //append eror message
                $username = '';  
            }
                                                                                            
            //save the customer data if the error flag is still clear
            if ($error == 0) {
                $query = "INSERT INTO customer (firstname,lastname,email,username,password) VALUES (?,?,?,?,?)";
                $stmt = mysqli_prepare($DBC,$query); //prepare the query
                mysqli_stmt_bind_param($stmt,'sssss', $firstname, $lastname, $email, $username, $password); 
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);    
                echo "<h2>customer saved</h2>";        
            } else { 
                echo "<h2>$msg</h2>".PHP_EOL;
            }      
            mysqli_close($DBC); //close the connection once done
        }
    ?>

        <h1>New Customer Registration</h1>
        <h5><a href='listcustomers.php' class="link-secondary">Return to the Customer listing</a></h5>   
        <h5><a href='index.php' class="link-secondary">Return to the main page</a></h5>                  <!-- Assume this is the home page -->
        <br>
        <form method="POST" action="registercustomer.php">
            <div class="form-group row">
                <label for="firstname" class="col-sm-2 col-form-label">First Name: </label>
                <div class="col-sm-10">
                    <input type="text" id="firstname" name="firstname" minlength="5" maxlength="50" required> 
                </div>
            </div>
             
            <div class="form-group row">
                <label for="lastname" class="col-sm-2 col-form-label">Last Name: </label>
                <div class="col-sm-10">
                    <input type="text" id="lastname" name="lastname" minlength="5" maxlength="50" required> 
                </div>  
            </div>  
            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Email: </label>
                <div class="col-sm-10">
                    <input type="email" id="email" name="email" maxlength="100" size="50" required> 
                </div>              
            </div>
            <div class="form-group row">
                <label for="username" class="col-sm-2 col-form-label">User Name: </label>
                <div class="col-sm-10">
                    <input type="username" id="username" name="username" minlength="4" maxlength="8" required> 
                </div>              
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">Password: </label>
                <div class="col-sm-10">
                    <input type="password" id="password" name="password" minlength="8" maxlength="32" required> 
                </div>
            </div> 
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <input type="submit" name="submit" value="Register">
                </div>              
            </div>
        </form>
        <br>
    
    </main>

    <footer class = "container-fluid">
        <br>
        <p>&copy; Copyright 2021 Ongaonga Bed &amp; Breakfast. All rights reserved.</p>
        <p><a href="privacy.php">Privacy Statement</a></p>
    </footer>
    
</body>
</html>
