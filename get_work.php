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
if(strlen($_SESSION['id']) != 26)
{
    exit();
}

//$r_worker_id = $_POST['worker_id'];
$r_worker_id = $_SESSION['id'];
$r_hash_id = $_POST['hash_id'];

if(!validate_hashID($r_hash_id))
{
    print ":P";
    exit();
}

$sql = $con->prepare("UPDATE slots_todo SET worker_id = ? WHERE worker_id='' AND hash_id = ? ORDER BY id LIMIT 1");
$sql->bind_param("si",$r_worker_id,$r_hash_id);
$sql->execute();
//$stmt->affected_rows
$sql = $con->prepare("SELECT slots FROM slots_todo WHERE worker_id = ? AND hash_id=?");
$sql->bind_param("si",$r_worker_id,$r_hash_id);
$sql->execute();
$sql->bind_result($slots);
$sql->fetch();
$sql->close();
if($slots == null)
{
    $slots="[]";
}
$_SESSION['slots'] = $slots;
$result = '{"slots":' . $slots . ',"hash":"';
$sql = $con->prepare("SELECT hash,salt,keyspace,algo,salt_location,clear FROM submit_hashes WHERE hash_id = ?");
$sql->bind_param("i",$r_hash_id);
$sql->execute();
$sql->bind_result($hash,$salt,$keyspace,$algo,$salt_location,$clear);
$sql->fetch();
$result = $result . $hash . '","salt":"' . $salt . '","keyspace":"' . $keyspace . '","algo":' . $algo . ',"salt_location":' . $salt_location;
$sql->close();
$sql = $con->prepare("DELETE FROM slots_todo WHERE worker_id =? AND hash_id=?");
$sql->bind_param("si",$r_worker_id,$r_hash_id);
$sql->execute();
$sql->close();
$con->close();

header('Content-type: application/json');
if(strlen($clear) > 0)
{
    $result = $result . ',"cracked":1';
}
else
{
    $result = $result . ',"cracked":0';
}
$result = $result . '}';

print $result;

/**

POST /ravan/get_work.php HTTP/1.1
Host: 192.168.237.136
Content-Type: application/x-www-form-urlencoded
Content-Length: 24

hash_id=49&worker_id=100


RESPONSE:
{"slots":[6,7,8,9,10],"hash":"abcdefgh"}

**/
?>
