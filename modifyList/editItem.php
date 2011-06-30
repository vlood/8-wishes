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

$iid = $_REQUEST["iid"];
$iso = $_REQUEST["iso"];
$cid =  $_REQUEST["cid"];
$cname = $_REQUEST["cname"];
if (get_magic_quotes_gpc()==1) {
   $cname = stripslashes($cname);
}
?>
<HTML>

<link rel=stylesheet href=../style.css type=text/css>

<title>Edit Item</title>
<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>
<td valign="top" >

<?php
createNavBar("../home.php:Home|modifyList.php:Modify WishList|:Edit Item", false, "deleteItem");
?>

<p>&nbsp;
<center>
<form method="post" action="validate_editItem.php" style="page-break-after:">
<input type="hidden" name="iid" value="<?php echo $iid; ?>">
<input type="hidden" name="iso" value="<?php echo $iso; ?>">
<input type="hidden" name="cid" value="<?php echo $cid; ?>">
<input type="hidden" name="cname" value="<?php echo $cname; ?>">

<table style="border-collapse: collapse;" id="AutoNumber1" border="1" bordercolor="#11111" cellpadding="2" cellspacing="0" bgcolor="lightyellow">
<tr><td colspan="2" align="center" bgcolor="#6702cc"> <b><font size=3 color="#ffffff">
Edit Item from <?php echo $cname; ?> Category
</td></tr>

  <tr><td align="right">
    <b>Move to Category</b>
  </td><td bgcolor="#eeeeee">
<?php

  $query = "Select * from categories where catSortOrder!=-1000 and userid='" . $_SESSION["userid"] . "' order by name"; 
  $rs = mysql_query($query) or die("Could not query: " . mysql_error());

  print "<select name=movecid>";

  while($row = mysql_fetch_assoc($rs)){
    if($row["cid"] == $cid)
      $val = "selected";
    else
      $val = "";

    print "<option value=" . $row["cid"] . " " . $val . ">" . $row["name"] . "</option>";
  }
  print "</select>";

 print "</td></tr>";

   $query = "select title, items.description, price, quantity, link1, link1url, subdesc, link2, link2url, link3, link3url, allowCheck, addStar from items, categories where items.cid=categories.cid and categories.userid='" . $userid . "' and items.iid = " . $iid;

  $rs = mysql_query($query) or die("Could not query: " . mysql_error());

while($row = mysql_fetch_assoc($rs)){
?>
  <tr><td align="right">
    <b>Title</b>
  </td><td bgcolor="#eeeeee">
    <input type="text" size="100" name="title" value="<?php echo $row["title"] ?>">
  </td></tr>
  <tr><td align="right">
    <b>Description</b>
  </td><td bgcolor="#eeeeee">
    <input type="text" size="100" name="desc" value="<?php echo $row["description"] ?>">
  </td></tr>
  <tr><td align="right">
    <b>Price</b>
  </td><td bgcolor="#eeeeee">
    <input type="text" size="7" name="price" value="<?php echo $row["price"] ?>">
  </td></tr>
  <tr><td align="right">
    <b>Quantity</b>
  </td><td bgcolor="#eeeeee">
    <input type="text" size="7" name="quantity" value="<?php echo $row["quantity"] ?>">
  </td></tr>
  <tr><td align="right">
    <table>
      <tr>
        <td align=right>
          <b>Link 1 Name</b>
        </td>
      </tr>
      <tr>
        <td align=right>
          <b>URL</b>
        </td>
      </tr>
    </table>
  </td><td bgcolor="#eeeeee">
    <input type="text" size="50" name="link1" value="<?php echo $row["link1"] ?>">
    <br>
    <input type="text" size="100" name="link1url" value="<?php echo $row["link1url"] ?>">
  </td></tr>


  <tr><td align="right">
    <b>Additional<br>Information</b>
  </td><td bgcolor="#eeeeee">
    <textarea ROWS='3' COLS='80' name="subdesc"><?php echo str_replace("<br>", "\r\n", $row["subdesc"]) ?></textarea>
  </td></tr>
  <tr><td align="right">
    <table>
      <tr>
        <td align=right>
          <b>Link 2 Name</b>
        </td>
      </tr>
      <tr>
        <td align=right>
          <b>URL</b>
        </td>
      </tr>
    </table>
  </td><td bgcolor="#eeeeee">
    <input type="text" size="50" name="link2" value="<?php echo $row["link2"] ?>">
    <br>
    <input type="text" size="100" name="link2url" value="<?php echo $row["link2url"] ?>">
  </td></tr>


  <tr><td align="right">
    <table>
      <tr>
        <td align=right>
          <b>Link 3 Name</b>
        </td>
      </tr>
      <tr>
        <td align=right>
          <b>URL</b>
        </td>
      </tr>
    </table>
  </td><td bgcolor="#eeeeee">
    <input type="text" size="50" name="link3" value="<?php echo $row["link3"] ?>">
    <br>
    <input type="text" size="100" name="link3url" value="<?php echo $row["link3url"] ?>">
  </td></tr>

  <tr><td align="center" colspan="2">
    <b>Allow people to check this item off</b>
    <input type="checkbox" name="allowCheck"
<?php  
if($row["allowCheck"] == "true")
  print "checked=true";
?>>
  </td></tr>

  <tr><td align="center" colspan="2">
    <b>Add a star to this item</b>
    <input type="checkbox" name="addStar"
<?php  
if($row["addStar"] == "1")
  print "checked=true";
?>>
  </td></tr>

<?php
  }
?>
<tr><td colspan="2" bgcolor="#c0c0c0">

<table width="100%">
<tr><td align=center valign="center">
<input type="submit" value="Update Item" style="font-weight:bold">
</form>
</td>

<td align=center valign="center">
<form method="post" action="deleteItem.php" style="page-break-after : ">
<input type="hidden" name="iso" value="<?php echo $iso; ?>">
<input type="hidden" name="iid" value="<?php echo $iid; ?>">
<input type="hidden" name="cid" value="<?php echo $cid; ?>">
<input type="hidden" name="cname" value="<?php echo $cname; ?>">
<input type="hidden" name="referrer" value=editItem.php>
<input type="submit" value="Delete Item" style="font-weight:bold; color:red">
</form>

</td></tr></table>
</td></tr>
</table>

</tr></td></table>
</body>
</html>
