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

$dir = mysql_escape_string($_REQUEST["dir"]);

$cid = mysql_escape_string($_REQUEST["cid"]);

$cso = mysql_escape_string($_REQUEST["cso"]);

if($dir == "down"){
  $query = "update categories set catSortOrder = catSortOrder - 1 where catSortOrder = " . ($cso + 1) . " and userid = '" . $userid . "'";
  $result = mysql_query($query) or die("Could not query: " . mysql_error());

  $query = "update categories set catSortOrder = catSortOrder + 1 where cid = " . $cid;
  $result = mysql_query($query) or die("Could not query: " . mysql_error());

}
else
{
  $query = "update categories set catSortOrder = catSortOrder + 1 where catSortOrder = " . ($cso - 1) . " and userid = '" . $userid . "'";
  $result = mysql_query($query) or die("Could not query: " . mysql_error());

  $query = "update categories set catSortOrder = catSortOrder - 1 where cid = " . $cid;
  $result = mysql_query($query) or die("Could not query: " . mysql_error());

}

  header("Location: " . getFullPath("modifyList.php"));

  $query = "update people set lastModDate=NOW() where userid='" . $userid . "'";
  $result = mysql_query($query) or die("Could not query: " . mysql_error());
