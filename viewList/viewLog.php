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

$recip = $_REQUEST["recip"];
$name = $_REQUEST["name"];

?>
<HTML>

<link rel=stylesheet href=../style.css type=text/css>

<title><?php echo $name ?>'s WishList Change Log</title>
<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>
<td valign="top" >

<?php
createNavBar("../home.php:Home|viewList.php?recip=" . $recip . "&name=" . $name . ":View List - " . $name . "|:View Log " . $name, false, "");
?>

<p>
<font size="2">

<?php

$query = 
  "select firstname, lastname, suffix, purchaseHistory.userid, purchaseHistory.boughtDate, purchaseHistory.quantity, title, items.description, purchaseId from items, categories, people, purchaseHistory where items.iid=purchaseHistory.iid and categories.cid=items.cid and categories.userid='" . $recip . "' and people.userid=purchaseHistory.userid order by purchaseHistory.boughtDate desc";

$rs = mysql_query($query) or die("Could not query: " . mysql_error());
 
while($row = mysql_fetch_assoc($rs)){
?>

  On <font color="indianred"><?php echo parseDate($row["boughtDate"]) ?></font>, 
  <font color="navy"><?php echo $row["firstname"] . ' ' . $row["lastname"] . ' ' . $row["suffix"] ?></font> made the following changes
<?php
  if($row["userid"] == $userid){ 
?>    <b><a href="deletePurchase.php?recip=<?php echo $recip ?>&name=<?php echo $name?>&purchaseId=<?php echo $row["purchaseId"] ?>">[Undo this purchase]</a></b>
<?php
}
?>
  <br>
  <?php 
    print $row["quantity"] != "1" ? "<font color=red>Bought " . $row["quantity"] . "</font> " : "" ?><i><?php echo $row["title"] ?></i> 
  - <?php echo $row["description"] ?> 

  <p>
<?php
}
?>
</font>
</body>
</html>


