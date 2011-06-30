<?php

// throw this flag to tell funcLib not to redirect us to login.php which it
// would try to do if the user is not logged in
$ignoreSession = true;

include "../funcLib.php";

if (isset($_SESSION["userid"])) {
  createPage($amazonTag, $amazonDevTag);
}
else {
  $displayLogin = 1;
  $errorMessage = "Please Sign In";
  $userid = $_REQUEST["userid"];
  $password = $_REQUEST["password"];

  if($userid != "" and $password != ""){

    $query = "select * from people where userid='" . $userid . "'"; 
    $rs = mysql_query($query) or die("Could not query: " . mysql_error());    
    $userValidate = 0;
    $passValidate = 0;
    
    $myuserid = " ";

    if($row = mysql_fetch_assoc($rs)){    
      $userValidate = 1;

      if ($password == $row["password"]){
        $passValidate = 1;
      }
    }

    if ($userValidate== 1 and  $passValidate == 1){
      $_SESSION["userid"] = $row["userid"];
      $_SESSION["fullname"] = $row["firstname"] . ' ' . $row["lastname"] . ' ' . $row["suffix"];
      $_SESSION["admin"] = $row["admin"];
       $query = "update people set lastLoginDate=NOW() where userid='" .
         $row["userid"] . "'";
       mysql_query($query) or die("Could not query: " . mysql_error());

       createPage($amazonTag, $amazonDevTag);
       $displayLogin=0;
     }
    elseif($userValidate == 1 and $passValidate == 0){
      $errorMessage = "Password Error! Please Try Again";
    }
    else{
      $errorMessage = "User Name Error! Please Try Again";
    }
}

if($displayLogin == 1){

$name=$_REQUEST["wlurl"];

  $pos = strpos($name, "//");
  $len = strpos($name, "/", $pos+2) - ($pos + 2);

?>
<HTML>

<link rel=stylesheet href=../style.css type=text/css>


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

    if (valid_js()) {

      var hash;
      if(form.plainpassword.value){
        hash=MD5(form.plainpassword.value);
        //alert ("Hash = " + hash);
      } else {
        hash="";
        return false;
      }

      form.password.value = hash;
      form.plainpassword.value = "";// don't send plaintext password over net

      return true;
    }
    return true;
}
</script>

<title>Add New Item</title>
<body bgcolor=#ffffff onload="document.login_form.userid.focus();">
<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>
<td valign="top" >
<center>
<b><?php echo $errorMessage ?></b>

<form method=post action="" name=login_form onsubmit="return hash(this,'addItemFromWeb.php')">

<table style="border-collapse: collapse;" id="AutoNumber1" border="1" bordercolor="#11111" cellpadding="2" cellspacing="0" bgcolor="lightyellow">
<tr><td colspan="2" align="center" bgcolor="#6702cc"> <b><font size=3 color="#ffffff">
Add New Item From the Web<br>
You will have a chance to modify this after you sign in 
</td></tr>
<tr><td align=right>
<b>Name</b>
</td>
<td bgcolor=white>
<input size=100 type=text name=wldesc value="<?php echo $_REQUEST["wldesc"] ?>">
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
  <input size=50 type=text name=wlurlname value="<?php echo str_replace("www.", "", substr($name, $pos+2, $len)) ?>">
  <br>
  <input type=text size=100 name=wlurl value="<?php echo $_REQUEST["wlurl"] ?>">
  </td></tr>
</table>
<p>
<table>
<tr><td colspan=2 align=center>
<b>Please sign in</b>
</td>
</tr>
<tr>
<td align=right>Userid</td>
<td><input type=text name=userid></td>
</tr>
<tr>
<td align=right>Password</td>
<td><input name="plainpassword" type="password" maxlength="32"></td>
</tr>
</table>
<input type=hidden name=password>
<br>
<input type=submit value="Submit">
</form>
<?php
}
}


function createPage($amazonTag, $amazonDevTag){

  $query = "Select * from categories where userid='" . $_SESSION["userid"] . "' and catSortOrder!=-1000 order by name"; 
  $rs = mysql_query($query) or die("Could not query: " . mysql_error());

  if(!($row = mysql_fetch_assoc($rs))){
    print "You have not created any categories yet.  Please do so and then try again";
  }
  else{
    
    $url= $_REQUEST["wlurl"];
    if(strpos($url, "amazon") != 0 && $amazonTag != '' && $amazonDevTag != ''){
      $amazon = 1;
      include "../include/nusoap.php";

      $asin = ereg("([0-9A-Za-z]{10})", $url, $regs);

      $soapclient = new
        soapclient("http://soap.amazon.com/schemas2/AmazonWebServices.wsdl",
                   true);

      // uncomment the next line to see debug messages
      // $soapclient->debug_flag = 1;
      
      // create a proxy so that WSDL methods can be accessed directly
      $proxy = $soapclient->getProxy();
      
      // set up an array containing input parameters to be
      // passed to the remote procedure
      $params = array(
                      'asin'        => $regs[1],
                      'tag'         => $amazonTag,
                      'type'        => 'heavy',
                      'devtag'      => $amazonDevTag
                      );
      
      // invoke the method 
      $result = $proxy->ASINSearchRequest($params);
      $items = $result['Details'];

      $url = $items[0]['Url'];;
      $url = str_replace("/" . $amazonTag, "/ref=nosim/" . $amazonTag, $url);

      $OurPrice = $items[0]['OurPrice'];
      if(!is_numeric(substr($OurPrice,1))) $OurPrice = "";

      $ProductName = $items[0]['ProductName'];
      if(isset($items[0]['Authors'])){
        $authors = implode(", ", $items[0]['Authors']);
      }
      if(isset($items[0]['Artists'])){
        $artists = implode(", ", $items[0]['Artists']);
      }
      $catalog = $items[0]['Catalog'];
      $releasedate = $items[0]['ReleaseDate'];
    }
?>

<?php
/*        print $url . "<br>";  */
/*        print $OurPrice . "<br>";  */
/*        print $ProductName . "<br>";  */
/*        print $authors . "<br>";  */
/*        print $artists . "<br>";  */
/*        print $catalog . "<br>";  */
/*        print $releasedate . "<br>";  */
  ?>
<HTML>

<link rel=stylesheet href=../style.css type=text/css>

<title>Add New Item</title>
      <BODY>
      <table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
      <tr>
      <td valign="top">

<?php
createNavBar("../home.php:Home|modifyList.php:Modify WishList|:Add Item From Web");
?>

      <center>
      <p>&nbsp;
      <form method="post" action="validate_addItem.php">
      <table style="border-collapse: collapse;" id="AutoNumber1" border="1" bordercolor="#11111" cellpadding="2" cellspacing="0" bgcolor="lightyellow">
      <tr>
      <td colspan="2" align="center" bgcolor="#6702cc"> 
      <b>
      <font size=3 color="#ffffff">Add New Item from the Web
      </td>
      </tr>  
      <tr>
      <td align="right">    
      <b>Category</b>
      </td>
      <td bgcolor="#eeeeee">    

<?php
      mysql_data_seek($rs, 0);

      print "<select name=cid>";

      while($row = mysql_fetch_assoc($rs)){
        if(strcmp($row["name"], "Items Under Consideration") == 0){
          $considerCid = $row["cid"];
        }
        else{
          print "<option value=" . $row["cid"];
          if($amazon &&
             strpos(strtolower($row["name"]), strtolower($catalog)) !== FALSE){
            print " selected";
            $select = 1;
          }
          print ">" . $row["name"] . "</option>\n";
        }
      }
      print "<option value=" . $considerCid;
      if(!$select){
        print " selected";
      }
      print ">Items Under Consideration</option>";
?>
      </select>

      </td>
      </tr>

      <tr>
      <td align="right">    
      <b>Title
      </b>  
      </td>
      <td bgcolor="#eeeeee">    
      <input type="text" size="50" name="title" value="
<?php if($amazon) echo $ProductName; else echo $_REQUEST["wldesc"]; ?>">  
      </td>
      </tr>  
      <tr>
      <td align="right">    
      <b>Description
      </b>  
      </td>
      <td bgcolor="#eeeeee">    
      <input type="text" size="50" name="desc" 
      <?php 
          if($amazon){
            if(isset($authors)){
              echo " value=\"" . $authors . "\"";
            }
            if(isset($artists)){
              echo " value=\"" . $artists . "\"";
            }
          }
      ?>
      >  
      </td>
      </tr>  
      <tr>
      <td align="right">    
      <b>Price
      </b>  
      </td>
      <td bgcolor="#eeeeee">    
      <input type="text" size="7" name="price" value="<?php echo $OurPrice ?>">  
      </td>
      </tr>  
      <tr>
      <td align="right">    
      <b>Quantity
      </b>  
      </td>
      <td bgcolor="#eeeeee">    
      <input type="text" size="7" name="quantity" value="1">  
      </td>
      </tr>  
      <tr>
      <td align="right">    
      <table>      
      <tr>        
      <td align=right>          
      <b>Link 1 Name
      </b>        
      </td>      
      </tr>      
      <tr>        
      <td align=right>          
      <b>URL
      </b>        
      </td>      
      </tr>    
      </table>  
      </td>
      <td bgcolor="#eeeeee">    

<?php
          if(!$amazon){
            $url=$_REQUEST["wlurl"];
            $pos = strpos($url, "//");
            $len = strpos($url, "/", $pos+2) - ($pos + 2);
            $urlName = str_replace("www.", "", substr($url, $pos+2,$len));
          }
          else{
            $urlName = "Amazon";
          }
?>

      <input type="text" size="50" name="link1" value="<?php echo $urlName?>">
      <br>    
      <input type="text" size="100" name="link1url" value="<?php echo $url ?>">  
      </td>
      </tr>  
      <tr>
      <td align="right">    
      <b>Additional
      <br>Information
      </b>  
      </td>
      <td bgcolor="#eeeeee">    
      <textarea ROWS='3' COLS='80' name="subdesc"></textarea>  
      </td>
      </tr>  
      <tr>
      <td align="right">    
      <table>      
      <tr>        
      <td align=right>          
      <b>Link 2 Name
      </b>        
      </td>      
      </tr>      
      <tr>        
      <td align=right>          
      <b>URL
      </b>        
      </td>      
      </tr>    
      </table>  
      </td>
      <td bgcolor="#eeeeee">    
      <input type="text" size="50" name="link2" >    
      <br>    
      <input type="text" size="100" name="link2url">  
      </td>
      </tr>  
      <tr>
      <td align="right">    
      <table>      
      <tr>        
      <td align=right>          
      <b>Link 3 Name
      </b>        
      </td>      
      </tr>      
      <tr>        
      <td align=right>          
      <b>URL
      </b>        
      </td>      
      </tr>    
      </table>  
      </td>
      <td bgcolor="#eeeeee">    
      <input type="text" size="50" name="link3">    
      <br>    
      <input type="text" size="100" name="link3url">  
      </td>
      </tr>  
      <tr>
      <td align="center" colspan="2">    
      <b>Allow people to check this item off
      </b>    
      <input type="checkbox" name="allowCheck" checked>  
      </td>
      </tr>
      <tr>
      <td colspan="2" bgcolor="#c0c0c0" align=center>
      <input type="submit" value="Add" style="font-weight:bold">
      </td>
      </tr>
      </table>
      </form>
      </td>
      </tr>
      </table>
      </body>
      </html>
<?php
    }
}

?>
