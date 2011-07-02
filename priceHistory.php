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

  $iid = $_REQUEST["iid"];

  $query = "SELECT title,price FROM items WHERE iid=$iid";
  $rs2 = mysql_query($query) or die("Could not query: " . mysql_error());

  $row2 = mysql_fetch_assoc($rs2);


  $result = "<table style=\"border-collapse: collapse;\" border=\"1\" bordercolor=\"#111111\" cellpadding=\"4\" cellspacing=\"0\" bgcolor=lightYellow>";
  $result .= "<tr><td colspan=2 align=center bgcolor=\"#6702cc\">";
  $result .= "<font size=3 color=white>";
  $result .= "<b>Amazon price history for<br><i>" . $row2["title"] . "</i></b></font></td></tr>";

  if($iid){
    $query = "SELECT * FROM itemPriceHistory WHERE iid=$iid ORDER BY dateChanged";
    $rs2 = mysql_query($query) or die("Could not query: " . mysql_error());

    $i = 1;
    while($row3 = mysql_fetch_assoc($rs2)){

      if($i % 2 == 0){
        $bgcolor="lightYellow";
      }
      else{
        $bgcolor="white";
      }
      $result .= "<tr><td bgcolor=\"$bgcolor\">" . parseDate($row3["dateChanged"]) . "</td><td bgcolor=\"$bgcolor\">$" . $row3["price"] . "</td></tr>";
      $i++;
    }
  }

  if($bgcolor == "lightYellow"){
    $bgcolor="white";
  }
  else{
    $bgcolor="lightYellow";
  }
    
  $result .= "<tr><td bgcolor=\"$bgcolor\">Current price</td><td bgcolor=$bgcolor>$" . $row2["price"] . "</td></tr>";
  $result .= "</table>";


if($_REQUEST["js"]){

  print $result;
  return;
}
?>

<HTML>

<link rel=stylesheet href=style.css type=text/css>

<title>WishList Home Page</title>
<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>

<td valign="top">

<?php
createNavBar("../index.php:Home|:Price History");
?>

<center>

<h3>On this page you can see the historical prices Amazon has charged for an item since it was added to a WishList</h3>

<?php 
  print $result; 
?>

</center>
</td>
</tr>
</table>
</body>
</html>
