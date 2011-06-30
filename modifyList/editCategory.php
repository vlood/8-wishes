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

<BODY>
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>
<td valign="top" >

<?php
$cid = $_REQUEST["cid"];
$cname = $_REQUEST["cname"];
if (get_magic_quotes_gpc()==1) {
   $cname = stripslashes($cname);
}
?>

<?php
createNavBar("../home.php:Home|modifyList.php:Modify WishList|:Edit Category");
?>

<center>
<p>&nbsp;
<p>&nbsp;


<table style="border-collapse: collapse;" id="AutoNumber1" border="1" bordercolor="#11111" cellpadding="2" cellspacing="0" bgcolor="lightyellow">
<tr><td colspan="2" align="center" bgcolor="#6702cc"> <b><font size=3 color="#ffffff">
<b>Modify Category Heading</b>
</font></td></tr>
<?php

  $query = "select * from categories where cid=" . $cid;
  $rs = mysql_query($query) or die("Could not query: " . mysql_error());

if($row = mysql_fetch_assoc($rs)){
?>
<tr><td colspan="2" align=center>
<br>
<b><?php echo $row["name"] ?></b> 
<?php echo $row["description"] ?> 
<a target="_blank" href="<?php echo $row["linkurl"] ?>"><?php echo $row["linkname"]?></a><p>
<br>
</td></tr>

<form method="post" action="validate_editCategory.php">
<input type="hidden" name="cid" value="<?php echo $cid ?>"> 

<tr><td align="right">
<b>Category Name</b>
</td><td bgcolor="#eeeeee">
<input type="text" size="50" name="name" value="<?php echo $row["name"] ?>">
</td></tr>
<tr><td align="right">  
<b>Category Description</b>
</td><td bgcolor="#eeeeee">
<input type="text" size="50" name="description" value="<?php echo $row["description"] ?>">
</td></tr>
<tr><td align="right">
<table><tr><td align="right">
<b>Link Name</b>
</tr><tr><td  align="right">
<b>URL</b>
</td></tr></table>
</td><td bgcolor="#eeeeee">
<input type="text" size="50" name="linkname" value="<?php echo $row["linkname"] ?>">
<br>
<input type="text" size="100" name="linkurl" value="<?php echo $row["linkurl"] ?>">
</td></tr>

<tr><td align="right">
<b>Additional Information</b>
</td>
<td bgcolor="#eeeeee">
<textarea name="catSubDescription" cols="75" rows="5"><?php echo str_replace("<br>", "\r\n", $row["catSubDescription"]) ?></textarea>
</td></tr>

<tr><td colspan="2" bgcolor="#c0c0c0">
    <table width="100%">
    <tr><td align=center valign="center">
    <input type="submit" value="Update Category" style="font-weight:bold">
    </form>
  </td>

  <td align=center valign="center">
    <FORM METHOD="POST" ACTION="deleteCategory.php">
    <input type="hidden" name="cid" value="<?php echo $_REQUEST["cid"] ?>">
    <input type="hidden" name="cname" value="<?php echo $cname ?>">
    <input type="hidden" name="cso" value="<?php echo $_REQUEST["cso"] ?>">
    <input type="hidden" name="referrer" value="editCategory.php">
    <input type="submit" value="Delete Category" style="font-weight:bold; color:red">
    </form>
  </td></tr>
  </table>

</td></tr>
</table>

<?php
}
?>

</td></tr></table>
</body>
</html>
