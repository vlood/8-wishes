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

$firstname= convertString($_REQUEST["firstname"]);
$lastname= convertString($_REQUEST["lastname"]);
$suffix= convertString($_REQUEST["suffix"]);
$street = convertString($_REQUEST["street"]);
$city = convertString($_REQUEST["city"]);
$state = convertString($_REQUEST["state"]);
$zip = $_REQUEST["zip"];
$phone = convertString($_REQUEST["phone"]);
$mobilephone = convertString($_REQUEST["mobilephone"]);
$email = convertString($_REQUEST["email"]);
$bmonth = convertString($_REQUEST["bmonth"]);
$bday = $_REQUEST["bday"];
$url = convertString($_REQUEST["url"]);

if(!is_numeric($zip))
  $zip = 0;

if(!is_numeric($bday))
  $bday = 1;

$query = "update people set firstname = '" . $firstname . "', " .
       "lastname = '" . $lastname . "', " .
       "suffix = '" . $suffix . "', " .
       "street = '" . $street . "', " .
       "city = '" . $city . "', " . 
       "state = '" . $state . "', " . 
       "zip = " . $zip . ", " . 
       "email = '" . $email . "', " . 
       "phone = '" . $phone . "', " . 
       "mobilephone = '" . $mobilephone . "', " . 
       "url = '" . $url . "', " .
       "bmonth = '" . $bmonth . "', " .
       "bday = " . $bday . " " .
       "where userid = '" . $userid . "'";

$result = mysql_query($query) or die("Could not query: " . mysql_error());

header("Location: " . getFullPath("updateAccount.php"));
?>
