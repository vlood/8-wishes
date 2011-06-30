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

$cname = convertString($_REQUEST["cname"]);
$description = convertString($_REQUEST["description"]);
$linkname = convertString($_REQUEST["linkname"]);
$linkurl = convertString($_REQUEST["linkurl"]);
$catSubDescription = convertString($_REQUEST["catSubDescription"]);

// Note our use of ===.  Simply == would not work as expected
// because the position of "http" is at the front of a url
if($linkurl != "" && strpos($linkurl, "http") === false){
    $linkurl = "http://" . $linkurl;
}                

$query = "select max(catSortOrder) as cso from categories where userid='" . $userid . "'";
$rs = mysql_query($query) or die("Could not query: " . mysql_error());

$cso = 0;

if($row = mysql_fetch_assoc($rs)){
   $cso = $row["cso"];
   $cso++; // increment
}

if($cso == "" or $cso < 0)
   $cso = 0;

$query = "insert into categories (cid, userid, name, description, linkname, linkurl, catSortOrder, catSubDescription) values (null, '" . $userid . "', " .
"'" . $cname . "', " .
"'" . $description . "', " .
"'" . $linkname . "', " .
"'" . $linkurl . "', " . 
$cso . ", " . 
"'" . $catSubDescription . "')";

$rs = mysql_query($query) or die("Could not query: " . mysql_error());

header("Location: " . getFullPath("modifyList.php"));

$query = "update people set lastModDate=NOW() where userid='" . $userid . "'";

$rs = mysql_query($query) or die("Could not query: " . mysql_error());
