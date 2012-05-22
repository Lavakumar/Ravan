<?php
/*
The MIT License (MIT)
Copyright (c) 2010- Lavakumar Kuppan

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

$con = new mysqli(*************);
if(mysqli_connect_errno())//say the cracker is currently not functional
{
    echo "Could not connect to DB";
    exit();
}
include 'validate.php';
session_start();
$_SESSION['id'] = session_id();
$r_password = $_SESSION['id'];
if(!validate_password($r_password))
{
    session_regenerate_id();
    $_SESSION['id'] = session_id();
    $r_password = $_SESSION['id'];
}

$_SESSION['id'] = session_id();
$r_hash = $_POST['hash'];
$r_salt = $_POST['salt'];
$r_password = $_SESSION['id'];
$r_keyspace = $_POST['keyspace'];
$r_algo = $_POST['algo'];
$r_salt_location = $_POST['salt_location'];

if(!validate_hash($r_hash))
{
    $result='{"error":"Invalid hash value"}';
    print $result;
    exit();
}
else if(!validate_salt($r_salt))
{
    $result='{"error":"Invalid salt value. Max salt length is 100 characters."}';
    print $result;
    exit();
}
else if(!validate_keyspace($r_keyspace))
{
    $result='{"error":"Invalid keyspace value. Max length is 100 characters."}';
    print $result;
    exit();
}
else if(!validate_algo($r_algo))
{
    $result='{"error":"Invalid algorithm value"}';
    print $result;
    exit();
}
else if(!validate_saltlocation($r_salt_location))
{
    $result='{"error":"Invalid salt location value"}';
    print $result;
    exit();
}

$sql = $con->prepare("INSERT INTO submit_hashes (hash,salt,password,keyspace,algo,salt_location) VALUES (?,?,?,?,?,?)");
$sql->bind_param("ssssii",$r_hash,$r_salt,$r_password,$r_keyspace,$r_algo,$r_salt_location);
$sql->execute();
$hash_id = $con->insert_id;
$_SESSION['hash_id'] = $hash_id;
$result = '{"error":"","hash":"' . $r_hash . '","password":"' . $r_password . '","hash_id":' . $hash_id . '}';

header('Content-type: application/json');
print $result;
$sql->close();
$con->close();

/**

POST /ravan/submit_hash.php HTTP/1.1
Host: 192.168.237.136
Content-Type: application/x-www-form-urlencoded
Content-Length: 26

hash=abcdefgh&password=pwd

RESPONSE: 
{"hash":"abcdefgh","password":"pwd","hash_id":49}

**/

?>
