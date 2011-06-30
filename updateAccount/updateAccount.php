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

?>

<HTML>
<link rel=stylesheet href=../style.css type=text/css>

<title>Update Account Information</title>
<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>
<td valign="top" >

<?php
createNavBar("../home.php:Home|:Update Account", false, "");
?>

<center>
<p>&nbsp;

<?php

$query = "select * from people where userid='" . $userid . "'";

$result = mysql_query($query) or die("Could not query: " . mysql_error());

$row = mysql_fetch_assoc($result);

?>

<table width="100%">
<tr><td align=center valign=top>



<form method="post" action="editInfo.php">

<table style="border-collapse: collapse;" id="AutoNumber1" border="0" bordercolor="#111111" cellpadding="2" cellspacing="0" bgcolor=lightYellow>
<tr><td colspan="2" align="center" bgcolor="#6702cc">
<font size=3 color=white><b>Information On File</b></font>
</td></tr>
<tr><td align="right">
<b>Name</b>
</td><td bgcolor="white">
<?php echo $row['firstname'] . ' ' . $row['lastname'] . ' ' . $row['suffix'] ?>
</td></tr>
<tr><td align="right" valign="top">
<font face=courier>*</font> <b> Address</b>
</td><td bgcolor=white>
<?php echo $row["street"] ?>
<?php echo $row["city"] != "" ? "<br>" . $row["city"] : "" ?><?php echo $row["state"] != "" ? ", " . $row["state"] : "" ?>
<?php echo $row["zip"] != "0" ? "&nbsp;&nbsp;" . $row["zip"] : "" ?>
</td></tr>
<tr><td align="right">
<font face=courier>*</font> <b>Home Phone</b>
</td><td bgcolor=white>
<?php echo $row["phone"] ?>
</td></tr>
<tr><td align="right">
<font face=courier>*</font> <b>Mobile Phone</b>
</td><td bgcolor=white>
<?php echo $row["mobilephone"] ?>
</td></tr>
<tr><td align="right">
<b>Email</b>
</td><td bgcolor=white>
<?php echo $row["email"] ?>
</td></tr>
<tr><td align="right">
<b>BirthDay</b>
</td><td bgcolor=white>
<?php echo $row["bmonth"] . " " . $row["bday"] ?>
</td></tr>
<tr><td align="right">
<font face=courier>*</font> <b>HomePage URL</b>
</td><td bgcolor=white>
<a target="_blank" href="<?php echo $row["url"] ?>"><?php echo $row["url"] ?></a>
</td></tr>
<tr><td align="center" colspan="2" bgcolor="#c0c0c0">
<input type="submit" value="Edit Personal Info" style="font-weight:bold">

</td></tr></table>
[<font face=courier>*</font>] Field will only be displayed if you allow a person to view it
</form>


<table>
<tr>
<td onclick="location.href='changePassword.php'" height=27pt width=200pt align=center style="background-image: url(../images/button.gif); background-repeat: no-repeat; background-position:center center;" >
<a href="changePassword.php">Change Password</a>
</td></tr>
<tr>
<td onclick="location.href='manageListAccess.php'" height=27pt width=200pt align=center style="background-image: url(../images/button.gif); background-repeat: no-repeat; background-position:center center;" >
<a href="manageListAccess.php">Manage List Access</a>
</td></tr>
</table>


</td>
</tr>
</table>
</body>
</html>
