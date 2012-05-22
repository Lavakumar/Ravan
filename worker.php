<!--
The MIT License (MIT)

Copyright (c) 2010- Lavakumar Kuppan

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
-->


<html>
    <head>
        <title>Ravan - JavaScript Distributed Computing System</title>
    <style>
    #home_link
    {
        position:absolute;
        left:5px;
        top:2px;
    }
    #contact_link
    {
        position:absolute;
        right:5px;
        top:2px;
    }
    #logo
    {
        display:block;
        margin-left:auto;
        margin-right:auto;
    }
    #banner
    {
        text-align:center;
    }
    #main
    {
        text-align:center;
        margin:auto;
        width:700px;
    }
    </style>
    </head>
    <body>
    <div id="home_link"><a target="_blank" href="http://www.andlabs.org/tools/ravan/ravan.html">More Details</a></div>
    <div id="contact_link">Feedback/Comments/Questions: <a target="_blank" href="https://twitter.com/lavakumark">@lavakumark</a></div>
    <img id="logo" src="/img/ravan.jpg"/>
    <div id="banner"><b>JavaScript Distributed Computing System (BETA)</b></div>
    <br>
    <div id="main">
        Your system can become a node and help crack hashes. Press 'Start' if you are willing to donate some of your system's processing power to Ravan. You can stop this anytime you like.
        <br><br>
        <b>Note:</b> DO NOT open more than one Ravan worker in a single browser.
        <br>
        Requires a browser with WebWorker support. Chrome/Safari recommended.
        <br>
    <input type="submit" id="toggler" value="Start" onclick="toggle_worker()"/>
    <div id="out"></div>
    </div>
    <script>    
<?php
session_start();
$_SESSION['id'] = session_id();
if(ctype_digit($_GET['hash_id']))
{
    print "var hash_id=" . $_GET['hash_id'] . ";\n";
    //print "<script> w.postMessage(" . $_GET['hash_id'] ."); </script>";
}
else
{
    print "var hash_id=0;\n";
    //print "<h1> Invalid Hash_ID provided</h1>";
}
?>
    
    var w;
    if(hash_id == 0)
    {
        document.getElementById('out').innerHTML = "<h1> Invalid Hash_ID provided</h1>";
    }
    function toggle_worker()
    {
        if(document.getElementById('toggler').value == "Start")
        {
            if(hash_id == 0)
            {
                document.getElementById('out').innerHTML = "<h1> Invalid Hash_ID provided</h1>";
            }
            else
            {
                document.getElementById('toggler').value = 'Stop';
                w = new Worker('ravan_worker.js');
                w.onmessage = function (event){if(event.data=="0"){document.getElementById('out').innerHTML="";toggle_worker()}else{document.getElementById('out').innerHTML =  event.data}}
                w.postMessage(hash_id);
            }
        }
        else
        {
            w.terminate();
            document.getElementById('toggler').value = "Start";
        }
    }
    </script>
    </body>
    </html>

