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


if(isset($_REQUEST['setup'])){
  // if true then we need to setup the phpWishList environment
  // for the first time
  createConfig();
  exit;
}

include "funcLib.php";

if($_SESSION['admin'] != 1){
  print "Shame on you";
  exit;
}
                      
$message = "<h2>Admin Page</h2>";

if($_REQUEST["action"] == "commitDelete"){
  $delUserid = $_REQUEST["userid"];
  if($_REQUEST["confirm"] == "Yes"){
    $query = "delete from viewList where pid='" . $delUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    $query = "delete from viewList where viewer='" . $delUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    
    $query = "delete from comments where userid='" . $delUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    $query = "delete from comments where comment_userid='" . $delUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    
    $query = "delete from purchaseHistory where userid='" . $delUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    
    $query = "select iid from items, categories where items.cid=categories.cid and userid='" . $delUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    while($row = mysql_fetch_assoc($result)){
      $query = "delete from purchaseHistory where iid=" . $row["iid"];
      mysql_query($query) or die("Could not query: " . mysql_error());
      $query = "delete from items where iid=" . $row["iid"];
      mysql_query($query) or die("Could not query: " . mysql_error());
    }
    
    $query = "delete from categories where userid='" . $delUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    
    $query = "delete from people where userid='" . $delUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    
    $message = "<h2>Person deleted</h2>";
  }
}
elseif($_REQUEST["action"] == "commitEdit"){
  $oldUserid = $_REQUEST["oldUserid"];
  $newUserid = $_REQUEST["newUserid"];

  if($oldUserid != "" and $newUserid != "" and $oldUserid != $newUserid){

    $query = "update viewList set pid='" . $newUserid . "' where pid='" . $oldUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    $query = "update viewList set viewer='" . $newUserid . "' where viewer='" . $oldUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    
    $query = "update comments set userid='" . $newUserid . "' where userid='" . $oldUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    $query = "update comments set comment_userid='" . $newUserid . "' where comment_userid='" . $oldUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    
    $query = "update purchaseHistory set userid='" . $newUserid . "' where userid='" . $oldUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    
    $query = "update categories set userid='" . $newUserid . "' where userid='" . $oldUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    
    $query = "update people set userid='" . $newUserid . "' where userid='" . $oldUserid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
  }
}
elseif($_REQUEST["action"] == "commitAdd"){
  $fname = convertString($_REQUEST["fname"]);
  $lname = convertString($_REQUEST["lname"]);
  $suffix = convertString($_REQUEST["suffix"]);
  $desiredUserid = convertString($_REQUEST["userid"]);
  $email = convertString($_REQUEST["email"]);
  
  if($desiredUserid != ""){
    // come up with random password
    $salt = "abchefghjkmnpqrstuvwxyz0123456789";
    srand((double)microtime()*1000000); 
    $i = 0;
    while ($i <= 7) {
      $num = rand() % 33;
      $tmp = substr($salt, $num, 1);
      $password = $password . $tmp;
      $i++;
    }
    
    $query = "insert into people (userid, firstname, lastname, suffix, email, password) value (" .
      "'" . $desiredUserid . "'," .
      "'" . $fname . "', " .
      "'" . $lname . "', " .
      "'" . $suffix . "', " .
      "'" . $email . "', " .
      "'" . md5($password) . "')";
    
    // first add them to the people table
    $result = mysql_query($query) or die("Could not query: " . mysql_error());

    // now set up the special categories
    $query  = "insert into categories (catSortOrder, userid, name, catSubDescription) value (-10000, '" . $desiredUserid . "', 'Items Under Consideration', 'You are the only person who can view items in this category.')";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());


    $query  = "insert into categories (catSortOrder, userid) value (-1000, '" . $desiredUserid . "')";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());


    $query = "insert into viewList (viewContactInfo, readOnly, pid, viewer) value (1, 0, '" . $desiredUserid . "', '" . $desiredUserid ."')";

    $result = mysql_query($query) or die("Could not query: " . mysql_error());

    $message = "<h2>Account created - Password = " . $password . "</h2>Send <a target=\"_blank\" href=\"sendEmail.php?recip=" . $desiredUserid . "&action=addedUser&subject=WishList%20Account%20Created&message=<b>WishList Account Created</b><br><br><b>Userid:</b> " . $desiredUserid . "<br><b>Password:</b> " . $password . "<br><br>In order to change your password, login to the WishList site with the password provided above.  Once logged in, click on the <b><font color=navy>Update Your Account</font></b> button located at the bottom of the homepage.  On the page that opens, click on the <b><font color=navy>Change Password</font></b> button.\">email?</a><p>";
  }
  else{
    $message = "<h2> The userid cannot be empty!</h2>";
  }
}
elseif($_REQUEST["action"] == "changeAdmin"){

  if($_REQUEST["grantAdmin"] != ""){
    foreach($_REQUEST["grantAdmin"] as $gAdmin){

      $t .= "userid='" . $gAdmin . "' ";
      $s .= "userid!='" . $gAdmin . "' ";
        
    }
    
    $t = str_replace (' ', " or ", trim($t));
    $s = str_replace (' ', " and ", trim($s));

    $query1 = "update people set admin='1' where (" . $t . ")";

    $result = mysql_query($query1) or die("Could not query: " . mysql_error());
    $query2 = "update people set admin='0' where (" . $s . ")";

    $result = mysql_query($query2) or die("Could not query: " . mysql_error());
  }
  else{
    $query = "update people set admin='0'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
  }
  $message = "<h2>Admin privileges changes</h2>";
}
?>

<HTML>

<link rel=stylesheet href=style.css type=text/css>

<title>WishList Admin Page</title>
<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>

<td valign="top">

<?php
createNavBar("home.php:Home|:Admin Page");
?>

<center>

<?php

print $message;

if($_REQUEST["action"] == "add"){
?>
<form method=post>
<input type=hidden name="action" value="commitAdd" value=1>
<table style="border-collapse: collapse;" border="1" bordercolor="#111111" cellpadding="2" cellspacing="0" bgcolor=lightYellow>
<tr><td colspan=2 align=center bgcolor="#6702cc"><font size=3 color=white><b>Add new user</b></font></td></tr>

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
<tr><td align=center colspan=2 bgcolor="#c0c0c0"><input type=submit value="Add User" style="font-weight:bold"></td>
</tr></table>
</form>
<?php
}
elseif($_REQUEST["action"] == "edit"){
  $editUserid = $_REQUEST["userid"];
  print "<form method=post>";
  print "<input type=hidden name=action value=commitEdit>";
  print "<input type=hidden name=oldUserid value=" . $editUserid . ">";
  print "<table style=\"border-collapse: collapse;\" id=\"AutoNumber1\" border=\"1\" bordercolor=\"#111111\" cellpadding=\"2\" cellspacing=\"0\" bgcolor=lightYellow>";
  print "<tr><td colspan=2 align=center bgcolor=\"#6702cc\"><font size=3 color=white><b>Change Userid</b></font></td></tr>";
  print "<tr><td><b>Old Userid</b></td><td>" . $editUserid . "</td></tr>";
  print "<tr><td><b>New Userid</b></td><td><input type=text name=newUserid></td>";
  print "<tr><td align=center colspan=2 bgcolor=\"#c0c0c0\"><input type=submit value=\"Update \" style=\"font-weight:bold\"></td>";
  print "</tr></table>";
  print "</form>";
}
elseif($_REQUEST["action"] == "delete"){
  $deleteUserid = $_REQUEST["userid"];
  print "<form method=post>";
  print "<input type=hidden name=action value=commitDelete>";
  print "<input type=hidden name=deleteUserid value=" . $editUserid . ">";
  print "<table style=\"border-collapse: collapse;\" id=\"AutoNumber1\" border=\"1\" bordercolor=\"#111111\" cellpadding=\"2\" cellspacing=\"0\" bgcolor=lightYellow>";
  print "<tr><td colspan=2 align=center bgcolor=\"#6702cc\"><font size=3 color=white><b>Confirm Delete</b></font></td></tr>";
  print "<tr><td>Are you sure you want to delete <b>" . $deleteUserid . "</b>?";
  print "<p>Everything relating to this user will be deleted";
  print "<tr><td align=center colspan=2 bgcolor=\"#c0c0c0\"><input type=submit name=confirm value=Yes style=\"font-weight:bold\"> <input type=submit name=confirm value=No style=\"font-weight:bold\"></td>";
  print "</tr></table>";
  print "</form>";
}

?>

<form method="post" action="admin.php">
<input type=hidden name=action value=changeAdmin>

<table style="border-collapse: collapse;" id="AutoNumber1" border="1" bordercolor="#111111" cellpadding="2" cellspacing="0">
<tr><td colspan="5" align="center" bgcolor="#6702cc">
<font size=3 color=white><b>Current Users</b></font>
</td></tr>
<tr bgcolor=lightYellow><td align="center">
<b>UserId</b>
</td><td align="center" valign="top">
<b>Name</b>
</td><td align="center">
<b>Last Login</b>
</td><td align="center">
<b>Admin</b>
</td><td align="center">
<b>Email</b>
</td>
</tr>


<?php

$query = "select * from people order by lastname, firstname";

$result = mysql_query($query) or die("Could not query: " . mysql_error());

while($row = mysql_fetch_assoc($result)){
  print "<tr>";
  print "<td><a href=\"admin.php?action=edit&userid=" . $row["userid"] . "\">[edit]</a> <a href=\"admin.php?action=delete&userid=" . $row["userid"] . "\">[del]</a> " . $row["userid"] . "</td>";
  print "<td>" . $row["firstname"] . ' ' . $row["lastname"] . ' ' . $row["suffix"] . "</td>";
  print "<td><font face=Courier>" . parseDate($row["lastLoginDate"], 1) . "</font></td>";
  print "<td align=center><input type=checkbox name=grantAdmin[] value=" . $row["userid"] . " " . ($row["admin"] == 1 ? "checked" : "") . "></td>";
  print "<td>" . $row["email"] . "</td>";
  print "</tr>";
}
?>


<tr><td align="center" colspan="5" bgcolor="#c0c0c0">
<input type="submit" value="Update Admin Status" style="font-weight:bold">

</td></tr></table>

</form>

<table>
<tr>
<td onclick="location.href='admin.php?action=add'" height=27pt width=200pt align=center style="background-image: url(images/button.gif); background-repeat: no-repeat; background-position:center center;" >
<a href="admin.php?action=add">Add New User</a>
</td></tr>
<tr>
<td onclick="location.href='admin.php?setup=true'" height=27pt width=200pt align=center style="background-image: url(images/button.gif); background-repeat: no-repeat; background-position:center center;" >
<a href="admin.php?setup=true">Change Settings</a>
</td></tr>
</table>

</center>
</td>
</tr>
</table>
</body>
</html>

<?php

function createConfig(){

$new_base_dir = $_REQUEST['base_dir'];
$new_base_url = $_REQUEST['base_url'];

$new_admin_email = $_REQUEST['admin_email'];

$new_db_loc = $_REQUEST['db_loc'];
$new_db_name = $_REQUEST['db_name'];
$new_db_userid = $_REQUEST['db_userid'];
$new_db_pass = $_REQUEST['db_pass'];

$new_amazonTag = $_REQUEST['amazonTag'];
$new_amazonDevTag = $_REQUEST['amazonDevTag'];

global $base_dir, $base_url, $admin_email, $db_loc, $db_name,
        $db_userid, $db_pass, $amazonTag, $amazonDevTag;

$allowUpdate = false;

session_name("WishListSite");
session_start();

if((file_exists($base_dir . "/config.php") ||
   file_exists("config.php"))){

  // file already exists so make sure user is an admin
  if($_SESSION["admin"] != 1){
    print "Stop that!!!";
    exit;
  }
  else{
    $allowUpdate = true;
  }
}
else{
  // file doesn't exist so we need to create it
  $doingSetup = true; // tells funcLib we are setting up the environment
  $ignoreSession = true; // tells funcLib to not redirect us to login.php
  $allowUpdate = true;
}

include 'funcLib.php';

if(!isset($_REQUEST['updateValues'])){
  // user hasn't submitted via the form yet so try and set variables to the 
  // current global values if they exist
  $allowUpdate = false;

  $new_base_dir = $base_dir; $new_base_url = $base_url; $new_admin_email = $admin_email;
  $new_db_loc = $db_loc; $new_db_name = $db_name; $new_db_userid = $db_userid; 
  $new_db_pass = $db_pass; $new_amazonTag = $amazonTag; $new_amazonDevTag = $amazonDevTag;
}

if($new_db_loc != '' && $new_db_name != '' && $new_db_userid != '' && $new_db_pass != ''){

  // test db connection               
  $link = @(mysql_connect($new_db_loc, $new_db_userid, $new_db_pass));
  if(!$link){
    $error .= "ERROR: Could not connect with the DB server<br>Make sure connection information is correct<br>";
    $allowUpdate = false;
  }
}

if($allowUpdate == true &&
   $new_base_dir != '' && $new_base_url != '' &&
   $new_admin_email != '' && $new_db_loc != '' && $new_db_name != '' &&
   $new_db_userid != '' && $new_db_pass != ''){

  $fh = fopen($base_dir . "/config.php", 'w');
  if($fh){
    fwrite($fh, "<?php\r\n");
    fwrite($fh, "\$base_dir = '$new_base_dir';\r\n");
    fwrite($fh, "\$base_url = '$new_base_url';\r\n\r\n");

    fwrite($fh, "\$admin_email = '$new_admin_email';\r\n\r\n");

    fwrite($fh, "\$db_loc = '$new_db_loc';\r\n");
    fwrite($fh, "\$db_name = '$new_db_name';\r\n");
    fwrite($fh, "\$db_userid = '$new_db_userid';\r\n");
    fwrite($fh, "\$db_pass = '$new_db_pass';\r\n\r\n");

    fwrite($fh, "\$amazonTag = '$new_amazonTag';\r\n");
    fwrite($fh, "\$amazonDevTag = '$new_amazonDevTag';\r\n");
    fwrite($fh, "?>\r\n");
    fclose($fh);

    chmod($base_dir . "/config.php", 0770);
    header("Location: admin.php");
  }
  else{
    echo "Couldn't write config file!!!";
  }
}
else{
?>
<HTML>

<link rel=stylesheet href=style.css type=text/css>

<title>WishList Admin Page</title>
<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>

<td valign="top">

<?php
createNavBar("home.php:Home|admin.php:Admin Page|:Change WishList Settings");
?>

<center>

<?php
if($error){
  print "<h2>$error</h2>";
}
?>

<p>
<form method=post>
<input type=hidden name=setup value=true>
<input type=hidden name=updateValues value=true>

<table style="border-collapse: collapse;" border="1" bordercolor="#111111" cellpadding="2" cellspacing="0" bgcolor=lightYellow>
<tr><td colspan=2 align=center bgcolor="#6702cc"><font size=3 color=white><b>WishList Setup</b></font></td></tr>

<td align=right>* Base Dir</td>
<td bgcolor=white><input type=text name=base_dir value="<?php echo $new_base_dir != '' ? $new_base_dir : getcwd(); ?>" size=50></td>
</tr>

<tr>
<td align=right>* Base URL</td>
<td bgcolor=white><input type=text name=base_url value="<?php echo $new_base_dir != '' ? $new_base_url : "http://" . $_SERVER['HTTP_HOST']; ?>" size=50></td>
</tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr>
<td align=right>* Primary Admin Email</td>
<td><input type=text name=admin_email value="<?php echo $new_admin_email; ?>" size=50></td>
</tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr>
<td align=right>* Database Server</td>
<td><input type=text name=db_loc value="<?php echo $new_db_loc; ?>" size=50></td>
</tr>

<tr>
<td align=right>* Database Name</td>
<td><input type=text name=db_name value="<?php echo $new_db_name; ?>" size=50></td>
</tr>

<tr>
<td align=right>* Database Userid</td>
<td><input type=text name=db_userid value="<?php echo $new_db_userid; ?>" size=50></td>
</tr>

<tr>
<td align=right>* Database Password</td>
<td><input type=text name=db_pass value="<?php echo $new_db_pass; ?>" size=50></td>
</tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr>
<td align=right>Amazon Tag</td>
<td><input type=text name=amazonTag value="<?php echo $new_amazonTag; ?>" size=50></td>
</tr>

<tr>
<td align=right>Amazon devtag</td>
<td><input type=text name=amazonDevTag value="<?php echo $new_amazonDevTag; ?>" size=50></td>
</tr>

</table>
<p>
<input type=submit value=Submit>
</form>
* = required field
</body>
</html>
<?php
}
} // end of function