<?php
/// This program is free software; you can redistribute it and/or modify
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

@(include 'config.php');

if(!isset($base_dir)){
  // this should only be true when phpWishList is first
  // installed so we should be in the root directory.
  // redirect to admin
  header("Location: admin.php?setup=true");
  exit;
}

session_name("WishListSite");
session_start();

if (isset($_REQUEST["action"])) {
  if ($_REQUEST["action"] == "logout") {
    session_destroy();
  }
}

if(isset($_SESSION['userid'])){
   // already logged in
   header("Location: home.php");
   return;
}

$displayError = True;
$errorMessage = "Please Sign In";

if (!empty($_REQUEST["userid"])) {
  //print $_REQUEST["password"] . "<br>";

  $link = mysql_connect($db_loc, $db_userid, $db_pass);
  if(!$link){
    sendEmail($admin_email, "", "Database is dead", "umm, the database is dead", 0);
    die("<p><font size=6>Danger Will Robinson!!!!  It looks like the database is dead.<p>Try back at the top of the hour.</big>");
  }
  mysql_select_db($db_name);

  $query = "Select * from people where userid='" . $_REQUEST["userid"] . "'";
  $rs = mysql_query($query) or die("Could not query: " . mysql_error());
  
  $userValidate = 0;
  $passValidate = 0;
  
  if ($row = mysql_fetch_array($rs,MYSQL_ASSOC)) {
    
    $userValidate = 1;
    
    if($_REQUEST["password"] == $row["password"]){
      $passValidate = 1;
      session_start();

      $_SESSION["userid"] = $row["userid"];
      $_SESSION["fullname"] = $row["firstname"] . " " . $row["lastname"] . ' ' . $row["suffix"];
      $_SESSION["admin"] = $row["admin"];
      header("Location: home.php");
      mysql_free_result($rs);
      
      $query = "update people set lastLoginDate=NOW() where userid='" .
        $row["userid"] . "'";
      mysql_query($query) or die("Could not query: " . mysql_error());
      
      exit;
    }
  }
  
  if($userValidate == 1 and passValidate == 0){
    $errorMessage = "Password Error! Please Try Again";
  }
  else{
    $errorMessage = "User Name Error! Please Try Again";
  }
}
?>

<HTML>

<head>
</head>

<script language="javascript">
/*
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Copyright (C) Paul Johnston 1999 - 2000.
 * Updated by Greg Holt 2000 - 2001.
 * See http://pajhome.org.uk/site/legal.html for details.
 */

/*
 * Convert a 32-bit number to a hex string with ls-byte first
 */
var hex_chr = "0123456789abcdef";
function rhex(num)
{
  str = "";
  for(j = 0; j <= 3; j++)
    str += hex_chr.charAt((num >> (j * 8 + 4)) & 0x0F) +
           hex_chr.charAt((num >> (j * 8)) & 0x0F);
  return str;
}

/*
 * Convert a string to a sequence of 16-word blocks, stored as an array.
 * Append padding bits and the length, as described in the MD5 standard.
 */
function str2blks_MD5(str)
{
  nblk = ((str.length + 8) >> 6) + 1;
  blks = new Array(nblk * 16);
  for(i = 0; i < nblk * 16; i++) blks[i] = 0;
  for(i = 0; i < str.length; i++)
    blks[i >> 2] |= str.charCodeAt(i) << ((i % 4) * 8);
  blks[i >> 2] |= 0x80 << ((i % 4) * 8);
  blks[nblk * 16 - 2] = str.length * 8;
  return blks;
}

/*
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally 
 * to work around bugs in some JS interpreters.
 */
function add(x, y)
{
  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return (msw << 16) | (lsw & 0xFFFF);
}

/*
 * Bitwise rotate a 32-bit number to the left
 */
function rol(num, cnt)
{
  return (num << cnt) | (num >>> (32 - cnt));
}

/*
 * These functions implement the basic operation for each round of the
 * algorithm.
 */
function cmn(q, a, b, x, s, t)
{
  return add(rol(add(add(a, q), add(x, t)), s), b);
}
function ff(a, b, c, d, x, s, t)
{
  return cmn((b & c) | ((~b) & d), a, b, x, s, t);
}
function gg(a, b, c, d, x, s, t)
{
  return cmn((b & d) | (c & (~d)), a, b, x, s, t);
}
function hh(a, b, c, d, x, s, t)
{
  return cmn(b ^ c ^ d, a, b, x, s, t);
}
function ii(a, b, c, d, x, s, t)
{
  return cmn(c ^ (b | (~d)), a, b, x, s, t);
}

/*
 * Take a string and return the hex representation of its MD5.
 */
function MD5(str)
{
  x = str2blks_MD5(str);
  var a =  1732584193;
  var b = -271733879;
  var c = -1732584194;
  var d =  271733878;
 
  for(i = 0; i < x.length; i += 16)
  {
    var olda = a;
    var oldb = b;
    var oldc = c;
    var oldd = d;

    a = ff(a, b, c, d, x[i+ 0], 7 , -680876936);
    d = ff(d, a, b, c, x[i+ 1], 12, -389564586);
    c = ff(c, d, a, b, x[i+ 2], 17,  606105819);
    b = ff(b, c, d, a, x[i+ 3], 22, -1044525330);
    a = ff(a, b, c, d, x[i+ 4], 7 , -176418897);
    d = ff(d, a, b, c, x[i+ 5], 12,  1200080426);
    c = ff(c, d, a, b, x[i+ 6], 17, -1473231341);
    b = ff(b, c, d, a, x[i+ 7], 22, -45705983);
    a = ff(a, b, c, d, x[i+ 8], 7 ,  1770035416);
    d = ff(d, a, b, c, x[i+ 9], 12, -1958414417);
    c = ff(c, d, a, b, x[i+10], 17, -42063);
    b = ff(b, c, d, a, x[i+11], 22, -1990404162);
    a = ff(a, b, c, d, x[i+12], 7 ,  1804603682);
    d = ff(d, a, b, c, x[i+13], 12, -40341101);
    c = ff(c, d, a, b, x[i+14], 17, -1502002290);
    b = ff(b, c, d, a, x[i+15], 22,  1236535329);    

    a = gg(a, b, c, d, x[i+ 1], 5 , -165796510);
    d = gg(d, a, b, c, x[i+ 6], 9 , -1069501632);
    c = gg(c, d, a, b, x[i+11], 14,  643717713);
    b = gg(b, c, d, a, x[i+ 0], 20, -373897302);
    a = gg(a, b, c, d, x[i+ 5], 5 , -701558691);
    d = gg(d, a, b, c, x[i+10], 9 ,  38016083);
    c = gg(c, d, a, b, x[i+15], 14, -660478335);
    b = gg(b, c, d, a, x[i+ 4], 20, -405537848);
    a = gg(a, b, c, d, x[i+ 9], 5 ,  568446438);
    d = gg(d, a, b, c, x[i+14], 9 , -1019803690);
    c = gg(c, d, a, b, x[i+ 3], 14, -187363961);
    b = gg(b, c, d, a, x[i+ 8], 20,  1163531501);
    a = gg(a, b, c, d, x[i+13], 5 , -1444681467);
    d = gg(d, a, b, c, x[i+ 2], 9 , -51403784);
    c = gg(c, d, a, b, x[i+ 7], 14,  1735328473);
    b = gg(b, c, d, a, x[i+12], 20, -1926607734);
    
    a = hh(a, b, c, d, x[i+ 5], 4 , -378558);
    d = hh(d, a, b, c, x[i+ 8], 11, -2022574463);
    c = hh(c, d, a, b, x[i+11], 16,  1839030562);
    b = hh(b, c, d, a, x[i+14], 23, -35309556);
    a = hh(a, b, c, d, x[i+ 1], 4 , -1530992060);
    d = hh(d, a, b, c, x[i+ 4], 11,  1272893353);
    c = hh(c, d, a, b, x[i+ 7], 16, -155497632);
    b = hh(b, c, d, a, x[i+10], 23, -1094730640);
    a = hh(a, b, c, d, x[i+13], 4 ,  681279174);
    d = hh(d, a, b, c, x[i+ 0], 11, -358537222);
    c = hh(c, d, a, b, x[i+ 3], 16, -722521979);
    b = hh(b, c, d, a, x[i+ 6], 23,  76029189);
    a = hh(a, b, c, d, x[i+ 9], 4 , -640364487);
    d = hh(d, a, b, c, x[i+12], 11, -421815835);
    c = hh(c, d, a, b, x[i+15], 16,  530742520);
    b = hh(b, c, d, a, x[i+ 2], 23, -995338651);

    a = ii(a, b, c, d, x[i+ 0], 6 , -198630844);
    d = ii(d, a, b, c, x[i+ 7], 10,  1126891415);
    c = ii(c, d, a, b, x[i+14], 15, -1416354905);
    b = ii(b, c, d, a, x[i+ 5], 21, -57434055);
    a = ii(a, b, c, d, x[i+12], 6 ,  1700485571);
    d = ii(d, a, b, c, x[i+ 3], 10, -1894986606);
    c = ii(c, d, a, b, x[i+10], 15, -1051523);
    b = ii(b, c, d, a, x[i+ 1], 21, -2054922799);
    a = ii(a, b, c, d, x[i+ 8], 6 ,  1873313359);
    d = ii(d, a, b, c, x[i+15], 10, -30611744);
    c = ii(c, d, a, b, x[i+ 6], 15, -1560198380);
    b = ii(b, c, d, a, x[i+13], 21,  1309151649);
    a = ii(a, b, c, d, x[i+ 4], 6 , -145523070);
    d = ii(d, a, b, c, x[i+11], 10, -1120210379);
    c = ii(c, d, a, b, x[i+ 2], 15,  718787259);
    b = ii(b, c, d, a, x[i+ 9], 21, -343485551);

    a = add(a, olda);
    b = add(b, oldb);
    c = add(c, oldc);
    d = add(d, oldd);
  }
  return rhex(a) + rhex(b) + rhex(c) + rhex(d);
}

function valid_js() {
   // anything that claims NS 4 or higher functionality better work 
   if (navigator.userAgent.indexOf("Mozilla/") == 0) {
      return (parseInt(navigator.appVersion) >= 4);
   }
   return false;
}
 
function hash(form,login_url) {
    // this is Javascript enabled browser
    // rudimentary check for a 4.x brower. should catch IE4+ and NS4.*
    var url;

    if (arguments.length > 1 && login_url != "") { // in case login_url is not passed in
      url = login_url;
    } else {
      url = "login.php";
    }
    url += "?";
      
    if (valid_js()) {
      var hash;
      if(form.plainpassword.value){
        hash=MD5(form.plainpassword.value);
        //alert ("Hash = " + hash);
      } else {
        hash="";
        return false;
      }

      url += "password=" + hash;
      url += "&userid=" + form.userid.value;

      form.password.value = hash;
      form.plainpassword.value = ""; // don't send plaintext password over net

      location.href = url;
      form.onsubmit = null;

      return false;
    }
    return true;
}
</script>

<link rel=stylesheet href=style.css type=text/css>

<title>WishList Login</title>

<body bgcolor=#ffffff onload="document.login_form.userid.focus();">
<center>

<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>

<td valign="top" align=center>
<p>&nbsp;
<p>&nbsp;

<table border=0 cellpadding=0 cellspacing=0 width=680>
  <tr>
    <td colspan=2>
      <table border=0 width=100% cellpadding=4 cellspacing=0>
        <tr>
	  <td align="center" bgcolor="#6702cc" >
            <font size=+1 face=Arial color=white>
            <b>Welcome to the WishList Site!</b>
            </font>
	  </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center">
      <font color="green" face="arial"><b>
      <?php print $errorMessage ?>
      </b></font>                                          
    </td>
  </tr>
  <tr>
    <td> 
      <table border=0 cellspacing=2 cellpadding=0 width=100%>
        <tr>
	  <td width="50%" align="center" valign="center">
            <table border=0 bgcolor="lightyellow" height="100%" width="100%">
              <tr height="70%">
                <td>
                  <font face=Arial size=-1>
                  The site is still under heavy development so some features
                  may not be working yet.  <p>This site was developed using FireFox.  Please send me an email if things look weird in your browser
                  </font>
                  <p>&nbsp;
                </td>
              </tr>
              <tr height="30%">
                <td align="center">
                  <font face=Arial size=+1><b>New to the WishList Site?</b></font>
                  <p>
                  <a href="registerUser.php">
                  Sign up now</a> to enjoy the WishList site              
                </td>
              </tr>
            </table>
          </td>
          <td align="left" valign="top">
            <form method=post action=login.php name=login_form onsubmit="return hash(this,'login.php')">
            <table border="0" cellspacing="6" cellpadding="6" bgcolor="ffffff" width="100%">
              <tr bgcolor="eeeeee">
                <td align="center">
                  <font face="arial"><b>Existing WishList users</b></font><br>
                  <font face="arial" size="-1"><nobr>&nbsp;Enter your ID and password to sign in&nbsp; </nobr></font>
                  <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                      <td align="right">
                        <table border="0" cellpadding="2" cellspacing="0">
                          <tr>
                            <td align="right" nowrap>
                              <font face="arial" size="-1">
                              WishList ID:
                              </font>
                            </td>
                            <td>
                              <input name="userid" size="17" value="">
                            </td>
                          </tr>
                          <tr>
                            <td align="right" nowrap>
                              <font face="arial" size="-1">Password:</font>
                            </td>
                            <td>
                              <input name="password" type="hidden">
                              <input name="plainpassword" type="password" size="17" maxlength="32">
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" nowrap align="center">
                              <!--<font face="arial" size="-1">
                              <input type="checkbox" disabled name="rememberID" value="y">
                              Remember my ID on this computer
                              </font>-->
                            </td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>
                              <input name="submitButton" type="submit" value="Sign In">
                            </td> 
                          </tr>
                        </table>
                      </td>
                    </tr> 
                  </table>
                </td>
              </tr>
              <tr bgcolor="eeeeee">
                <td valign="top" align="center">
                  <table border=0 width="100%">
                    <tr>
                      <!--<td align="left">
                        <font face="arial" size="-1">
                        <a href="javascript:alert('Feature not yet implemented')">
                        <s>Sign-in help</s></a>
                        </font>
                      </td>
                      -->
                      <td align="center">
                        <font face="arial" size="-1">
                        <a href="forgotPassword.php">
                        Forgot your password?</a>
                        </font>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            </form>
          </td>
        </tr>
      </table>	
    </td>
  </tr>
</table>	

<p>
<table>
<!--tr><td><a href="wishlist_button.php" >Add the WishList button to your ToolBar!</a></td></tr-->
</table>
</center>
<hr>
<table width=90%>
<tr><td align=center>
<table>
<tr><td colspan=2 bgcolor="#2199ee">
<font size=+1 face=Arial color=white>
<b>Latest News</b>
</font>
</td></tr>

<tr> <td nowrap valign=top bgcolor=wheat><font color=purple><b>July
22, 2006</b></font> </td></tr> <tr><td>Woops!  The new feature listed
on June 24 was not implemented correctly.  Basically only Admins of
the site got to see the cool new feature.  Everyone else probably got
some weird results.  I've fixed it so now everyone should be able to
view the price history.</td></tr>



<tr> <td nowrap valign=top bgcolor=wheat><font color=purple><b>June
24, 2006</b></font> </td></tr> <tr><td>I've noticed Amazon alters the
price of their items quite frequently so I added a new feature that
lets you track these changes.  The format of the price of an item will
look like <a href='' class=menuLinkRedDashed>$21.99</a> if Amazon has
changed its price since it was added to the list.  You can click on
the price and a window will open displaying the historical prices
Amazon has charged for that item.</td></tr>



<tr> <td nowrap valign=top bgcolor=wheat><font color=purple><b>January
02, 2006</b></font> </td></tr> <tr><td> You will now see a link
labeled <font color=red>[del]</font> next to each item and category on
your list.  This link allows you to delete the item or category
directly from your lists homepage. </td></tr>



<tr> <td nowrap valign=top bgcolor=wheat><font color=purple><b>April
09, 2005</b></font></td> </tr> <tr> <td> You will see the following at
the top of your list <br>&nbsp; <center> <table ><td NOWRAP
bgcolor=#99ff99><a class=menuLink href=''>[add]</a><img width=26px
height=13px src="images/space.GIF"></td><td bgcolor=#ffffcc> Click the
link to the left to add a comment to the top of your list for others
to see</td> </tr></table> </center> <br> You might use this to make a
general comment about the items on your list such as, <i>"The items
listed on this page are only suggestions, I would be happy with any
present I receive"</i>.  Once added, you will be able to edit and/or
delete the comment.</td></tr>

<tr> <td nowrap valign=top bgcolor=wheat><font color=purple><b>April
08, 2005</b></font></td> </tr> <tr> <td> Added a help page to the
WishList Site.  There is now a link labeled <font
color=blue>Help</font> in the upper right hand corner of each page.
The help page contains answers to frequently asked questions
regarding the WishList Site.  </td> </tr>


<tr> <td nowrap valign=top bgcolor=wheat><font
color=purple><b>December 15, 2004</b></font></td> </tr> <tr> <td> Now
when you add a new item to a category you will see a button called
<b>Perform Amazon Lookup</b> next to the input box for <i>Link
1</i>. If you want to add an item from amazon, all you have to do is
copy and paste the amazon url for the item into <i>Link 1 URL</i> and
then click <b>Perform Amazon Lookup</b>. The WishList Site will then
automatically fill in the <i>Title</i>, <i>Description</i>, and
<i>Price</i> for you.  <p> Even easier, if you placed the <b>Add to
Wishlist Button</b> link to your bookmarks then you can simply click
on that while you are visiting the item you want at amazon and the
WishList Site will fill everything in for you.  </td> </tr>

<tr> <td nowrap valign=top bgcolor=wheat><font
color=purple><b>November 30, 2004</b></font></td> </tr> <tr> <td>
There is now a button labeled "Send Update Notification" located in
the left hand menu of the "Modify WishList" portion of the site.
Clicking on the button will open a new window where you can compose
and send an email to every person who can view your list.  The emails
that are sent will appear to have originated directly from your email
address.</td> </tr>

<tr> <td nowrap valign=top bgcolor=wheat><font
color=purple><b>November 7, 2004</b></font></td> </tr> <tr> <td> Added
a new category call "Items Under Consideration".  Items you place in
this category will not be visible to the people who can view your list
so you can use it to store items you are considering for inclusion.  If
you decide you do want an item in this category to be visible to
everyone else, simply move it to another category (i.e. click <font
color=blue>[edit]</font> for the item you want to move and then select
the desired category in the drop down box) </td> </tr>

<tr>
<td nowrap valign=top bgcolor=wheat><font color=purple><b>August 18, 2004</b></font></td>
</tr>
<tr>
<td>
You can now compose and send email to other WishList users directly from the WishList Site. 
To use this new feature, rollover someone's name on the homepage and then click <font color=blue>E-mail</font>.
</td>
</tr>

<tr>
<td nowrap valign=top bgcolor=wheat><font color=purple><b>August 16, 2004</b></font></td>
</tr>
<tr>
<td>
The waving santa graphic is gone (at least until xmas).  The image in the upper left hand corner of the home page will now change with the seasons.  Eventually the Season Greetings graphics will change as well.
</td>
</tr>

<tr>
<td nowrap valign=top bgcolor=wheat><font color=purple><b>August 14, 2004</b></font></td>
</tr>
<tr>
<td>
<ol>
<li>You will no longer see <b><font color=red>[Undo]</font></b> next to an item when you check it off a list. The algorithm I was using did not work in certain situations and had to be changed. I may reintroduce this feature in the future.
<p><b>Note:</b> you can still view (and delete) your purchases by clicking on a list's purchase log.
<li>Your name will now appear on the homepage along with the other people's lists you can view.  
If you click on your name you will be asked to confirm that you really want to view your own list. 
This feature could be helpful if you want to know who bought what on your list. If you don't want to see you name on the homepage, goto <font color=indianred>Manage List Access</font> and remove your name.
<p>
<b>Note:</b> you will not be able to see comments people have left on your list.
</ol>
</td>
</tr>

<tr>
<td nowrap valign=top bgcolor=wheat><font color=purple><b>August 13, 2004</b></font></td>
</tr>
<tr>
<td>
<ol>
<li>You will now be alerted if new comments have been added to a list since the last time you viewed it
<li>Can now assign people read only access to your list
</ol>
</td>
</tr>

<tr>
<td nowrap valign=top bgcolor=wheat><font color=purple><b>August 12, 2004</b></font></td>
</tr>
<tr>
<td>
<ol>
<li><img src="images/star.gif"> Can now star items you really want.  There is an additional checkbox on the Add New Item and Edit Item pages.
<li>Can now add someone to view your list if you know their userid or their email address.
<li>Can view someone elses list if you know their userid (before it was just their email)
</ol>
</td>
</tr>

<tr>
<td nowrap valign=top bgcolor=wheat><font color=purple><b>August 11, 2004</b></font></td>
</tr>
<tr>
<td>Created seperate page for managing list access (click <font color=indianred>Manage List Access</font> under <font color=indianred>Update Account</font>) with the following functionality:
<ol>
<li>Can now selectively choose who can view your contact information such as address and phone.
<li>Changed method for gaining access to other people's lists. You can choose to view someone elses list if you know their email address.
<li>Can now choose to stop viewing someone elses list.
</ol>
</td>
</tr>

<tr>
<td nowrap valign=top bgcolor=wheat><font color=purple><b>August 10, 2004</b></font></td>
</tr><tr>
<td><ol><li>Items can now be moved to different categories.  This can be helpful if you accidently insert an item into the wrong category.  Click <font color=blue>[edit]</font> for the item you want to move and then select the desired  category in the drop down box.
<li>New users can sign up for accounts
</ol>
</td>
</tr>

<tr>
<td nowrap valign=top bgcolor=wheat><font color=purple><b>August 08, 2004</b></font></td>
</tr><tr>
<td>Additional information can now be added to categories (will be displayed under the category heading)
</td>
</tr>

<tr>
<td nowrap valign=top bgcolor=wheat><font color=purple><b>August 04, 2004</b></font></td>
</tr><tr>
<td>Finished PHP site. New features will be added on a periodic basis.  Check here for updates.
</td></tr>
</table>

</td></tr></table>
</body>
</html>
