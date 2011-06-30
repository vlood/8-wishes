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

if($_REQUEST["delete"] != ""){
  $query = "update categories set catSubDescription='' where userid='" . $_SESSION["userid"] . "' and catSortOrder=-1000";
  mysql_query($query) or die("Could not query: " . mysql_error());
  header("Location: " . getFullPath("modifyList.php"));
}

if($_REQUEST["update"] != ""){
  $catSubDescription = convertString($_REQUEST["catSubDescription"]);
  $query = "update categories set catSubDescription='" . $catSubDescription . "' where userid='" . $_SESSION["userid"] . "' and catSortOrder=-1000";
  mysql_query($query) or die("Could not query: " . mysql_error());
 header("Location: " . getFullPath("modifyList.php"));
}

?>
<HTML>

<link rel=stylesheet href=../style.css type=text/css>

<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>
<td valign="top" >

<?php
createNavBar("../home.php:Home|modifyList.php:Modify WishList|:Edit List Comment");
?>

<center>
<p>&nbsp;
<p>&nbsp;


<table style="border-collapse: collapse;" id="AutoNumber1" border="1" bordercolor="#11111" cellpadding="2" cellspacing="0" bgcolor="lightyellow">
<tr><td colspan="2" align="center" bgcolor="#6702cc"> <b><font size=3 color="#ffffff">
<b>Edit List Comment</b>
</font></td></tr>
<?php

  $query = "select catSubDescription from categories where userid='" . $_SESSION["userid"] . "' and catSortOrder=-1000";
  $rs = mysql_query($query) or die("Could not query: " . mysql_error());

if($row = mysql_fetch_assoc($rs)){
?>
<tr><td colspan="2" align=center>
<br>
<?php echo $row["catSubDescription"] ?>
<br>&nbsp;
</td></tr>

<form method="post" action="editListComment.php">

<tr><td align="right">
<b>List Comment</b>
</td>
<td bgcolor="#eeeeee">
<textarea name="catSubDescription" cols="75" rows="5"><?php echo str_replace("<br>", "\r\n", $row["catSubDescription"]) ?></textarea>
</td></tr>

<tr><td colspan="2" bgcolor="#c0c0c0">
    <table width="100%">
    <tr><td align=center valign="center">
    <input type="submit" name="update" value="Update List Comment" style="font-weight:bold">
  </td>

  <td align=center valign="center">
    <input type="submit" name="delete" value="Delete List Comment" style="font-weight:bold; color:red">
  </td></tr>
  </table>

</td></tr>
</form>
</table>

<?php
}
?>

</td></tr></table>
</body>
</html>
