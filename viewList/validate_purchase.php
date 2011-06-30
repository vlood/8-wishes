<?php
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

include "../funcLib.php";

$receiverName = $_REQUEST["receiverName"];
$receiverUserid = $_REQUEST["receiverUserid"];

$message = "<font color=indianred><b>" . $_SESSION["fullname"] .
"</b></font> has checked off the following items from <b>" . $receiverName .
"'s</b> WishList<dir>";

reset ($_REQUEST);
$ar = array();

while(list($iid, $value) = each ($_REQUEST)){
  
  if($value == "on"){
    
    array_push($ar, $iid . "=1");
    $query = "insert into purchaseHistory (iid, userid, quantity, boughtDate) values (" .
      $iid . ", '" . $userid . "', 1, NOW())";
    
    $rs = mysql_query($query) or die("Could not query: " . mysql_error());
    
  }
  elseif (is_numeric($value)) {
    $bought = $value;
    
    if($bought > 0){
      array_push($ar, $iid . "=" . $bought);
      $query = "insert into purchaseHistory (iid, userid, quantity, boughtDate) values (" . 
        $iid . ", '" . $userid . "', " . $bought . ", NOW())";
      $rs = mysql_query($query) or die("Could not query: " . mysql_error());
    }         
  }
  
}

foreach($ar as $i){
  $pos = strpos($i, "=");
  $iid = substr($i, 0, $pos);
  $value = substr($i, $pos+1, strlen($i));
  
  $query = "SELECT title, description FROM items WHERE iid=" . $iid;
  
  $rs = mysql_query($query) or die("Could not query: " . mysql_error());
  
  if($row = mysql_fetch_assoc($rs)){
    $message .= "<font color=red>Bought " . $value . " of</font> <i>" . $row["title"] . 
      "</i> - " . $row["description"] . "<p>";
    
  }
}

$query = "SELECT people.email FROM viewList, people WHERE viewer=people.userid AND pid='" . $receiverUserid . "' AND viewer !='" . $receiverUserid . "'";

$rs = mysql_query($query) or die("Could not query: " . mysql_error());

while($row = mysql_fetch_assoc($rs)){
  $to .= $row["email"] .", ";
}

$message .= "</dir>";
$subject = $receiverName . "'s Wishlist has been modified by " . $_SESSION["fullname"];

$res = sendEmail($to, "", $subject, $message, 0);

if($res != 0){
  header("Location: " . getFullPath("viewList.php?recip=". $receiverUserid ."&name=". $receiverName));
}
else{
  print "<html><body>An error has occurred while sending the email.</body></html>";
}


?>
<html>
<body>
Hello
</body>
</html>
