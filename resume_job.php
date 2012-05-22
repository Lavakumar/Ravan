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
if(!validate_hashID($r_hash_id))
{
    print '{"error":"Invalid Hash ID value"}';
    exit();
}

$sql = $con->prepare("SELECT password,hash,algo FROM submit_hashes WHERE hash_id=?");
$sql->bind_param("i",$r_hash_id);
$sql->execute();
$sql->bind_result($password,$hash,$algo);
$sql->store_result();
if($sql->num_rows > 0)
{
    $sql->fetch();
    if(($password === $_POST['password']) && ($hash === $_POST['hash']))
    {
        $result = '{"code":200,"error":"","algo":' . $algo . '}';
        print $result;
        $_SESSION['hash_id'] = $r_hash_id;
        $sql = $con->prepare("DELETE FROM slots_todo WHERE hash_id=?");
        $sql->bind_param("i",$r_hash_id);
        $sql->execute();
        exit();
    }
    else
    {
         print '{"error":"Invalid Hash and Password values"}';
         exit();
    }

}
else
{
    print '{"error":"Hash ID does not exist"}';
    exit();
}
