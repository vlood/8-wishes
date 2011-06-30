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

$recip = $userid;
$name = $_SESSION["fullname"];

?>

<HTML>

<link rel=stylesheet href=../style.css type=text/css>

<title><?php echo $name ?>'s WishList</title>
<BODY>
<font size="5"><b><?php echo $name ?>'s WishList</b></font>
<p>
<h2><font color=red>All indications of purchases have been removed</font></h2>

<?php printList2($recip, $userid, $name, 1, 0); 

?>
</body>
</html>
