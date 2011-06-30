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

$message = "<h2>&nbsp;</h2>";

$action = $_REQUEST["action"];

if($action == "stopViewOther"){
  $removeuserid = $_REQUEST["userid"];

  $query = "delete from viewList where pid='" . $removeuserid . "' and viewer='" . $userid . "'";

  $result = mysql_query($query) or die("Could not query: " . mysql_error());
  $message = "<h2>Successfully removed yourself</h2>";
}
elseif ($action == "stopViewMine"){

  $removeuserid = $_REQUEST["userid"];

  $query = "delete from viewList where pid='" . $userid . "' and viewer='" . $removeuserid . "'";

  $result = mysql_query($query) or die("Could not query: " . mysql_error());
  $message = "<h2>The person you removed can no longer view your list</h2>";

}
elseif ($action == "startViewOther"){
  $adduserid = $_REQUEST["userid"];

  $query = "select pid from viewList where pid='" . $adduserid . "' and viewer='" . $userid . "'";
  $result = mysql_query($query) or die("Could not query: " . mysql_error());
  
  if(mysql_num_rows($result) > 0){
    $message = "<h2>You can already view that person's list</h2>";
  }
  else{
    // need to add new field
    $query = "insert into viewList (viewContactInfo, readOnly, pid, viewer) values (0, 1, '" . $adduserid . "', '" . $userid . "')";
    
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    $message = "<h2>Send this person an email if you want to view their contact information and to remove your read only status</h2>";
  }
}
elseif ($action == "startViewMine"){
  $adduserid = $_REQUEST["userid"];

  $query = "select viewer from viewList where viewer='" . $adduserid . "' and pid='" . $userid . "'";
  $result = mysql_query($query) or die("Could not query: " . mysql_error());
  
  if(mysql_num_rows($result) > 0){
    $message = "<h2>That person can already view your list</h2>";
  }
  else{
    // need to add new field
    $query = "insert into viewList (viewContactInfo, readOnly, pid, viewer) " .
      "values (0, 1, '" . $userid . "', '" . $adduserid . "')";
    
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    $message = "<h2>Person added</h2>";
  }
}
elseif ($action == "hideContactInfo"){
  
  if($_REQUEST["admin"] != ""){
    foreach($_REQUEST["admin"] as $admin){
      $t .= "viewer='" . $admin . "' ";
      $s .= "viewer!='" . $admin . "' ";
    }
    
    $t = str_replace (' ', " or ", trim($t));
    $s = str_replace (' ', " and ", trim($s));

    $query1 = "update viewList set viewContactInfo='1' where (" . $t . ") and pid='" . $userid . "'";

    $result = mysql_query($query1) or die("Could not query: " . mysql_error());
    $query2 = "update viewList set viewContactInfo='0' where (" . $s . ") and pid='" . $userid . "'";

    $result = mysql_query($query2) or die("Could not query: " . mysql_error());
  }
  else{
    $query = "update viewList set viewContactInfo='0' where pid='" . $userid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
  }

  $t = "";
  $s = "";

  if($_REQUEST["readOnly"] != ""){
    foreach($_REQUEST["readOnly"] as $rOnly){

      $t .= "viewer='" . $rOnly . "' ";
      $s .= "viewer!='" . $rOnly . "' ";
        
    }
    
    $t = str_replace (' ', " or ", trim($t));
    $s = str_replace (' ', " and ", trim($s));

    $query1 = "update viewList set readOnly='1' where (" . $t . ") and pid='" . $userid . "'";

    $result = mysql_query($query1) or die("Could not query: " . mysql_error());
    $query2 = "update viewList set readOnly='0' where (" . $s . ") and pid='" . $userid . "'";

    $result = mysql_query($query2) or die("Could not query: " . mysql_error());
  }
  else{
    $query = "update viewList set readOnly='0' where pid='" . $userid . "'";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
  }
  $message = "<h2>Privileges changed</h2>";
  
}
?>

<HTML>
<link rel=stylesheet href=../style.css type=text/css>

<title>Manage List Access</title>
<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>
<td valign="top" >

<?php
createNavBar("../home.php:Home|updateAccount.php:Update Account|:Manage List Access", false, "listAccess");
?>

<center>
<p>&nbsp;

<?php echo $message?>



<table border=0 width=90%>
<tr>
<td align=center width=50%>



<form action="manageListAccess.php" method=post>
<input type=hidden name=action value="hideContactInfo">




<table bgcolor=lightyellow style="border-collapse: collapse;" border="0" bordercolor="#111111" cellPadding="0" cellSpacing="0">
<tr>
<td colspan=3>&nbsp;</td>
</tr>
<tr>
<tr>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>


<!-- start of first table -->
<table style="border-collapse: collapse;" id="AutoNumber1" border="0" bordercolor="#111111" cellPadding="0" cellSpacing="0">
<tr>
<td colspan=6 align="center" bgcolor="#6702cc">
<font size=3 color=white><b>People who can view your list</b></font>
</td>
</tr>

<tr bgcolor=lightYellow>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td valign=bottom><b>Name</b></td>
<td align=center><b>Can View Your<br>Contact Info</b></td>
<td>&nbsp;&nbsp;&nbsp;</td>
<td align=center><b>Read Only<br>Access</b></td>
</tr>
<?php
$query = "select viewer, firstname, lastname, suffix, userid, viewContactInfo, readOnly from viewList, people where viewList.viewer = people.userid and viewList.pid = '" . $userid . "'" . " order by lastname, firstname";

$result = mysql_query($query) or die("Could not query: " . mysql_error());

while($row = mysql_fetch_assoc($result)){
  $list .= $row["userid"] . ",";
 
  print "<tr><td><a class=\"menuLink\" href=manageListAccess.php?action=stopViewMine&userid=" . $row["userid"] . ">[Remove]</a></td><td>&nbsp;</td><td>" . $row["firstname"] . " " . $row["lastname"] . ' ' . $row["suffix"] . "</td><td align=center><input name=admin[] value=" . $row["userid"] . " type='checkbox' ";
  print $row["viewContactInfo"] ? "checked" : "";
  print "></td><td>&nbsp;</td>";
  print "<td align=center><input name=readOnly[] type=checkbox value=" . $row["userid"];
  print $row["readOnly"] ? " checked" : "";
  print "></td></tr>";
}
print "<input type=hidden name=listUsers value=" . $list . ">";
?>
<tr><td colspan=6 align="center" bgcolor="#c0c0c0">
<input type=submit value="Make Changes"  style="font-weight:bold">
</td></tr></table>
</form>

<p>&nbsp;


<table style="border-collapse: collapse;" id="AutoNumber1" border="0" bordercolor="#111111" cellpadding="2" cellspacing="0">
<tr>
<td align="center" bgcolor="#6702cc">
<font size=3 color=white><b>Add a person so they can view your list</b></font>
</td>
</tr>
<tr>
<td>
Enter the person's userid or e-mail:<br>
<form method=post>
<input type=text size=20 name=email>
<input type=hidden name=action value=addUser>
<input type=submit value="Go">
</form>
<?php

if($_REQUEST["action"] == "addUser"){
  $email = $_REQUEST["email"];
  
  $pos = strpos($email, "@");

  // Note our use of ===.  Simply == would not work as expected
  // because the position of 'a' was the 0th (first) character.
  if ($pos === false) {
    $query = "select firstname, lastname, suffix, userid from people where userid='" . $email . "'";
  }
  else{
    $query = "select firstname,lastname, suffix, userid from people where email='" . $email . "'";
  }

  $result = mysql_query($query) or die("Could not query: " . mysql_error());
  if ($row = mysql_fetch_assoc($result)){
    print "<a class='menuLink' href=\"manageListAccess.php?action=startViewMine&userid=" . $row[userid] . "\">[Add]</a> " . $row["firstname"] . " " . $row["lastname"] . ' ' . $row["suffix"] . "<br>";
  }
  else{
    print "<font color=red>No user with that e-mail or userid</font>";
  }
    
}
?>
</td>
</tr>
</table>


<!-- end of first table -->
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr><td colspan=3>&nbsp;</td></tr>
</table>




</td><td valign=top align=center width=50%>


<!-- begin second table -->

<table bgcolor=lightyellow style="border-collapse: collapse;" border="0" bordercolor="#111111" cellPadding="0" cellSpacing="0">
<tr>
<td colspan=3>&nbsp;</td>
</tr>
<tr>
<tr>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>


<table border=0>
<tr>
<td align=center>


<table style="border-collapse: collapse;" id="AutoNumber1" border="0" bordercolor="#111111" cellpadding="2" cellspacing="0">
<tr>
<td colspan=2 align="center" bgcolor="#6702cc">
<font size=3 color=white><b>Lists you can view</b></font>
</td>
</tr>
<tr>
<td>

<?php 
$query = "select viewer, firstname, lastname, suffix, userid from viewList, people where viewList.viewer = '" . $userid . "' and viewList.pid = people.userid order by lastname, firstname";

//$query = "select * from people where userid='" . $userid . "'";

$result = mysql_query($query) or die("Could not query: " . mysql_error());

while($row = mysql_fetch_assoc($result)){
  print "<tr><td><a class=\"menuLink\" href=manageListAccess.php?action=stopViewOther&userid=" . $row["userid"] . ">[Remove]</a></td><td>" . $row["firstname"] . " " . $row["lastname"] . ' ' . $row["suffix"] . "</td></tr>";
}
?>
</table>


<p>&nbsp;


<table style="border-collapse: collapse;" id="AutoNumber1" border="0" bordercolor="#111111" cellpadding="2" cellspacing="0">
<tr>
<td align="center" bgcolor="#6702cc">
<font size=3 color=white><b>Find someone elses list to view</b></font>
</td>
</tr>
<tr>
<td>
Enter the person's userid or e-mail:<br>
<form method=post>
<input type=text size=20 name=email>
<input type=hidden name=action value=findUser>
<input type=submit value="Go">
</form>
<?php

if($_REQUEST["action"] == "findUser"){
  $email = $_REQUEST["email"];

  $pos = strpos($email, "@");

  // Note our use of ===.  Simply == would not work as expected
  // because the position of 'a' was the 0th (first) character.
  if ($pos === false) {
    $query = "select firstname, lastname, suffix, userid from people where userid='" . $email . "'";
  }
  else{
    $query = "select firstname,lastname, suffix, userid from people where email='" . $email . "'";
  }

  $result = mysql_query($query) or die("Could not query: " . mysql_error());

  if ($row = mysql_fetch_assoc($result)){
    print "<a href=\"manageListAccess.php?action=startViewOther&userid=" . $row[userid] . "\">[Add]</a> " . $row["firstname"] . " " . $row["lastname"] . ' ' . $row["suffix"] . "<br>";
  }
  else{
    print "<font color=red>No user with that e-mail or userid</font>";
  }
    
}
?>
</td>
</tr>
</table>



</td>
</tr>
</table>

</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr><td colspan=3>&nbsp;</td></tr>
</table>

<!-- end second table -->

</td>
</tr>
</table>
</body>
</html>
