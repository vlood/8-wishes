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

// throw this flag to tell funcLib not to redirect us to login.php which it
// would try to do if the user is not logged in
$ignoreSession = true;

include "funcLib.php";

$stop = true;
?>
<HTML>

<link rel=stylesheet href=style.css type=text/css>

<title>WishList Register</title>
<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>

<td valign="top" background="images/candy-cane_background.gif">
<center>
<?php

if($_REQUEST["fname"] != ""){
if($stop){
  return;                      
}
  $fname = convertString($_REQUEST["fname"]);
  $lname = convertString($_REQUEST["lname"]);
  $suffix = convertString($_REQUEST["suffix"]);
  $userid = convertString($_REQUEST["userid"]);
  $email = convertString($_REQUEST["email"]);
  $comment = convertString($_REQUEST["comment"]);

  // send an email out to all the admins
  $query = "select * from people where admin=1";
  $result = mysql_query($query) or die("Could not query: " . mysql_error());  

  $to = "";

  while($row = mysql_fetch_assoc($result)){
    $to .= $row["email"] . ",";
  }

  $subject = "Request for new WishList account";
  $message = "The following person is requesting a new account<p><b>First Name:</b> " . $fname . "<br><b>Last Name:</b> " . $lname ."<br><b>Suffix:</b> " . $suffix . "<br><b>Desired Userid:</b> " . $userid . "<br><b>Email:</b> " . $email . "<br><b>Comment</b><br>" .$comment;

  $result = sendEmail($to, "", $subject, $message, 0);

  if($result == 1)
    print "<h2>Thank you. You should receive an email shortly with your account details</h2>";
  else
    print "<h1>An unexpected error has occured</h1>";

}
else{ 
?>
<h2>Welcome to the WishList Site</h2>

<table width=80%><tr><td align=left> 

<?php
if($stop){
  print "Too many requests have been made within the last hour.  This feature has been temporarily disabled.";
  return;
}
?>
The following information will be submitted to the administrator of
the WishList Site who will create an account for you.  You will
receive an email with your WishList password when the account is
created.

</td></tr></table>
<p>&nbsp;
<form method=post action="registerUser.php">
<table style="border-collapse: collapse;" id="AutoNumber1" border="0" bordercolor="#111111" cellpadding="2" cellspacing="0" bgcolor=lightYellow>

<tr><td colspan="2" align="center" bgcolor="#6702cc">
<font size=3 color=white><b>Please Fill In</b></font>
</td></tr>
<tr>
<td align=right><b>First Name</b></td>
<td><input type=text name=fname size=20></td>
</tr>
<tr>
<td align=right><b>Last Name</b></td>
<td><input type=text name=lname size=20></td>
</tr>
<tr>
<td align=right><b>Suffix</b></td>
<td><input type=text name=suffix size=20></td>
</tr>
<tr>
<td align=right><b>Desired Userid</b></td>
<td><input type=text name=userid size=20></td>
</tr>

<tr>
<td align=right><b>Email</b></td>
<td><input type=text name=email size=40></td>
</tr>

<tr>
<td align=right><b>Comment</b></td>
<td><textarea name=comment rows=3 cols=60 onfocus="this.value=''"></textarea></td>
</tr>

<tr><td align="center" colspan="2" bgcolor="#c0c0c0">
<input type="submit" value="Submit" style="font-weight:bold">

</td></tr>
</table>

</form>

</center>
</td>
</tr>
</table>
</body>
</html>
<?php
   }
?>
