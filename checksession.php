<?php
session_start();

//function to check if the user is logged else send to the login page 
function checkUser($acl = 0) {
     $_SESSION['URI'] = '';    
     if ($_SESSION['loggedin'] == 1)
        return TRUE;
     else {
        $_SESSION['URI'] = 'http://ongaongabandb.epizy.com'.$_SERVER['REQUEST_URI']; //save current url for redirect     
        header('Location: http://ongaongabandb.epizy.com/login.php', true, 303);       
     }       
}

//just to show we are logged in
function loginStatus() {
  
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {    
    $un = $_SESSION['username'];    
    echo "<h3>Currently logged in as $un</h3>";
  }
  else {
    echo "<h3>Currently logged out</h3>";            
  }
}
  

 //log a user in
function login($id,$username) {
  //simple redirect if a user tries to access a page they have not logged in to
  if ($_SESSION['loggedin'] == 0 and !empty($_SESSION['URI']))        
       $uri = $_SESSION['URI'];          
  else { 
    $_SESSION['URI'] =  'http://ongaongabandb.epizy.com/login.php';         
    $uri = $_SESSION['URI'];           
  }  

  $_SESSION['loggedin'] = 1;        
  $_SESSION['userid'] = $id;   
  $_SESSION['username'] = $username; 
  $_SESSION['URI'] = ''; 
  header('Location: '.$uri, true, 303);     
}

//simple logout function
function logout(){
 $_SESSION['loggedin'] = 0;
 $_SESSION['userid'] = -1;        
 $_SESSION['username'] = '';
 $_SESSION['URI'] = '';
 header('Location: http://ongaongabandb.epizy.com/login.php', true, 303);    
}

//pass user function to pass the customerID
function passUser(){
    return $_SESSION['userid'];
}


?>