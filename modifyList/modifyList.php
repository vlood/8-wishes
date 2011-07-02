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

<head>
<script type="text/javascript" src="../getPriceHistory.js"></script>
</head>

<title>Modify List</title>
<BODY>

<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>
<td valign="top" >

<?php
createNavBar("../index.php:Home|:Modify WishList");
?>

<table width="100%"><tr><td>
<font face=arial size=5 color=red><b>Your List</b></font> - 
<font size=-1 face=arial><b>All check marks have been removed</b></font>
</td>
<td align="right">
<b><a class="menuLink" target="_blank" href="print.php">Printer Friendly Version</a></b>
</td></tr></table>

<?php
  $mquery = "select catSubDescription from categories where userid ='" . $userid . "' and catSortOrder=-1000";

  $mrs = mysql_query($mquery) or die("Could not query: " . mysql_error());
  $mrow = mysql_fetch_assoc($mrs);

print "<table border=0 cellpadding=2 cellspacing=0 width=100%><tr>";
if($mrow["catSubDescription"] != ""){

  print "<td NOWRAP bgcolor=#99ff99><a class=menuLink
href='editListComment.php'>[edit]</a><img width=26px height=13px
src=\"../images/space.GIF\"></td><td width=99% bgcolor=#ffffcc> "
. $mrow["catSubDescription"];

} else{ print "<td NOWRAP bgcolor=#99ff99><a class=menuLink
href='editListComment.php'>[add]</a><img width=26px height=13px
src=\"../images/space.GIF\"></td><td width=99% bgcolor=#ffffcc> Click
the link to the left to add a comment to the top of your list for
others to see"; }

print "</td></tr></table><p>";
?>

<?php 

printModifyList($userid);

?>
<table width=100%>
<tr><td colspan="3" align="center">
<form method="post" action="addCategory.php">
<input type="submit" value="Add New Category" style="font-weight:bold">
</form>
</td>
</tr>
</table>

<p>

<?php
  $mquery = "select * from categories where userid ='" . $userid . "' and catSortOrder=-10000";

  $mrs = mysql_query($mquery) or die("Could not query: " . mysql_error());
  $mrow = mysql_fetch_assoc($mrs);

  printCategory($mrow, $_SESSION["fullname"], 1, 0, 0, 1, 0, 0, 1);
?>


<!-- begin Send Update Notification Table -->
<table align=center>
<tr>
<script language="JavaScript">
<!-- Begin JavaScript
      function changeColor(tcell, color){
        if(color == 'off'){
          tcell.style.borderTopColor= '#B2AFC4';
          tcell.style.borderLeftColor= '#B2AFC4';
          tcell.style.borderBottomColor= '#7F7B91';
          tcell.style.borderRightColor= '#7F7B91';
          tcell.bgColor = '#9C98B3';
        }      
        else{
          tcell.style.borderTopColor= '#D1D1D4';
          tcell.style.borderLeftColor= '#D1D1D4';
          tcell.style.borderBottomColor= 'white';
          tcell.style.borderRightColor= 'white';
          tcell.bgColor = 'yellow';
        }
      }

-->
</script>

<td bgcolor="#9C98B3" 
  style="border: 2px solid rgb(128,255,128); 
         border-top-color: #B2AFC4;
         border-left-color: #B2AFC4;
         border-bottom-color: #7F7B91;
         border-right-color: #7F7B91;"
  onmouseover="changeColor(this,'on');"
  onmouseout="changeColor(this,'off');"
  height=27pt align=center
  onclick="javascript:open('../sendEmail.php?action=emailRecipViewers&recip=<?php
  print $_SESSION["userid"]
?>','WishList_com','height=575,width=850,left=10,top=10,location=1,scrollbars=yes,menubar=1,toolbars=1,resizable=yes');">

<b>
<a href="javascript:void(open('../sendEmail.php?action=emailRecipViewers&recip=<?php print $_SESSION["userid"] ?>','WishList_com','height=575,width=750,left=10,top=10,location=1,scrollbars=yes,menubar=1,toolbars=1,resizable=yes'))"><font color=black face="Times" size=2>&nbsp;&nbsp;Send Update Notification&nbsp;&nbsp;</font></a>
</b>
</td></tr>
</table>

</td>
</tr>
</table>
<!--
<script type="text/javascript" src="http://www.assoc-amazon.com/s/link-enhancer?tag=<?php echo $amazonTag; ?>"></script>
<noscript><img src="http://www.assoc-amazon.com/s/noscript?tag=<?php echo $amazonTag; ?>" alt="" /></noscript>
-->
</body>
</html>
