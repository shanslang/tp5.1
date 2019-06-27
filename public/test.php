<?php

$serverName = "120.78.149.107";  
$connectionInfo = array( "Database"=>"THAccountsDB","UID" => 'gm',"PWD" => 'Cn0bgJ4uulSYLAhQgIBy');  
$conn = sqlsrv_connect( $serverName, $connectionInfo);  
  
if( $conn )  
{  
     echo "Connection established.\n";  
}  
else  
{  
     echo "Connection could not be established.\n";  
     die( print_r( sqlsrv_errors(), true));  
}  
  

sqlsrv_close( $conn);  

// echo phpinfo();