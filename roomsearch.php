<?php
// this line is to include the QuantumPHP file for sending logs to the F12 developer tools in the browser
include 'quantumphp.php';
ob_start();

// set the operation mode to 1 for Chrome and Firefox (2 is just for FireFox and 3 is just for Chrome)
QuantumPHP::$MODE = 1;


if(isset($_POST['start_date']) and !empty($_POST['start_date']) 
and (isset($_POST['end_date']) and !empty($_POST['end_date'])))
{
    // add the variables to the QuantumPHP buffer
    QuantumPHP::add($from = $_POST['start_date']);
    QuantumPHP::add($to = $_POST['end_date']);
    // $from = $_POST['start_date'];            // left this line here in case the QuantumPHP needs to be removed in the future
    // $to = $_POST['end_date'];                // left this line here in case the QuantumPHP needs to be removed in the future

    include "config.php"; //load in any variables
    $DBC = mysqli_connect(DBHOST, DBUSER , DBPASSWORD, DBDATABASE);
    $output = '';
    // query supplied by Open Polytechnic in the assignment to be used in this file.
    $query = "SELECT * FROM room WHERE roomID NOT IN                                               
                (SELECT roomID FROM bookings WHERE checkin >= '$from' AND checkout <= '$to')
                ";  
    $result = mysqli_query($DBC, $query);
    
    
    $output .= '<table class="table table-bordered">
        <thead><tr><th>Room Number</th><th >Room Name</th><th>Room Type</th><th>Beds</th></tr></thead>';
    if (mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            $output .= ' 
                <tr>
                <td>'.$row['roomID'].'</td>
                <td>'.$row['roomname'].'</td>
                <td>'.$row['roomtype'].'</td>
                <td>'.$row['beds'].'</td>
                </tr>';

            QuantumPHP::add($row['roomID']);
            QuantumPHP::add($row['roomname']);
            QuantumPHP::add($row['roomtype']);
            QuantumPHP::add($row['beds']);
        }
    }
    else
    {
        $output .= '
            <tr>
            <td colspan="4">No Rooms Found</td>
            </tr>
        ';
    }; 
    $output .= '</table>';

    // send the QuantumPHP to the broswer
    QuantumPHP::send();

    echo $output;

    mysqli_free_result($result); //free any memory used by the query
    mysqli_close($DBC); //close the connection once done
}

?>