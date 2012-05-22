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
$r_hash_id = $_POST['hash_id'];
$r_worker_id = $_SESSION['id'];
$r_slot = $_POST['slot'];
$r_cracked = $_POST['cracked'];
$r_clear = $_POST['clear'];

if(strlen($_SESSION['id']) != 26)
{
    exit();
}

if(!(validate_hashID($r_hash_id) && validate_slot($r_slot) && validate_result($r_clear) && validate_cracked($r_cracked)))
{
    print ":P";
    exit();
}

$_alloted_slots = trim($_SESSION['slots']);
$_alloted_slots = ltrim($_alloted_slots,"[");
$_alloted_slots = rtrim($_alloted_slots,"]");
$_alloted_slots_array = explode(",",$_alloted_slots);
if(!in_array($r_slot,$_alloted_slots_array))
{
    //print ":P" . $_alloted_slots_array . " - " . $r_slot;
    print_r ($_alloted_slots_array);
    exit();
}


$sql = $con->prepare("INSERT INTO slots_done (hash_id,worker_id,slot,cracked,clear) VALUES (?,?,?,?,?)");
$sql->bind_param("isiis",$r_hash_id,$r_worker_id,$r_slot,$r_cracked,$r_clear);
$sql->execute();
$sql->close();
$con->close();
/**

POST /ravan/submit_result.php HTTP/1.1
Host: 192.168.237.136
Content-Type: application/x-www-form-urlencoded
Content-Length: 52

hash_id=49&worker_id=100&slot=3&cracked=1&clear=abcd


Response: NIL
**/
?>
