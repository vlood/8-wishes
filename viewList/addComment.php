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

if($confirm != ""){

$recip = convertString($_REQUEST["recip"]);
$comment_userid = convertString($userid);
$comment = convertString($_REQUEST["comment"]);
$name = convertString($_REQUEST["name"]);

$query = "insert into comments (commentId, userid, comment_userid, comment, date) values (null, '" . $recip . "', '" . $comment_userid . "', '" . $comment . "', NOW())";

$rs = mysql_query($query) or die("Could not query: " . mysql_error());

  header("Location: " . getFullPath("viewList.php") . "?recip=" . $recip . "&name=" . $name);

if($_REQUEST["email"] != ""){

  $ar = array();                   
  $to = "";

  foreach($_REQUEST['email'] as $email){
     $to = $email . ", " . $to;
  }                         

  $query = "select email from people  where userid='" . $userid . "'";

  $rs = mysql_query($query) or die("Could not query: " . mysql_error());
 
  if($row = mysql_fetch_assoc($rs)){

     print $to;
      $from = $_SESSION["fullname"] . "<" . $row["email"] . ">";

      $subject = $_SESSION["fullname"] . " has added a comment to " . $_REQUEST["name"] . "'s list";
      $message = "<b>Comment:</b><br>" . $cleanString($_REQUEST["comment"]);

      sendEmail($to, $from, $subject, $message, 0);
   }

}
}
else{
?>
<HTML>

<link rel=stylesheet href=../style.css type=text/css>

<title><?php echo $name ?>'s WishList</title>
<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>
<td valign="top">

<?php
createNavBar("../index.php:Home|viewList.php?recip=" . $_REQUEST["recip"] . "&name=" . $_REQUEST["name"] . ":View List - " . $_REQUEST["name"] . "|:Add Comment", false, "addComment");
?>

<table><tr><td>
<form name="theForm" method=post action=addComment.php>
<p>
<b>Comment</b><br> <textarea ROWS='5' COLS='90' name=comment 
onFocus="javascript:if(this.value=='Type comment here') this.value='';">Type comment here</textarea>
</td></tr>
<tr><td align=center>
<table style="border: 1px solid lightBlue" >
<tr><td colspan="2" align="center" bgcolor="#6702cc"> <b><font size=3 color="#ffffff">
Send Email?</font></b>
</td>
</tr>
<tr>
<td align=center>

<b>Your comment will be sent in an email to each person who has a check next to their name</b>
<br>You may want to include your own name to verify the emails are sent
</td></tr>
<tr><td align=center>
<table><tr><td align=center>
<table><tr><td>
<?php

$query = "select * from people, viewList where pid = '" . $_REQUEST["recip"] . "' and viewer!='" . $_REQUEST["recip"] . "' and viewer = people.userid order by lastname, firstname";

$rs = mysql_query($query) or die("Could not query: " . mysql_error());

while($row = mysql_fetch_assoc($rs)){
?>

<input type=checkbox name=email[] value="<?php echo $row["email"] ?>"><?php echo $row["firstname"] . ' ' . $row["lastname"] . ' ' . $row["suffix"] ?><br>
<?php
}
?>
</td></tr></table>
</td></tr>
<tr><td>

<SCRIPT LANGUAGE="JavaScript">
<!--    

function checkAll(field)
{
for (i = 0; i < this.document.theForm.elements['email[]'].length; i++)
    this.document.theForm.elements['email[]'][i].checked = true;
}

function uncheckAll(field)
{
for (i = 0; i < this.document.theForm.elements['email[]'].length; i++)
    this.document.theForm.elements['email[]'][i].checked = false;
}

//  End -->
</script>

</td></tr>
<tr><td colspan="2" bgcolor="#c0c0c0" align=center>
<input type=button value="Check All" onClick="checkAll(document.theForm.email)"; style="font-weight:bold">
<input type=button value="Uncheck All" onClick="uncheckAll(document.theForm.email)"; style="font-weight:bold">

</td></tr></table>

</td></tr></table>

<br>
<input type=hidden name=confirm value=yes>
<input type=hidden name=recip value=<?php echo $_REQUEST["recip"] ?>>
<input type=hidden name=name value="<?php echo $_REQUEST["name"] ?>">
<input type=submit value="Add Comment" style="font-weight:bold">

</form>
</td><tr><table>
<?php
}
?>
