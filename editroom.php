<!doctype html>
<?php
    include 'checksession.php';
    checkUser();
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit a Room</title>

    <!-- Bootstrap core CSS and Scripts-->
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
                            <a class="nav-link dropdown-toggle active" href="listrooms.php" id="navbardrop" data-toggle="dropdown">Rooms</a>
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
                            <a class="nav-link dropdown-toggle active" href="listrooms.php" id="navbardrop" data-toggle="dropdown">Rooms</a>
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
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </nav>


    <main role="main" class="container">

    <?php
        include "config.php"; //load in any variables
        $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

        if (mysqli_connect_errno()) {
            echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
            exit; //stop processing the page further
        };

        //function to clean input but not validate type and content
        function cleanInput($data) {  
        return htmlspecialchars(stripslashes(trim($data)));
        }

        //retrieve the roomid from the URL
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $id = $_GET['id'];
            if (empty($id) or !is_numeric($id)) {
                echo "<h2>Invalid room ID</h2>"; //simple error feedback
                exit;
            } 
        }
        //the data was sent using a formtherefore we use the $_POST instead of $_GET
        //check if we are saving data first by checking if the submit button exists in the array
        if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {     
            //validate incoming data - only the first field is done for you in this example - rest is up to you do
            $error = 0; //clear our error flag
            $msg = 'Error: ';  

            //roomID (sent via a form ti is a string not a number so we try a type conversion!)    
            if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
                $id = cleanInput($_POST['id']); 
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid room ID '; //append error message
                $id = 0;  
            }   
            
            //roomname
            if (isset($_POST['roomname']) and !empty($_POST['roomname']) and is_string($_POST['roomname'])) {
                $rn = cleanInput($_POST['roomname']); 
                $roomname = (strlen($rn)>50)?substr($rn,1,50):$rn; //check length and clip if too big
                //we would also do context checking here for contents, etc       
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid room name '; //append eror message
                $roomname= '';  
            }
            
            //description
            if (isset($_POST['description']) and !empty($_POST['description']) and is_string($_POST['description'])) {
                $des = cleanInput($_POST['description']); 
                $description = (strlen($des)>200)?substr($des,1,200):$des; //check length and clip if too big
                //we would also do context checking here for contents, etc       
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid description '; //append eror message
               $description= '';  
             }
            
            //roomtype
            if (isset($_POST['roomtype']) and !empty($_POST['roomtype'])) {
                $roomtype = cleanInput($_POST['roomtype']);    
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid room type '; //append error message
                $roomtype = '';
            } 
                 
            //beds
            if (isset($_POST['beds']) and !empty($_POST['beds']) and is_integer(intval($_POST['beds']))) {
                $beds = cleanInput($_POST['beds']);         
            } else {
                $error++; //bump the error flag
                $msg .= 'Invalid number of beds '; //append error message
                $beds = 0;
            } 
            
    
            //save the room data if the error flag is still clear and room id is > 0
            if ($error == 0 and $id > 0) {
                $query = "UPDATE room SET roomname=?,description=?,roomtype=?,beds=? WHERE roomID=?";
                $stmt = mysqli_prepare($DBC,$query); //prepare the query
                mysqli_stmt_bind_param($stmt,'sssdi', $roomname, $description, $roomtype, $beds, $id); 
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);    
                echo "<h2>Room details updated.</h2>";     
            //header('Location: http://localhost/bit608/listrooms.php', true, 303);      
            } else { 
                echo "<h2>$msg</h2>".PHP_EOL;
            }      
        }
        //locate the room to edit by using the roomID
        //we also include the room ID in our form for sending it back for saving the data
        $query = 'SELECT roomID,roomname,description,roomtype,beds FROM room WHERE roomid='.$id;
        $result = mysqli_query($DBC,$query);
        $rowcount = mysqli_num_rows($result);
        if ($rowcount > 0) {
        $row = mysqli_fetch_assoc($result);
        ?>

        <h1>Room Details Update</h1>
        <h5><a href='listrooms.php' class="link-secondary">Return to the Room Listing</a></h5>   
        <h5><a href='index.php' class="link-secondary">Return to the main page</a></h5>                  <!-- Assume this is the home page -->
        <br>
        <form method="POST" action="editroom.php">
            <input type="hidden" name="id" value="<?php echo $id;?>">
            <div class="form-group row">
                <label for="roomname" class="col-sm-2 col-form-label">Room name: </label>
                <div class="col-sm-10">
                <input type="text" id="roomname" name="roomname" minlength="5" maxlength="50" value="<?php echo $row['roomname']; ?>" required> 
                </div>
            </div> 
            <div class="form-group row">
                <label for="description" class="col-sm-2 col-form-label">Description: </label>
                <div class="col-sm-10">
                <input type="text" id="description" name="description" size="100" minlength="5" maxlength="200" value="<?php echo $row['description']; ?>" required> 
                </div>    
            </div>  
            <div class="form-group row"> 
                <label for="roomtype" class="col-sm-2 col-form-label">Room type: </label>
                <div class="col-sm-10">
                <input type="radio" id="roomtype" name="roomtype" value="S" <?php echo $row['roomtype']=='S'?'Checked':''; ?>> Single 
                <input type="radio" id="roomtype" name="roomtype" value="D" <?php echo $row['roomtype']=='D'?'Checked':''; ?>> Double 
                </div>
            </div>
            <div class="form-group row">
                <label for="beds" class="col-sm-2 col-form-label">Beds (1-5): </label>
                <div class="col-sm-10">
                <input type="number" id="beds" name="beds" min="1" max="5" value="1" value="<?php echo $row['beds']; ?>" required> 
                </div>
            </div> 
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                <input type="submit" name="submit" value="Update">
                </div>
            </div>
        </form>

        <?php 
        } else { 
            echo "<h2>room not found with that ID</h2>"; //simple error feedback
        }
        mysqli_close($DBC); //close the connection once done
        ?>

        <br>
    </main>

    <footer class = "container-fluid">
        <br>
        <p>&copy; Copyright 2021 Ongaonga Bed &amp; Breakfast. All rights reserved.</p>
        <p><a href="privacy.php">Privacy Statement</a></p>
    </footer> 
    
</body>
</html>
