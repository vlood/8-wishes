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
$query = "select * from people, viewList where pid = people.userid and viewer='" . $userid . "' order by lastname, firstname";

$result = mysql_query($query) or die("Could not query: " . mysql_error());

$num_rows = mysql_num_rows($result);

?>
<HTML>

<link rel=stylesheet href=style.css type=text/css>

<script language="JavaScript">
<!-- Begin JavaScript

<?php 

   while($row = mysql_fetch_assoc($result)){
     $allowView = $row["viewContactInfo"];
     $new_userid = $row["userid"];
     $name = $row["firstname"] . ' ' . $row["lastname"] . ' ' .$row["suffix"];
     $street = $allowView ? $row['street'] : "Info Withheld";
     $city = $allowView ? ($row['city'] != "" ? "" . $row['city'] : "") : "";
     $state = $allowView ? ($row['state'] != "" ? ", " . $row['state'] : "") : "";
     $zip = $allowView ? ($row['zip'] != "" ? "  " . $row['zip'] : "") : "";
     $phone = $allowView ? ($row['phone'] != "" ? "H " . $row['phone'] . "\\r\\n" : "\\r\\n") : "\\r\\n";
     $mobilePhone = $allowView ? ($row['mobilephone'] != "" ? "M " . $row['mobilephone'] . "\\r\\n" : "\\r\\n") : "\\r\\n";
     $email = $row["email"];
     $url = $allowView ? ($row['url'] == "" ? "null" : $row['url']) : "null";
?>
        <?php echo $new_userid ?>_contact = "<?php echo $name ?>\r\n<?php echo $street ?>\r\n<?php echo $city ?><?php echo $state ?><?php echo $zip ?>\r\n\r\n<?php echo $phone ?><?php echo $mobilePhone ?>email: <?php echo $email ?>\r\n\r\nBirthday: <?php echo  $row['bmonth'] ?> <?php echo  $row['bday'] ?>";
        <?php echo  $new_userid ?>_email = "<?php echo  $email ?>";
        <?php echo  $new_userid ?>_homepage = "<?php echo $url ?>";

<?php
}
?>
        var theObject;
        navName = navigator.appName;
        navVer = parseInt(navigator.appVersion);

        function show_record(obj, txtArea, PersonName) {
          if (theObject != obj){
              cnt = eval(PersonName + "_contact");
              eml = PersonName; //eval(PersonName + "_email");
              hmp = eval(PersonName + "_homepage");

              if(hmp == "null"){
                 document.links[<?php echo  $num_rows + 3 ?>].style.color = "white";
                 hmp = "javascript:alert('This person does not have a homepage')";
              }
              else{
                 document.links[<?php echo  $num_rows + 3 ?>].style.color = "blue";
              }

              txtArea.value = cnt;
              document.links[<?php echo  $num_rows + 2 ?>].href= "javascript:void(open(\"sendEmail.php?action=emailPerson&recip=" + eml + "\",'WishList_com','height=425,width=850,left=10,top=10,location=1,scrollbars=yes,menubar=1,toolbars=1,resizable=yes'))";
              document.links[<?php echo  $num_rows + 3 ?>].href= hmp;

              if(!(navName == "Netscape" && navVer <= 4)){
                txtArea.style.fontWeight="normal";
                obj.style.color="red";
                if (theObject != null){
                  theObject.style.color="blue";
                }
              }  
              theObject = obj;
          }
       }
// End JavaScript-->
</script>

<title>WishList Home Page</title>
<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>

<td valign="top">

<?php
createNavBar(":Home", true);
?>

<center>
<p>&nbsp;

<?php 
  $season = getSeason();

  // determine which graphic to display 
  if($season == 5){
    echo "<img src=\"images/greet04.gif\" lowsrc=\"images/space.GIF\" width=\"431\" height=\"83\">\n";
  } else {
    echo "<img src=\"images/welcome_logo.gif\" lowsrc=\"images/space.GIF\" width=\"302\" height=\"48\">\n";
    echo "<br><img src=\"images/logo.gif\" lowsrc=\"images/space.GIF\" width=\"419\" height=\"81\">\n";
  }
?>




<!--
              <table class="yellowBorder" cellspacing="0" cellpadding="5" width="60%" nosave border="0" >
                <tr>
                  <td class="lightYellow" align="center" height="60">
                    <font size=5 face=arial><b>Welcome to the Wish List Site</b></font>
                  </td>
                </tr>

              </table>
-->

<h2>Click on a name to view their list</h2>

<form name="theForm">
<table border=0>
<tr><td valign="center">
<?php

if ($num_rows > 0)
     mysql_data_seek($result, 0);

$dumpy = "";

while($row = mysql_fetch_assoc($result)){

  $new_userid = $row["userid"];
  $name =  $row['firstname'] . ' ' . $row['lastname'] . ' ' . $row['suffix'];

  $dumpy .= "<a href=\"viewList/viewList.php?recip=" . $new_userid . 
      "&name=" . $name . "\" onmouseover=\"show_record(this, theForm.contact, '" . $new_userid . "')\">" . $name . "</a><br>";

//  $dumpy .= "<a href=\"viewList/vl_" . $new_userid . 
//      "_" . $name . "\" onmouseover=\"show_record(this, theForm.contact, '" . $new_userid . "')\">" . $name . "</a><br>";
}

if($num_rows > 0){
  $message = "Contact Information\r\n\r\nClick on a name to view their Wish List\r\n\r\nChoose E-Mail to send them an email or Home-Page to go to their homepage if they have one";
}
else{
  $message = "You cannot view anybody's list\r\n\r\nGoto Update Your Account > Manage List Access to add people";
}
$dumpy .= "</td><td>";

if(strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko')){
  $dumpy .= "<textarea  COLS='32' ROWS='8' style='font-family: Courier; font-size:small; background-color:transparent; border:0' WRAP='soft' NAME='contact' readonly='true'>" .$message . "</textarea>";
    }
  else{
    $dumpy .= "<textarea COLS='32' ROWS='9' style='background-color:transparent; overflow:hidden; border:0; scrolling:none; color:black; font-family: Courier; font-size:small;'  WRAP='physical' noscroll NAME='contact' readonly>" . $message ."</textarea>";
  }
print $dumpy;

/* </td><td> */
/*                     <script LANGUAGE="javascript"> */
/*                       <!-- Begin JavaScript */
/*                       if(navName == "Netscape"){ */
/*                         if(navVer <="4"){ */
/*                           document.write("<textarea ROWS='8' COLS='32' NAME='contact' wrap='soft' readonly='true'><?php echo $message ?></textarea>"); */
/*                         } */
/*                         else{  */
/*                           document.write("<textarea style='font-family: Courier; font-size:small; background-color:transparent; border:0' ROWS='8' COLS='32' WRAP='soft' NAME='contact' readonly='true'><?php echo $message ?></textarea>"); */
/*                         } */
/*                       } */
/*                       else{  */
/*                         // microsoft */
/*                         document.write("<textarea style='background-color:transparent; overflow:hidden; border:0; scrolling:none; color:black; font-family: Courier; font-size:small;' ROWS='9' COLS='32' WRAP='physical' noscroll NAME='contact' readonly><?php echo $message ?></textarea>"); */
/*                       } */
/*                       // End Javascript--> */
/*                     </script> */
?>
</td></tr>
<tr><td>&nbsp;</td><td>
                    <table border=0 width=100%>
                      <tr>
                        <td>
                          <a class="menuLink" name="email" href="javascript:alert('Please select a name first')" >E-mail</a>

                        </td>
                        <td align=right>
                          <a class="menuLink" name="url" href="javascript:alert('Please select a name first')" >Home-Page</a>
                        </td>
                      </tr>
                    </table>

</td></tr>
</table>
</form>
<p>

<hr>

<table>
<tr>
<td onclick="location.href='modifyList/modifyList.php'" height=27pt width=200pt align=center style="background-image: url(images/button.gif); background-repeat: no-repeat; background-position:center center;" >
<a href="modifyList/modifyList.php">Modify Your List</a>
</td></tr>
<tr>
<td onclick="location.href='updateAccount/updateAccount.php'" height=27pt width=200pt align=center style="background-image: url(images/button.gif); background-repeat: no-repeat; background-position:center center;" >
<a href="updateAccount/updateAccount.php">Update Your Account</a>
</td></tr>
<?php
if($_SESSION["admin"] == 1){
?>
<tr>
<td onclick="location.href='admin.php'" height=27pt width=200pt align=center style="background-image: url(images/button.gif); background-repeat: no-repeat; background-position:center center;" >
<a href="admin.php">Admin Page</a>
</td></tr>
<?php
   }
?>

</table>
</center>
<?php

//$season = getSeason();

if($season == 1){
  $image = "winter.jpg";
}
elseif($season == 2){
  $image = "spring.jpg";
}
elseif($season == 3){
  $image = "summer.jpg";
}
elseif($season == 4){
  $image = "autumn.jpg";
}
else{
  $image = "waving_santa2.gif";
}
?>
    <span style="position: absolute; left: 106; top: 52">
    <img border="0" src="images/<?php echo $image ?>" lowsrc="images/space.GIF">
    </span>
</td></tr></table>
</body>
</html>
