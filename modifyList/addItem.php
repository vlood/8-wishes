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

$cid = $_REQUEST["cid"];
$cname = $_REQUEST["cname"];
if (get_magic_quotes_gpc()==1) {
   $cname = stripslashes($cname);
}

if(isset($_REQUEST["amazonLookup"])){
  $url = $_REQUEST["url"];
  if(strpos($url, "amazon") != 0){
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
}
?>
<HTML>

<link rel=stylesheet href=../style.css type=text/css>

<title>Add New Item</title>

<BODY>

<table cellspacing="0" cellpadding="5" width="100%" height="100%" bgcolor="#FFFFFF" nosave border="0" style="border: 1px solid rgb(128,255,128)">
<tr>
<td valign="top" >

<?php
createNavBar("../index.php:Home|modifyList.php:Modify WishList|:Add Item");
?>

<center>
<p>&nbsp;
<script language="JavaScript">
<!-- Begin JavaScript

function validateUrl(){
  if(theForm.link1url.value.indexOf("amazon") == -1){
    alert ("Not a valid amazon url");
    return;
  }
  location.href = "<?php echo getFullPath("addItem.php?cid=" . $cid . "&cname=" . $cname . "&amazonLookup=true&url=") ?>" + theForm.link1url.value;
}
-->
</script>
<form method="post" name="theForm" action="validate_addItem.php">
<input type="hidden" size="50" name="cid" value="<?php echo $cid; ?>">

<table style="border-collapse: collapse;" id="AutoNumber1" border="1" bordercolor="#11111" cellpadding="2" cellspacing="0" bgcolor="lightyellow">
<tr><td colspan="2" align="center" bgcolor="#6702cc"> <b><font size=3 color="#ffffff">
Add new item to "<?php echo $cname; ?>" category
</td></tr>

  <tr><td align="right">
    <b>Title</b>
  </td><td bgcolor="#eeeeee">
    <input type="text" size="100" name="title" value="<? echo $ProductName ?>">
  </td></tr>
  <tr><td align="right">
    <b>Description</b>
  </td><td bgcolor="#eeeeee">
    <input type="text" size="100" name="desc" value="<? echo $artists . $authors ?>">
  </td></tr>
  <tr><td align="right">
    <b>Price</b>
  </td><td bgcolor="#eeeeee">
    <input type="text" size="7" name="price" value="<? echo $OurPrice ?>">
  </td></tr>
  <tr><td align="right">
    <b>Quantity</b>
  </td><td bgcolor="#eeeeee">
    <input type="text" size="7" name="quantity" value="1">
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
    <table width=100%>
      <tr><td>
        <input type="text" size="50" name="link1" value="
<?php
if($amazon){
  // if this is a amazon link then set link name to be Amazon
  echo "Amazon";
}
?>
">
        <br>
        <input type="text" size="97" name="link1url" value="<? echo $url ?>">
      </td>
<?php
if($amazonTag != '' && $amazonDevTag != ''){
  // don't show amazon stuff if we don't have an amazon associates account
?>
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
          tcell.bgColor = 'white';
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
        onmouseover="changeColor(this,'on');" onmouseout="changeColor(this,'off');">
        <a href="javascript:validateUrl()"><font color=black face="Times" size=2><b>Perform<br>Amazon<br>Lookup</b></font></a>
      </td>
<?php
}  
?>
      </tr>
    </table>
  </td></tr>


  <tr><td align="right">
    <b>Additional<br>Information</b>
  </td><td bgcolor="#eeeeee">
    <textarea ROWS='3' COLS='80' name="subdesc"></textarea>
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
    <input type="text" size="50" name="link2" >
    <br>
    <input type="text" size="100" name="link2url">
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
    <input type="text" size="50" name="link3">
    <br>
    <input type="text" size="100" name="link3url">
  </td></tr>

  <tr><td align="center" colspan="2">
    <b>Allow people to check this item off</b>
    <input type="checkbox" name="allowCheck" checked>
  </td></tr>
  <tr><td align="center" colspan="2">
    <b>Add a star to this item</b>
    <input type="checkbox" name="addStar">
  </td></tr>

<tr><td colspan="2" bgcolor="#c0c0c0" align=center>
<input type="submit" value="Add" style="font-weight:bold">
</td></tr></table>

</form>

</td></tr></table>
</body>
</html>
