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

<title>Edit Personal Information</title>
<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>

<td valign="top" >

<?php
createNavBar("../index.php:Home|updateAccount.php:Update Account|:Edit Personal Info", false, "changeInfo");
?>

<center>
<p>&nbsp;
<p>&nbsp;

<?php
$query = "select * from people where userid='" . $userid . "'";

$result = mysql_query($query) or die("Could not query: " . mysql_error());

$rs = mysql_fetch_assoc($result);

?>

<form method="post" action="validate_editInfo.php">
<table style="border-collapse: collapse;" id="AutoNumber1" border="1" bordercolor="#111111" cellpadding="2" cellspacing="0">
<tr><td colspan="2" align="center" bgcolor="#6702cc"> <b><font size=3 color="#ffffff">
Information on File</font>
</td></tr>

<tr><td class="lightYellow" align="right">
<b>First Name</b>
</td><td bgcolor="#eeeeee">
<input type=text size="30" name="firstname" value="
<?php echo $rs['firstname'] ?>">
</td></tr>

<tr><td class="lightYellow" align="right">
<b>Last Name</b>
</td><td bgcolor="#eeeeee">
<input type=text size="30" name="lastname" value="
<?php echo $rs['lastname'] ?>">
</td></tr>

<tr><td class="lightYellow" align="right">
<b>Suffix</b>
</td><td bgcolor="#eeeeee">
<input type=text size="30" name="suffix" value="
<?php echo $rs['suffix'] ?>">
</td></tr>

<tr><td class="lightYellow" align="right">
<b>Street</b>
</td><td bgcolor="#eeeeee">
<input type=text size = "30" name="street" value="
<?php echo $rs['street'] ?>">
</td></tr>

<tr><td class="lightYellow" align="right">
<b>City</b>
</td><td bgcolor="#eeeeee">
<input type=text size = "30" name="city" value="
<?php echo $rs['city'] ?>">
</td></tr>

<tr><td class="lightYellow" align="right">
<b>State</b>
</td><td bgcolor="#eeeeee">
<input type=text size = "30" name="state" value="
<?php echo $rs['state'] ?>">
</td></tr>

<tr><td class="lightYellow" align="right">
<b>Zip Code</b>
</td><td bgcolor="#eeeeee">
<input type=text size = "10" name="zip" value="
<?php echo $rs['zip'] != "0" ? $rs['zip'] : "" ?>">
</td></tr>

<tr><td class="lightYellow" align="right">
<b>Home Phone</b>
</td><td bgcolor="#eeeeee">
<input type="text" name="phone" value="
<?php echo $rs['phone'] ?>
">
</td></tr>

<tr><td class="lightYellow" align="right">
<b>Mobile Phone</b>
</td><td bgcolor="#eeeeee">
<input type="text" name="mobilephone" value="
<?php echo $rs['mobilephone'] ?>
">
</td></tr>

<tr><td class="lightYellow" align="right">
<b>Email</b>
</td><td bgcolor="#eeeeee">
<input type="text" name="email" size="50" value="<?php echo $rs['email'] ?>">
</td></tr>

<tr><td class="lightYellow" align="right">
<b>BirthDay</b>
</td><td bgcolor="#eeeeee">
   <select name="bmonth" size="1">
<?php
$month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

for($i=0; $i < count($month); $i++){

  print "<option ";
  print $month[$i] == $rs['bmonth'] ? "selected" : ""; 
  print " value=\"" . $month[$i] . "\">" . $month[$i] . "</option>";
}
?>
    </select>

   <select name="bday" size="1">
<?php
for($i = 1; $i < 32; $i++){
   print "<option ";
   print $i == $rs["bday"] ? "selected" : "";
   print " value=\"" . $i . "\">" . $i . "</option>";
}
?>
   </select>


</td></tr>

<tr><td class="lightYellow" align="right">
<b>HomePage URL</b>
</td><td bgcolor="#eeeeee">
<input type="text" name="url" size="100" value="<?php echo $rs['url'] ?>">
</td></tr>
<tr><td colspan="2" align="center" bgcolor="#c0c0c0">
<input type="submit" value="Update" style="font-weight:bold">
</td></tr>

</table>
<br>

</form>


</td></tr></table>
</body>
</html>
