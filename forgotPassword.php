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

?>

<HTML>

<link rel=stylesheet href=style.css type=text/css>

<head>
<title>Forgot password</title>
</head>

<?php 
$userid = $_REQUEST["userid"];
$password = $_REQUEST["password"];

if($userid != ""){

   if($_REQUEST["changePassword"] == "true"){
     
     // come up with random password
     $salt = "abchefghjkmnpqrstuvwxyz0123456789";
     srand((double)microtime()*1000000); 
     $i = 0;
     while ($i <= 7) {
       $num = rand() % 33;
       $tmp = substr($salt, $num, 1);
       $pass = $pass . $tmp;
       $i++;
     }
     
     $query = "update people set password='" . md5($pass) . 
       "' where userid='" . $_REQUEST["userid"] . "'";

     $result = mysql_query($query) or die("Could not query: " . mysql_error());

     $to = $_REQUEST["email"];
     $from = "";
     $subject = "Your new WishList Password";
     $message = "Your new password is <b>" . 
       $pass . "</b><p>You can change your password by going to <b>Update Your Account</b> and clicking on the <b>Change Password</b> button ";

     sendEmail($to,$from,$subject,$message,0);
?>
     <BODY>   
     <table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
     <tr>
     <td valign="top" align=center>
     <p>&nbsp;
     <p>&nbsp;  
     You should receive an email shortly with your new password
     <br>
     <a href="login.php">Return to login<a>

<?php     
   }
   else{

     $query = "Select * from people where userid='" . $userid . "'";
     $result = mysql_query($query) or die("Could not query: " . mysql_error());

     if($row = mysql_fetch_assoc($result)){
?>
     <BODY>
     <table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
     <tr>
     <td valign="top" align=center>
     <p>&nbsp;
     <p>&nbsp;
     <form name=theForm method=post action=forgotPassword.php>

     <input type=hidden name=email value=<?php echo $row["email"] ?>>
     <input type=hidden name=changePassword value="true">
     <input type=hidden name=userid value=<?php echo $_REQUEST["userid"] ?>>
     <b>Click here to reset you password</b><br> <input type=submit value="Reset Password">
     </form>

<?php
     }
     else{
?>
     <body>
     <table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
     <tr>
     <td valign="top" align=center>
     <p>&nbsp;
     <p>&nbsp;
     No user found with that id - <a href="forgotPassword.php">Try Again?</a>        
     <p><a href="login.php">Return to login page</a>

<?php
    }
  }
}
else{

?>
<BODY onLoad="document.theForm.userid.focus();">
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>
<td valign="top" align=center>
<p>&nbsp;
<p>&nbsp;
<form name=theForm method=post action=forgotPassword.php>
<table style="border-collapse: collapse;" id="AutoNumber1" border="1" bordercolor="#11111" cellpadding="2" cellspacing="0" bgcolor="lightyellow">
<tr><td colspan="2" align="center" bgcolor="#6702cc"> <b><font size=3 color="#ffffff">
Forgot Password?
</td></tr>
<tr>
<td><b>Enter your WishList userid</b></td>
<td><input type=text name=userid></td>
</tr>
<tr><td colspan=2 bgcolor="#c0c0c0" align=center>
<input type=submit value=Submit>
</td>
</tr>
</table>
</form>



<?php
}
?>
</td>
</tr>
</table>
</body>
</html>
