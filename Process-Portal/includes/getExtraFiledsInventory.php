<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
$q=$_GET['q'];
if($q=="409" OR $q=="402" OR $q=="403")
{	
  include"../partial-forms/customers-list.php";
  if($_GET['q2']=="SIM")
  {
    include"../partial-forms/harware-devices-list.php";
  }
}elseif($q=="407")
{
 if($_GET['q2']=="SIM" OR $_GET['q2']=="HARDWARE")
  {
  include"../partial-forms/emp-list.php";
  }
}else{
}

?>