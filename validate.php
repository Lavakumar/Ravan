<?php
/*
The MIT License (MIT)
Copyright (c) 2010- Lavakumar Kuppan

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

function validate_slots($v_slots)
{
    for($i=0;$i<count($v_slots);$i++)
    {
        //echo  $v_slots[$i];
        if(!preg_match('/^\[\d{1,10},\d{1,10},\d{1,10},\d{1,10},\d{1,10}\]$/',$v_slots[$i]))
        {
            return false;
            
        }
    }
    return true;
}

function validate_hashID($v_hashID)
{
    if(preg_match('/^\d{1,10}$/',$v_hashID) && intval($v_hashID) > 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function validate_hash($v_hash)
{
    if(preg_match('/^[a-f\d]{32,128}$/i',$v_hash))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function validate_salt($v_salt)
{
    if(strlen($v_salt) < 100)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function validate_keyspace($v_ks)
{
    if((strlen($v_ks) > 0) && (strlen($v_ks) < 150)&& (!preg_match('/(\\\\|")/',$v_ks)))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function validate_saltlocation($v_sl)
{
    if(preg_match('/^[01]{1}$/',$v_sl))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function validate_algo($v_algo)
{
    if(preg_match('/^[0123]{1}$/',$v_algo))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function validate_result($v_result)
{
    if((strlen($v_result) < 100) && (!preg_match('/(\\\\|")/',$v_result)))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function validate_slot($v_slot)
{
    if(preg_match('/^\d{1,10}$/',$v_slot))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function validate_cracked($v_cracked)
{
    if(preg_match('/^[01]{1}$/',$v_cracked))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function validate_password($v_password)
{
    if(preg_match('/^[a-z\d]{20,30}$/',$v_password))
    {
        return true;
    }
    else
    {
        return false;
    }
}


?>