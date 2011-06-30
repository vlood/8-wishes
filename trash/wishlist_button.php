<?php

@(include 'config.php');

?>
<html>
<link rel=stylesheet href=style.css type=text/css>
<body>

<div>
<table
<tr>
<td align="left">
<table border="0" cellpadding="0" cellspacing="0">

<tr>

<td valign="top" width="350"><img border="0" src="./images/spacer_5.gif" width="10" height="21"><b><font face="Verdana, Helvetica, sans-serif" color="#990099"><a href="login.php"><img border="0" src="./images/return_purple.gif" width="90" height="24"><br>
<br>
</a>A D
D&nbsp;&nbsp; T O&nbsp;&nbsp; Y O U R&nbsp;&nbsp; W I S H L I S T<br>
&nbsp;<br>
<font size="3">from anywhere on the Web!</font></font></b><br>

<img border="0" src="./images/spacer_5.gif" width="25" height="21">
<div align="left">
<table border="0" cellpadding="0" cellspacing="0" width="100%">

<tr>
<td><font face="Arial" size="2">To add items to your WishList you'll need to put a special,
&amp; completely safe <b>WishList Site</b>
bookmark in your browser's bookmarks list.</font></td>

</tr>

<tr>
<td><img border="0" src="./images/spacer_5.gif" width="25" height="11"></td>
</tr>

</table>
</div>
<div align="left">
<table border="0" cellpadding="0" cellspacing="0" width="100%">

<tr>
<td><font face="Arial" size="2">For those using Microsoft, Firefox, or Mozilla Browsers, drag this &quot;Add to
WishList Button&quot; up to your browser's <a href="#toolbar"> TOOLBAR</a>.  The <b>
WishList Site</b> bookmark conveniently stays on your toolbar.</font></td>

<td ><img border="0" src="./images/spacer_5.gif" width="7" height="21"></td>
<td valign="middle" bgcolor=lightblue> 

<a href="javascript:void(open('<?php echo $base_url; ?>/modifyList/addItemFromWeb.php?wldesc='+escape(document.title)+'&wlurl='+escape(location.href),'WishList_com','height=600,width=900,left=10,top=10,location=1,scrollbars=yes,menubar=1,toolbars=1,resizable=yes'));">Add to Wishlist Button</a></td>

</tr>

</table>
</div>


<div align="left">
<table border="0" cellpadding="0" cellspacing="0">

<tr>
<td valign="top" width="350" colspan="3"><img border="0" src="./images/spacer_5.gif" width="25" height="11">
</td>
</tr>

<tr>
<td valign="top" width="350"><font face="Arial" size="2">Those using other browsers may <i>Right Click</i> on this link:<b> 
<a href="javascript:void(open('<?php echo $base_url; ?>/modifyList/addItemFromWeb.php?wldesc='+escape(document.title)+'&wlurl='+escape(location.href),'WishList_com','height=600,width=900,left=10,top=10,location=1,scrollbars=yes,menubar=1,toolbars=1,resizable=yes'));">Add to Wishlist Button</a></td>


</b>and
select &quot;Add to Favorites&quot; or &quot;Add to Bookmark&quot;</font>

</td>
<td valign="top"><img border="0" src="./images/spacer_5.gif" width="19" height="14"></td>
<td valign="top">
</td>
</tr>

</table>
</div>


<div align="left">

<table border="0" cellpadding="0" cellspacing="0">

<tr>
<td valign="top" width="350" colspan="2"><img border="0" src="./images/spacer_5.gif" width="25" height="11">
</td>
</tr>

<tr>
<td valign="top" width="350"><font face="Arial" size="2">Once you have the <b>

WishList Site</b> bookmark in your browser you can find the specific product you're wishing for,
anywhere on the Web. Then use the <b>WishList Site</b> bookmark to put that product's web page in your
WishList!</font>
</td>
<td valign="top"><img border="0" src="./images/spacer_5.gif" width="19" height="14"></td>
</tr>

</table>

</div>

<div align="left">
<table border="0" cellpadding="0" cellspacing="0">

<tr>
<td valign="top" width="350" colspan="2"><img border="0" src="./images/spacer_5.gif" width="25" height="19">
</td>
</tr>

<tr>

<td valign="top" width="350"><font face="Arial" size="2">
<a name="toolbar">
*Cant</a> find your Link
Toolbar? Here's a picture of how to open your Links Toolbar.</font>
</td>
<td valign="top"><img border="0" src="./images/spacer_5.gif" width="19" height="14"></td>
</tr>

</table>

</div>

<img border="0" src="./images/link_toolbar.gif" width="383" height="309">

</td>
<td valign="top"><img border="0" src="./images/spacer_5.gif" width="8" height="21"></td>
<td valign="top">
<img border="0" src="./images/drag_link.gif" width="220" height="179">
<div align="left">
<table border="0" cellpadding="0">

<tr>

<td width="125"><font face="Verdana, Helvetica, sans-serif" size="1"><b>Drag this Add to WishList
Button up to your links toolbar.</b></font></td>
<td> 

<a href="javascript:void(open('<?php echo $base_url; ?>/modifyList/addItemFromWeb.php?wldesc='+escape(document.title)+'&wlurl='+escape(location.href),'WishList_com','height=600,width=900,left=10,top=10,location=1,scrollbars=yes,menubar=1,toolbars=1,resizable=yes'));">Add to Wishlist Button</a></td>
</tr>

</table>
</div>
</td>
</tr>

</table>
</div>


</body>
</html>