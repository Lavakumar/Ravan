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

#$worker_ids = explode(",",$_GET['worker_ids']);
$r_clear = $_POST['clear'];
$r_hash_id = $_POST['hash_id'];

if(!(intval($r_hash_id) == intval($_SESSION['hash_id'])))
{
    print ":P";
    exit();
}

if(!(validate_result($r_clear) && validate_hashID($r_hash_id)))
{
    print ":P";
    exit();
}

$sql = $con->prepare("UPDATE submit_hashes SET clear=? WHERE hash_id=?");
$sql->bind_param("si",$r_clear,$r_hash_id);
$sql->execute();
$sql->close();
$con->close();
?>