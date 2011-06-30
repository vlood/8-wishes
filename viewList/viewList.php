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

$confirm = $_REQUEST["confirm"];

if($confirm == "No"){
     header("Location: " . getFullPath("../home.php"));
}
?>
<HTML>

<link rel=stylesheet href=../style.css type=text/css>

<head>
<script type="text/javascript" src="../getPriceHistory.js"></script>
</head>

<title><?php echo $name ?>'s WishList</title>

<?php
// Alert $userid if new comments have been added to $recip's list since the 
// last time $userid viewed it (unless $recip == $userid of course)
if($recip != $userid){
  $query = "select lastViewDate from comments, viewList where comments.userid=viewList.pid and pid='" . $recip . "' and viewer='" . $userid . "' and comment_userid!='" . $userid . "' and date > lastViewDate";

  $rs = mysql_query($query) or die("Could not query: " . mysql_error());

  if(mysql_num_rows($rs) > 0){
    $alert = "onLoad=\"javascript:alert('New comments have been added since the last time you viewed this list')\"";
  }
}
?>

<BODY <?php echo $alert ?>>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>
<td valign="top">

<?php
createNavBar("../home.php:Home|:View List - " . $_REQUEST["name"], false, "viewOther");
?>

<br>

<table width="100%" style="border: 1px solid lightblue">

<tr ><td>
  <table cellspacing="0" cellpadding="2" nosave border="0" style="border: 1px solid rgb(128,255,128)" >
    <tr>
      <td bgcolor="lightyellow" align="left" valign="top"> <font size="2">
       Click <a class="menuLink" href="viewLog.php?recip=<?php echo $recip ?>&name=<?php echo $name ?>">here</a> to view the purchase log
      </td>
    </tr>
  </table>
</td>
<td align="center">

<?php

$query = "select lastModDate from people where userid='" . $recip . "'";

$rs = mysql_query($query) or die("Could not query: " . mysql_error());
   

  if($row = mysql_fetch_assoc($rs)){
?>
<font size=2>
<font color=indianred><?php echo $name ?></font> last modified this list on <font color=navy><?php echo parseDate($row["lastModDate"]) ?></font></font>
<?php
}
?>
  
</td><td align="right">
  <table cellspacing="0" cellpadding="2" nosave border="0" style="border: 1px solid rgb(128,255,128)" >
    <tr>
      <td bgcolor="lightyellow" align="left" valign="top"> <font size="2">
        <b><a class="menuLink" target="_blank" href="print.php?recip=<?php echo $recip ?>&name=<?php echo $name ?>">Printer Friendly Version</a></b>
      </td>
    </tr>
  </table>
</td></tr></table>

<br>

<font size="2">

<?php

if($recip == $userid and $confirm != "Yes"){

  print "<center><p>&nbsp;<form method=post>";
  print "<b>Are you sure you want to view your own list?<br>You will be able to see any purchases that may have been made!  This could ruin the surprise</b>";
  print "<p><input type=submit name=confirm value=Yes style=\"font-weight:bold\"> <input type=submit name=confirm value=No style=\"font-weight:bold\">";
  print "</form>\n";
  print "<p>&nbsp;<p><b>Perhaps you wanted to <a href=\"../modifyList/modifyList.php\">Modify</a> your list instead?</b></center>";
  print "</td></tr></table></body></html>";
  return;
}
?>
<form method="post" action="validate_purchase.php">
<input type="hidden" name="receiverUserid" value="<?php echo $recip ?>">
<input type="hidden" name="receiverName" value="<?php echo $name ?>">

<?php

  printList2($recip, $userid, $name, 0);

?>

<p>

<input type="submit" value="Submit" style="font-weight:bold">
<input type="reset" value="Reset" style="font-weight:bold">

</form>

<hr>

<font size="4"><b>Comments</b></font> - <?php echo $name ?> will not see these
<p>

<?php

if($recip != $userid){

?>
<?php
 $query = "select commentId, comment_userid, firstname, lastname, suffix, comment, date from comments, people where comments.userid='" . $recip . "' and comments.comment_userid=people.userid order by date desc";

$rs = mysql_query($query) or die("Could not query: " . mysql_error());

while($row = mysql_fetch_assoc($rs)){
?>
<font color="navy"><?php echo $row["firstname"] . ' ' . $row["lastname"] . ' ' . $row["suffix"] ?> made the following comments on <?php echo parseDate($row["date"]) ?></font>
<?php
  if($userid == $row["comment_userid"]){
?>
    <b><a class="menuLink" href="deleteComment.php?commentId=<?php echo $row["commentId"] ?>&recip=<?php echo $recip ?>&name=<?php echo $name ?>"  >[Remove Comment]</a></b>
<?php
  }
?>
  
<br>
<?php echo $row["comment"] ?><p>

<?php
  }
?>
<form method="post" action="addComment.php">
<input type="hidden" name="recip" value="<?php echo $recip ?>">
<input type="hidden" name="name" value="<?php echo $name ?>">
<input type="submit" value="Add New Comment" style="font-weight:bold">
</form>

<?php
}
else{
  print "<font color=red><b>You are not allowed to view comments added to your list</b></font>";
}
?>

</font>
</td>
</tr>
</table>
<!--
<script type="text/javascript" src="http://www.assoc-amazon.com/s/link-enhancer?tag=<?php echo $amazonTag; ?>"></script>
<noscript><img src="http://www.assoc-amazon.com/s/noscript?tag=<?php echo $amazonTag; ?>" /></noscript>
-->
</body>
</html>

<?php

$query = "update viewList set lastViewDate=NOW() where pid='" . $recip . "' and viewer='" . $userid . "'";

$rs = mysql_query($query) or die("Could not query: " . mysql_error());

?>
