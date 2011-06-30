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

$name = $_REQUEST["name"];
$recip = $_REQUEST["recip"];

$purchaseId = $_REQUEST["purchaseId"];
$confirm = $_REQUEST["confirm"];

if($confirm == "yes"){

  if($purchaseId != ""){
     $query = "delete from purchaseHistory where purchaseId=" . $purchaseId;

     $rs = mysql_query($query) or die("Could not query: " . mysql_error());

     header("Location: " . getFullPath("viewLog.php?recip=" . $recip . "&name=" . $name));

     $query = "select email from viewList, people where viewer=userid and pid='" . $recip . "' and viewer!='" . $recip . "'";

     $rs = mysql_query($query) or die("Could not query: " . mysql_error());

     while($row = mysql_fetch_assoc($rs)){
       $to .= $row["email"] .", ";
     }

     $from = "";

     $subject = $name . "'s WishList has been modified by " . 
       $_SESSION["fullname"];

     $desc = $_REQUEST["desc"];

     $message = "<font color=indianred><b>" . $_SESSION["fullname"] . 
       "</b></font>" . 
       " has <b>deleted</b> the following purchase from " . 
       $name . "'s WishList" .
       "<br><dir>" . $desc . "</dir>";

     print $to . "<br>";
     print $from . "<br>";
     print $subject . "<br>";
     print $message . "<br>";;

     sendEmail($to, $from, $subject, $message, 0);
  }
}
else{

  $query = "select * from items, purchaseHistory where items.iid=purchaseHistory.iid and purchaseHistory.userid='" . $userid . "' and purchaseId=" . $purchaseId;

  $rs = mysql_query($query) or die("Could not query: " . mysql_error());

  if($row = mysql_fetch_assoc($rs)){
    $desc = ($row["purchaseHistory.quantity"] != "1" ? "<font color=red>Bought " . $row["quantity"] . "</font> " : "") . "<i>" . $row["title"] . "</i> - " . $row["description"]; 

?>
<HTML>

<link rel=stylesheet href=../style.css type=text/css>

<title>Delete Purchase</title>
<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">

<tr>
<td valign="top" >

<?php
createNavBar("../home.php:Home|viewList.php?recip=" . $recip . "&name=" . $name . ":View List - " . $name . "|viewLog.php?recip=$recip&name=$name:View Log - $name|:Delete Purchase", false, "");
?>

<h2>Delete Purchase</h2>
<p>
  <?php echo $desc ?> 

<p>
<table bgcolor="violet">
<tr><td>
<h3>Are you sure you want to delete this purchase?</h3>
</td><td>
<form method="post" action="deletePurchase.php">
<input type="hidden" name="purchaseId" value="<?php echo $purchaseId ?>">
<input type="hidden" name="desc" value="<?php echo $desc ?>">
<input type="hidden" name="name" value="<?php echo $name ?>">
<input type="hidden" name="recip" value="<?php echo $recip ?>">
<input type="hidden" name="confirm" value="yes">
<input type="submit" value="Yes">
</form>
</td><td>
<form method="post" action="viewLog.php">
<input type="hidden" name="name" value="<?php echo $name ?>">
<input type="hidden" name="recip" value="<?php echo $recip ?>">
<input type="submit" value="No">
</form>
</td></tr>
<tr><td colspan=3 align=center>
<b>Note</b>: an email will be sent to everyone that can view <?php echo $name ?>'s WishList
</td></tr></table>


<?php
}
else{
  print "<h1><font color=red>That purchase does not belong to you!</font></h1>";
}

}

?>
