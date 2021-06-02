<?php
if(isset($_POST['auto1'])) $auto1=$_POST['auto1']; else $auto1='';
if(isset($_POST['nopeus1'])) $nopeus1=$_POST['nopeus1']; else $nopeus1='';
if(isset($_POST['nopeus2'])) $nopeus2=$_POST['nopeus2']; else $nopeus2='';
if(isset($_POST['matka'])) $matka=$_POST['matka']; else $matka='';
header("location: index.php?auto1=$auto1&nopeus1=$nopeus1&nopeus2=$nopeus2&matka=$matka");
?>