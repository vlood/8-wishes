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

include "funcLib.php";

$subject = "";
$message = "";

$query = "select email from people where userid='" . $userid . "'";

$result = mysql_query($query) or die("Could not query: " . mysql_error());

if ($row = mysql_fetch_assoc($result)){
  // get the email address of the user
  $from = $row["email"];
}

$send = $_REQUEST['send'];

if($send == "Send"){
  $ar = array();                   
  $to = "";
  $from = $_SESSION["fullname"] . '<' . $from . '>';

  foreach($_REQUEST['email'] as $email){
    if(strcmp($to,"") != 0){
      $to = $email . ", " . $to;
    }
    else{
      $to = $email;
    }
  }                         

  $subject = $_REQUEST['subject'];
  // the server I am hosting this on automatically escapes single and
  // double quotes.  Since this may not be the case on all servers, I
  // explictily remove the escaping
  $subject = str_replace("\\&quot;", "&quot;", 
                         str_replace("\\'", "'", 
                                     str_replace("\\\"", "\"", $subject)));

  if(strcmp($subject, " (please fill out)") == 0){
    $subject = "A message from " . $_SESSION['fullname'];
  }

  $message = convertString_no_escape($_REQUEST['message']);

  // the server I am hosting this on automatically escapes single and
  // double quotes.  Since this may not be the case on all servers, I
  // explictily remove the escaping
  $message = str_replace("\\&quot;", "&quot;",
                         str_replace("\\'", "'", 
                                     str_replace("\\\"", "\"", $message)));

  if(sendEmail($to, $from, $subject, $message, 0) == 0){
    print "<html><body><center><h2>An error occured while sending your email <br>" . $to . "</h2><p><a href=\"javascript:window.close();\">Close Window</a></body></html>";
  }
  else{
    print "<html><body><center><h2>Message Sent Successfully to <br>". $to . "</h2><p><a href=\"javascript:window.close();\">Close Window</a></body></html>";
  }
  
  return;
}

$action = $_REQUEST["action"];

if($action != ""){

  $recipArray = array();
  
  if($action == "adminNewUser"){
    array_push($recipArray, $_REQUEST["newUserEmail"]);
  }
  else if($action == "addedUser"){

    $recip = $_REQUEST["recip"];

    $query = "select firstname, lastname, suffix, email from people where userid='" . $recip . "' order by lastname, firstname";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());

    if ($row = mysql_fetch_assoc($result)){
      $name = $row["firstname"] . ' ' . $row["lastname"] . ' ' . 
        $row["suffix"] . " <" . $row["email"] . ">";
      array_push($recipArray, $name);    
    }

    $subject = $_REQUEST["subject"];
    $message = str_replace("<br>", "\r\n", $_REQUEST["message"]);

  }
  elseif($action == "emailPerson"){
    $recip = $_REQUEST["recip"];

    $query = "select firstname, lastname, suffix, email from people where userid='" . $recip . "' order by lastname, firstname";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    if ($row = mysql_fetch_assoc($result)){
      $name = $row["firstname"] . ' ' . $row["lastname"] . ' ' . 
        $row["suffix"] . " <" . $row["email"] . ">";
      array_push($recipArray, $name);    
    }
  }
  elseif($action == "emailRecipViewers"){
    $recip = $_REQUEST["recip"];
    
    $query = "select firstname, lastname, suffix, email from people,viewList where userid=viewer and pid='" . $recip . "' and viewer !='" . $recip . "' order by lastname, firstname";
    $result = mysql_query($query) or die("Could not query: " . mysql_error());
    
    while ($row = mysql_fetch_assoc($result)){
      $name = $row["firstname"] . ' ' . $row["lastname"] . ' ' . $row["suffix"] . " <" . $row["email"] . ">";
      array_push($recipArray, $name);
    }
  }
}
  
?>

<html>
<link rel=stylesheet href=style.css type=text/css>
<body>
<center>
<form action="sendEmail.php" method="POST">

<table style="border-collapse: collapse;" id="AutoNumber1" border="1" bordercolor="#111111" cellpadding="2" cellspacing="0" bgcolor=lightYellow>
<tr><td colspan="2" align="center" bgcolor="#6702cc">
<font size=3 color=white><b>Send Email</b></font>
</td></tr>
<tr><td bgcolor=white colspan=2 align=center>
<br><b>
An email will be sent to each person who has a check next to their name
</b>
<br>&nbsp;
</td></tr>
<tr>
<td valign=top align=right>
<font size="3" face="Arial"><strong>To :</font></strong></td>
<td bgcolor="#eeeeee">
<?php
foreach ($recipArray as $recip){
  $p = str_replace(">", "&gt;", (str_replace("<", "&lt;", $recip)));
  print "<input type=checkbox name=email[] checked value=\"" . 
    $recip . "\">" . $p . "<br>";
}
?>
</tr><tr>
<td align=right><font size="3" face="Arial"><strong>CC :</font></strong></td>
<td  bgcolor="#eeeeee">
<?php
  print "<input type=checkbox name=email[] checked value=\"" . 
    $_SESSION["fullname"] . '<' . $from . '>' . "\">" . $_SESSION["fullname"] . "&lt;" . $from . "&gt;";
?>
</td>
</tr><tr>
<td align=right><font size="3" face="Arial"><strong>From :</font></strong></td>
<td bgcolor="#eeeeee"><input name=from type=text size=80 value="<?php echo $_SESSION["fullname"] . "&lt;" . $from . "&gt;" ?>" disabled></input></td>
</tr>
<tr>
<td align=right><font size="3" face="Arial"><strong>Subject :</strong></font></td>
<td bgcolor="#eeeeee"><strong><input type="text" size="80" maxlength="100" name="subject" value=<?php echo $subject != "" ? "\"". $subject . "\"" : "\" (please fill out)\"" ?> onfocus="javascript:if(this.value==' (please fill out)') this.value=''"></strong></td>
</tr>
<tr>
<td valign=top align=right><font size="3" face="Arial"><strong>Body :</strong></font></td>
<td>
<textarea name="message" rows="10" cols="80" wrap="PHYSICAL"><?php echo $message ?></textarea>
</td></tr>
<tr>
<td colspan=2 bgcolor="#c0c0c0" align=center>
<input type="submit" name=send value=Send style="font-weight:bold"> <input type="submit" value=Cancel style="font-weight:bold" onclick="javascript:window.close()"></td>
</tr>
</table>

</form>
</center>
</body>
</html>
