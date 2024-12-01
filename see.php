<?php
session_start();

if(isset($_POST['submit']))
{
    $title=$_POST['title'];
    $Description=$_POST['Description'];
    $idno=$_SESSION['IDNo'];

$sql1="insert into LMS.recommendations (Book_Name,Description,IDNo) values ('$title','$Description','$idno')"; 

echo $idno;
}
?> 
