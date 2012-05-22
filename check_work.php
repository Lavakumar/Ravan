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
if(!(intval($r_hash_id) == intval($_SESSION['hash_id'])))
{
    print ":P";
    exit();
}

if(!validate_hashID($r_hash_id))
{
    print ":P";
    exit();
}

$sql = $con->prepare("UPDATE slots_done SET is_read=1 WHERE is_read=0 AND hash_id=?");
                    //UPDATE slots_todo SET worker_id = ? WHERE worker_id=0 AND hash_id = ? LIMIT 1
$sql->bind_param("i",$r_hash_id);
$sql->execute();

//$stmt->affected_rows
$sql = $con->prepare("SELECT slot,cracked,clear FROM slots_done WHERE hash_id=? AND is_read=1");
$sql->bind_param("i",$r_hash_id);
$sql->execute();

$sql->bind_result($slot,$is_cracked,$clear);
//{"results":[{"a":1,"b":2},{"a":11,"b":22},{"a":111,"b":222}]}

$slots="";
$cracked="";

while ($sql->fetch())
{
    $slots = $slots . $slot . ",";
    if($is_cracked > 0)
    {
        $cracked = $cracked . '{"slot":' . $slot . "," . '"clear":"' . $clear . '"},' ; 
    }
}
$sql->close();

$sql = $con->prepare("DELETE FROM slots_done WHERE is_read=1 AND hash_id=?");
$sql->bind_param("i",$r_hash_id);
$sql->execute();
$sql->close();

$un_used_slots="";

//$sql = $con->prepare("SELECT slots FROM slots_todo WHERE hash_id=? AND worker_id=0");
$sql = $con->prepare("SELECT slots FROM slots_todo WHERE hash_id=? AND worker_id=''");
$sql->bind_param("i",$r_hash_id);
$sql->execute();

$sql->bind_result($un_used);
while ($sql->fetch())
{
    $un_used_slots = $un_used_slots . $un_used . "," ;
}

$sql = $con->prepare("UPDATE submit_hashes SET timestamp=? WHERE hash_id=?");
                    //UPDATE slots_todo SET worker_id = ? WHERE worker_id=0 AND hash_id = ? LIMIT 1
$sql->bind_param("ii",time(),$r_hash_id);
$sql->execute();

$sql->close();
$con->close();

header('Content-type: application/json');
//{"results":[{"a":1,"b":2},{"a":11,"b":22},{"a":111,"b":222}]}
print '{"slots":[' . rtrim($slots,",") . '],"cracked":[' . rtrim($cracked,",") . '],"un_used":[' . rtrim($un_used_slots,",") . ']}';

/** TODO

POST /ravan/check_done.php HTTP/1.1
Host: 192.168.237.136
Content-Type: application/x-www-form-urlencoded
Content-Length: 24

hash_id=49


RESPONSE: (TODO)
{"slots":[6,7,8,9,10],"hash":"abcdefgh"}

**/
?>