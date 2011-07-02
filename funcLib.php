<?php

@(include 'config.php');

if(!isset($base_dir)){
  // this should only be true when phpWishList is first
  // installed so we should be in the root directory.
  // redirect to admin only if we aren't currently there
  if(!isset($doingSetup)){
    header("Location: admin.php?setup=true");
    exit;
  }
}

check_sesion();

$link = @(mysql_connect($db_loc, $db_userid, $db_pass));
if(!$link){
  if(!isset($doingSetup)){
    sendEmail($admin_email, "", "Database is dead", "umm, the database is dead", 0);
    die("<p><font size=6>Danger Will Robinson!!!!  It looks like the database is dead.<p>Try back at the top of the hour.</big>");
  }
}
else{
  mysql_select_db($db_name);
}

function check_sesion() {
    if(!isset($_SESSION)){
      session_name("WishListSite");
      session_start();

      if (!isset($_SESSION["userid"])) {

        // if the page that is including this file has not set $ignoreSession then
        // redirect the user to login
        if(!isset($ignoreSession)){
          header("Location: " . $base_url.'/login.php');
          exit;
        }
      }
      else {
        $userid = $_SESSION["userid"];
      }
    }
}


/* getSeason : returns the current season
 *
 * 1 => winter (jan 1  -> mar 20)
 * 2 => spring (mar 21 -> jun 20)
 * 3 => summer (jun 21 -> sep 21) 
 * 4 => fall   (sep 22 -> nov 30)
 * 5 => christmas (dec 1 -> dec 31)
 */
function getSeason(){
  $today = getdate();
  
  $d1 = mktime(0,0,0, $today['mon'], $today['mday'], 2004);

  if($d1 >= 1072936800 and $d1 < 1079848800){ //jan 1 and mar 21
    $season = 1;
  }
  elseif ($d1 < 1087794000){ //june 21
    $season = 2;
  }
  elseif ($d1 < 1095829200){ // sept 22
    $season = 3;
  }
  elseif ($d1 < 1101880800){ // dec 1
    $season = 4;
  }
  else{ // xmas
    $season = 5;
  }

  return $season;
}


// $data must be of the form url_1:url_1Name|url_2:url_2Name...|:url_nName
// $helpLink indicates where to anchor to in help.php
function createNavBar($data, $displayGreeting = false, $helpLink = ''){

global $base_url;

print "<table width=100% class=\"yellowBorder\">";
print "<tr class=\"lightYellow\"><td width=10%>"; //<b>NavBar : </b>";

$items = explode("|", $data);

$array_count = count($items);

for($i = 0; $i < $array_count; $i++){
  $piece = explode(":", $items[$i]);

  if($i == $array_count - 1){
    print "<b>$piece[1]</b>";
  }
  else{
    print "<a class=\"menuLink\" href=\"$piece[0]\">$piece[1]</a>";
    print " > ";
  }
}
print "</td>";

if($displayGreeting){
  print "<td align=center width=80%>";
  print "<b>Welcome " . $_SESSION["fullname"] . "</b>";
  print "</td>";
}

print "<td width=10% align=\"right\"><a class=menuLink target=\"_blank\" href=\"$base_url/help.php#$helpLink\">Help</a> :: <a class=\"menuLink\" href=\"$base_url/logout.php\">logout</a>";
print "</td></tr></table>";
}

function getFullPath($url) {
  $fp = "http://";
  $fp .= $_SERVER["HTTP_HOST"];
  $dir = dirname($_SERVER["PHP_SELF"]);
  if ($dir != "/")
    $fp .= $dir;
  $fp .= "/" . $url;
  return $fp;
}

/* $date is assumed to be a date obtained from mysql so it's of the form
 * YYYY-MM-DD hh:mm:ss
 *
 * All dates should be passed through this function before display
 */
function parseDate($date, $shortMonth=0, $clientLoc = 3){
  /* Indicate what time zone the servers is in.
   * 0 = Alaska
   * 1 = Pacific
   * 2 = Mountain
   * 3 = Central
   * 4 = Eastern
   */
  $serverLoc = 1; // server is in califonia

  ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})", $date, $regs);

  // now we need to localize the time.  This would be much better if we knew the time
  // zone of the user and then added/subtracted the approiate amount dynamically
  $dateCon = mktime ($regs[4] + ($clientLoc - $serverLoc),
                     $regs[5],$regs[6],$regs[2],$regs[3],$regs[1]);

  if($shortMonth){
    return strftime("%b %d, %Y at %I:%M %p", $dateCon);
  }
  else{
    return strftime("%B %d, %Y at %I:%M %p", $dateCon);
  }
}

/* $to should be of the form "x@x.com, y@y.com, z@z.com".  The
 $message should have all new lines converted to <br> wherever desired
 before calling this method */
function sendEmail($to, $from, $subject, $message, $debug){

  $degug = 1;

  if($debug == 1){
    $to = "Me <" . $admin_email . ">";
  }
  
  if($from == "")
    $from = "From: WishList Site <" . $admin_email . ">\n";
  else
    $from = "From: " . $from . "\n";
  
  $headers  = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
  
  $message = "<html><body><small>This is an automated message from the " .
    "<a href=\"". getFullPath("home.php") . "\">WishList Site</a>." .
    "</small><p>" . $message . "</body></html>";

  if (mail($to, $subject, str_replace("<br>", "<br>\r\n", str_replace(". ", ". \r\n", $message)), $from . $headers)){
    return 1;
  }
  else{
    return 0; // an error has occurred
  }
}

function TESTsendEmail($to, $from, $subject, $message, $debug){

  if($debug == 1){
    $to = "Me <" . $admin_email . ">";
  }
  
  if($from == "")
    $from = "From: WishList Site <" . $admin_email . ">";
  else
    $from = "From: " . $from;
  
  $message = str_replace("<br>", "<br>\r\n", str_replace(". ", ". \r\n", $message));

  $boundary = md5(uniqid(time()));

  $headers  = "From: " . $from . "\r\n" .
              "Return-Path: " . $from . "\r\n" .
              "MIME-Version: 1.0\r\n" .
              "Content-Type: multipart/alternative; boundary=\"" . $boundary . "\"\r\n\r\n" .
              "--" . $boundary . "\r\n" .
              "Content-Type: text/plain; charset=ISO-8859-1\r\n" .
              "Content-Transfer-Encoding: 8bit\r\n" .
              "Content-Disposition: inline\r\n\r\n" .
              "Please view this with an HTML compliant browser\r\n" .
              "--" . $boundary . "\r\n" .
              "Content-Type: text/html; charset=ISO-8859-1\r\n" .
              "Content-Transfer-Encoding: 8bit\r\n" .
              "Content-Disposition: inline\r\n\r\n" .
              "<html><body><small>This is an automated message from the " .
              "<a href=\"". getFullPath("home.php") . "\">WishList Site</a>." .
              "</small><p>" . $message . "</body></html>\r\n" .
              "--" . $boundary . "--\r\n";

  if (mail($to, $subject, '', $headers)){
    return 1;
  }
  else{
    return 0; // an error has occurred
  }
}

/* $to should be of the form "x@x.com, y@y.com, z@z.com".  The
 $message should have all new lines converted to <br> wherever desired
 before calling this method */
function sendEmailFromFavorites($to, $from, $subject, $message, $debug){

  if($debug == 1){
    $to = "Me <" . $admin_email . ">"; 
  }
  
  if($from == "")
    $from = "From: Favorites Site <" . $admin_email . ">\n";
  else
    $from = "From: " . $from . "\n";
  
  $headers  = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
  
  $message = "<html><body><small>This is an automated message from the " .
    "<a href=\"". getFullPath("home.php") . "\">Favorites Site</a>." .
    "</small><p>" . $message . "</body></html>";

  if (mail($to, $subject, str_replace("<br>", "<br>\r\n", str_replace(". ", ". \r\n", $message)), $from . $headers)){
    return 1;
  }
  else{
    return 0; // an error has occurred
  }
}

/* useful function to get rid of the auto escapes either the server is doing
 * or the browser is doing.  Should use this function whenever we get text from
 * the user though we should call convertString if we are going to put the text
 * into the database
 */
function cleanString($str){
  return str_replace("\\'", "'", str_replace("\\\\", "\\", str_replace("\\\"", "\"", $str)));
}

/* convertString - a primitive method of form validation.  This needs more work
 * 
 * $str : the string to convert
 */
function convertString($str){
  // the server I am hosting this on automatically escapes single and
  // double quotes.  Since this may not be the case on all servers, I
  // explictily remove the escaping
  $str = cleanString($str);

  return trim(mysql_escape_string(str_replace(chr(34), "&quot;", (str_replace("\r\n", "<br>" , $str)))));
}

function convertString_no_escape($str){
  return trim(str_replace(chr(34), "&quot;", (str_replace("\r\n", "<br>" , $str))));
}


/* printList2
 *
 * $recip : The user whos list to print
 * $buyerUserId : The user requesting to view recip's list
 * $name : The full name of $recip
 * $pretty : if equal to 0 then $userid wants printer friendly version
 * $displayPurchases : if equal to 0 then don't indicate if item has 
 *   been purchased
 */
function printList2($recip, $buyerUserId, $name, $pretty, $displayPurchases = 1){

  // first need to determine if $buyeruserid has read only access
  $query = "select readOnly from viewList where pid='" . $recip . "' and viewer='" . $buyerUserId . "'";

  $rs = mysql_query($query) or die("Could not query: " . mysql_error());

  $readOnly = 1;

  if($row = mysql_fetch_assoc($rs)){
    $readOnly = $row["readOnly"];
  }

  if($readOnly)
    print "<h2>You have Read Only Access to this list</h2>";


  // ok, I really hate doing this query but it is the only way that I can
  // determine if recip has starred an item or not
  $query = "select addStar from categories, items where addStar='1' and categories.cid=items.cid and userid='" . $recip . "' limit  1";

  $rs = mysql_query($query) or die("Could not query: " . mysql_error());

  if($row = mysql_fetch_assoc($rs)){
    print "<table class='yellowBorder'><tr><td>A <img src=\"../images/star.gif\" lowsrc=\"../images/space.GIF\"> indicates an item " . $name . " wants most</td></tr></table>&nbsp;";
  }
  // end of the hate


  // Now find all categories associated with $recip
  $query = "select * from categories where userid ='" . $recip . "' order by catSortOrder";
  
  $rs = mysql_query($query) or die("Could not query: " . mysql_error());

  while($row = mysql_fetch_assoc($rs)){
    printCategory($row, $name, $pretty, $displayPurchases, $readOnly, 0, -1, -1);
  }

}

/* printModifyList
 *
 * $userid : the owner of the list to print
 */
function printModifyList($userid){

  $query = "select * from categories where catSortOrder > -1 and userid ='" . $userid . "' order by catSortOrder";
  
  $rs = mysql_query($query) or die("Could not query: " . mysql_error());
  
  $val = "";
  
  $i = 0;
  $last = mysql_num_rows($rs);

  while($row = mysql_fetch_assoc($rs)){
    
    printCategory($row, $_SESSION["fullname"], 1, 0, 0, 1, $i, $last);
    $i++;
  }
}

/* printCategory
 *   if displayPurchases = 0 then the user is modifying his/her list 
 */
function printCategory($row, $name, $pretty, $displayPurchases, $readOnly, $modifyList, $i, $last, $printConsider = 0){

  // handle any special categories first
  if($row["catSortOrder"] < 0){
    if($row["catSortOrder"] == -1000){
      if($row["catSubDescription"] != ""){
        print "\n<table width=100% border=0 cellpadding=0 cellspacing=0>";
        print "<tr><td valign=top>\n";
        print "<table cellpadding=0 cellspacing=0><tr><td>\n";
        print $row["catSubDescription"];// . "<br>&nbsp;\n";
        print "</td></tr><tr><td>&nbsp;</td></tr></table>\n";
        print "</td></tr></table>\n";
      }
      return;
    }
    if($printConsider == 0 and $row["catSortOrder"] == -10000)
      return;
  }

  print "\n<table width=100% border=0 cellpadding=0 cellspacing=0><tr>\n";

  if($modifyList){
    print "<td valign=top style=\"border-bottom: 1px solid rgb(128,255,128);\">";
  }
  else{
    print "<td valign=top>";
  }

  print "\n<table cellpadding=0 cellspacing=0>";
  print "<tr>";

  if($modifyList and $row["catSortOrder"] != -10000){
  ?>
 <td NOWRAP class=goldenRod valign=top>
 <a class="menuLink" href="editCategory.php?cso=<?php echo $row["catSortOrder"] ?>&cid=<?php echo $row["cid"] ?>&cname=<?php echo $row["name"] ?>">[edit]</a>
 <a class="menuLinkRed" title="Click this to delete the category" href="deleteCategory.php?cso=<?php echo $row["catSortOrder"] ?>&cid=<?php echo $row["cid"] ?>&cname=<?php echo $row["name"] ?>&referrer=modifyList.php">[del]</a>
<?php 
    if ($last == 1){
      print "<img width=26px height=26px src=\"../images/space.GIF\">";
    }
    else{
      if ($i != 0){
        print "<a href=\"moveCategory.php?dir=up&cid=" . $row["cid"] . "&cso=" . $row["catSortOrder"] . "\"><img width=13px height=13px border=0 src=\"../images/up_arrow_lightBlue.gif\" title=\"Click this arrow to move the category up\" lowsrc=\"../images/space.GIF\"></a>";
      }
      
      if($i != $last - 1){
        print "<a href=\"moveCategory.php?dir=down&cid=" . $row["cid"] . "&cso=" . $row["catSortOrder"] . "\"><img width=13px height=13px border=0 src=\"../images/down_arrow_lightBlue.gif\" title=\"Click this arrow to move the category down\" lowsrc=\"../images/space.GIF\"></a>";
        if($i == 0){
          print "<img width=13px height=13px src=\"../images/space.GIF\">";
        }
      }
      else{
        print "<img width=13px height=13px src=\"../images/space.GIF\">";
      }
    }
      
 print "</td><td>";

  }
  else{
    print "\n<tr><td>\n";
  }
  
  if($row["name"] != ""){
    $text = "<b>" . $row["name"] . "</b> ";
  }

  if($row["description"] != ""){
    $text .= $row["description"] . ' ';
  }
  
  if($row["linkname"] != ""){
    $text .= " - <a href='" . $row["linkurl"] . "'>" . $row["linkname"] . "</a>";
  }
  $len = strlen($text);

  if($text != ""){
    print $text;
  }
  

  if($row["catSubDescription"] != ""){
    if($text != ""){
      print "<br>";
    }
    print $row["catSubDescription"];// . "<br>&nbsp;";
  }
  
  print "\n</td></tr>\n";
  print "</table>\n";
  
  print "</td></tr></table>\n";

  print "<table cellpadding=0 cellspacing=0><tr>";


  // Now begin iterating through items in this category
  $query = "select * from items where cid=" . $row["cid"] . 
    " order by itemSortOrder";

  $rs2 = mysql_query($query) or die("Could not query: " . mysql_error());

  $j = 0; 
  $last2 = mysql_num_rows($rs2);

  // before we print out the item details, need to display either a checkbox
  // or a drop down box to allow people to purchase the item
  while($row2 = mysql_fetch_assoc($rs2)){
    print "\n<tr>\n";
    
    if($modifyList){
?>
     <td NOWRAP class="goldenRod" valign="top">
     <a class="menuLink" href="editItem.php?iso=<?php echo $row2["itemSortOrder"] ?>&iid=<?php echo $row2["iid"] ?>&cid=<?php echo $row["cid"] ?>&cname=<?php echo $row["name"] ?>">[edit]</a>
     <a class="menuLinkRed" title="Click this to delete the item" href="deleteItem.php?iso=<?php echo $row2["itemSortOrder"] ?>&iid=<?php echo $row2["iid"] ?>&cid=<?php echo $row["cid"] ?>&cname=<?php echo $row["name"] ?>&referrer=modifyList.php">[del]</a>
<?php 

     if($last2 == 1){
       print "<img width=26px height=13px src=\"../images/space.GIF\">";
     }
     else{
       if ($j != 0){
         print "<a href=\"moveItem.php?dir=up&cid=" . $row["cid"] . "&iid=" . $row2["iid"] . "&iso=" . $row2["itemSortOrder"] . "\"><img width=13px height=13px border=0 src=\"../images/up_arrow_lyellow.gif\" title=\"Click this arrow to move the item up\" lowsrc=\"../images/space.GIF\"></a>";
       }
       
       if($j != $last2 - 1){
         print "<a href=\"moveItem.php?dir=down&cid=" . $row["cid"] . "&iid=" . $row2["iid"] . "&iso=" . $row2["itemSortOrder"] . "\"><img width=13px height=13px border=0 src=\"../images/down_arrow_lyellow.gif\" title=\"Click this arrow to move the item down\" lowsrc=\"../images/space.GIF\"></a>";
         if($j == 0){
           print "<img width=13px height=13px src=\"../images/space.GIF\">";
         }
       }
       else{
         print "<img width=13px height=13px src=\"../images/space.GIF\">";
       }
     }


     print "</td>";
    }

    $bought = 0;
    $quantity = 0;
    $iid = $row2["iid"]; 
    
    if($row2["allowCheck"] == "true"){

      if(modifyList == 0){
        $query = "SELECT sum(quantity) as numBought FROM purchaseHistory WHERE iid=" . $iid . " GROUP BY iid";
        $rs = mysql_query($query) or die("Could not query: " . mysql_error());
        
        if($sumRow = mysql_fetch_assoc($rs)){
          $bought = $sumRow["numBought"];
        }  
      }

      print "<td align=right valign=top width=1px>\n";
      $quantity = $row2["quantity"];
      
      if($quantity > 1){
        // person wants more than one of the items so use a drop down box 
        // instead of a checkbox.

        if($displayPurchases == 0) // not displaying purchases so reset bought
          $bought = 0;
        
        if(($quantity - $bought) > 0 and !$readOnly){
          // if the person's desired quantity has not been reached yet, display
          // a drop-down

          $titley = "";
          if($displayPurchases == 1)
            $titley = "title=\"Select the number you have purchased\"";

          print "<select $titley  name=" . $iid . ">";
          for($i = 0; $i <= $quantity - $bought; $i++){
            print "<option value='" . $i . "'>" . $i . "</option>";
          }
        }
        else{ // Everything already bought, display disabled drop down
          print "<select disabled>";
        }
        print "</select>";
      }
      else{
        // person only wants one of the item so display a checkbox
        if($bought > 0){
          // disable the box if the item has already been bought
          $val = "checked=true disabled=true";
        }
        else{
          if($readOnly)
            $val = "disabled=true";
          else
            $val = "";
        }
        
        if($displayPurchases == 0)
          $val = "";

        $titley = "";
        if($displayPurchases == 1)
          $titley = "title=\"Click here if you purchased this item\"";

        print "<input $titley type='checkbox' name='" . $iid . "' " . $val  . ">";
      }
      print "</td><td>";
    }
    else{
      // can't check this item off so don't display checkbox or drop down
      print "<td colspan=2>";
    }
    
    printItem($row2, $pretty, $name, $quantity, $bought);
    
    print "\n</td></tr>\n";       
    $j++;
  }
  if($modifyList){
?>
</table>

<table width=100% cellspacing=0 cellpadding=0>
<tr><td class="goldenRod" align="center">
<b>
<a class="menuLink" href="addItem.php?cid=<?php echo $row["cid"] ?>&cname=<?php echo $row["name"] ?>">Add New Item to <u><?php echo $row["name"] ?></u></a></b>
</td></tr>
<tr><td>&nbsp;</td></tr>
<?php
  }
  else{
    print "<tr><td>&nbsp;</td></tr>"; // blank line
  }
  print "</table>";
}



function printItem($row2, $pretty, $name, $quantity, $bought){

  $text = "";

  $dash = 0;

  if($row2["addStar"] == '1'){
    $text = "<img src=\"../images/star.gif\" lowsrc=\"../images/space.GIF\">";
  }

  if($row2["title"] != ""){  
    $text .= "<i>" . $row2["title"] . "</i>";
    $dash = 1;
  }
  
  if($row2["description"] != ""){
    if($dash)
      $text .= " - ";
    $text .= $row2["description"];
    $dash = 1;
  }
  
  if($row2["price"] != "0.00"){
    if($dash)
      $text .= " - ";

    $queryPrice = "SELECT iid FROM itemPriceHistory WHERE iid='" . $row2["iid"] . "' LIMIT 1";
    $rsPrice = mysql_query($queryPrice) or die("Could not query: " . mysql_error());
        
    if($priceHist = mysql_fetch_assoc($rsPrice)){
      $text .= "<a target=_blank class=menuLinkRedDashed onclick=\"getPrice(" . $row2["iid"] . ", this); return false;\" href=../priceHistory.php?iid=" . $row2["iid"] . ">$" . $row2["price"] . "</a>";
    }  
    else{
      $text .= "$" . $row2["price"];
    }
    $dash = 1;
  }
  
  if($row2["link1"] != ""){
    if($dash)
      $text .= " - ";
    $text .= "<a target='_blank' href='" . $row2["link1url"] . "'>" . $row2["link1"] . "</a>";
  }

  print $text;  

  //if($row2["userid"] != null and 
  //   $row2["userid"] == $buyerUserId and $pretty == 0){
  //  print " <b><a class=menuLinkRed href=\"viewLog.php?recip=" . $recip . "&name=" . $name . "\">[Undo]</a></b>";
  //}
  
  $subdesc = $row2["subdesc"];
  $link2 = $row2["link2"];
  $link2url = $row2["link2url"];
  $link3 = $row2["link3"];
  $link3url = $row2["link3url"];
  
  
  if($subdesc != "" or link2 != "" or link3 != "" or $quantity > 1){
    print "<table border=0>";
    
    if($subdesc != ""){
      print "<tr><td width='15px'>&nbsp;</td><td>";
      print $subdesc;
      print "</td></tr>";
    }
    
    if($link2 != ""){
      print "<tr><td width='15px'>&nbsp;</td><td>";
      print "<a target='_blank' href='" . $link2url . "'>" . $link2 ."</a></td></tr>";
      
      if($link3 != ""){
        print "<tr><td width='15px'>&nbsp;</td><td>";
        print "<a target='_blank' href='" . $link3url . "'>" . $link3 . "</a></td></tr>";
      }
    }
    
    if($quantity > 1){ 
      print "<tr><td width='15px'>&nbsp;</td><td>";
      print "<font color=red>" . $name . " wants " . $quantity . " of these, there are " . ($quantity - $bought) . " left to be purchased";
/*       print $bought == 1 ? "has" : "have"; */
/*       print " been purchased";   */
/*       if($pretty == 0 and $quantity > $bought){ */
/*         print "<br>Use the drop down box to indicate how many you have bought"; */
/*       } */
      print "</font></td></tr>";
    }
    print "</table>";
  }
  else{
    print "<br>";
  }
}

/* deleteItem deletes $iid and any purchaseHistory entries for $iid.  
 * If $iid has been bought and the item is deleted 20 before $userid's bday
 * or christmas, an email will be sent to the buyer
 */
function deleteItem($iid, $userid, $fullname){
  $sendMail = 0;
  
  $query = "select itemSortOrder as iso, categories.cid as cid from items, categories where categories.cid=items.cid and iid=" . $iid . " and userid='" . $userid . "'";
  $item_rs = mysql_query($query) or die("Could not query: " . mysql_error());

  if(!($item_row = mysql_fetch_assoc($item_rs))){ 
    return "Item does not belong to you!";
  }

  // check to see if the item has been bought
  $query = "select title, items.description, email from items, purchaseHistory, people where items.iid=" . $iid . " and items.iid=purchaseHistory.iid and people.userid=purchaseHistory.userid";
  
  $rs1 = mysql_query($query) or die("Could not query: " . mysql_error());

  if(mysql_num_rows($rs1) > 0){  
    // the item has been puchased so may have to send warning email
    $sendMail = 1;
  }
  
  // delete from purchaseHistory
  $query = "delete from purchaseHistory where purchaseHistory.iid=" . $iid;
  $result = mysql_query($query) or die("Could not query: " . mysql_error());

  // delete from itemPriceHistory
  $query = "DELETE FROM itemPriceHistory WHERE itemPriceHistory.iid=" . $iid;
  $result = mysql_query($query) or die("Could not query: " . mysql_error());
  
  // delete from items
  $query = "delete from items where items.iid=" . $iid;
  $result = mysql_query($query) or die("Could not query: " . mysql_error());
  
  $query = "update items set itemSortOrder = itemSortOrder - 1 where itemSortOrder > " . 
    $item_row["iso"] . " and cid=" . $item_row["cid"];
  $result = mysql_query($query) or die("Could not query: " . mysql_error());
  
  $query = "update people set lastModDate=NOW() where userid='" . $userid . "'";
  $result = mysql_query($query) or die("Could not query: " . mysql_error());
  
  if($sendMail == 1){

    // get userid's bday and bmonth
    $query = "select bday, bmonth from people where userid='" . $userid . "'";
    $rs2 = mysql_query($query) or die("Could not query: " . mysql_error());
    
    $array = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May',
                   6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 
                   10 => 'October', 11 => 'November', 12 => 'December');
    
    if($row2 = mysql_fetch_assoc($rs2)){
      $bmonth = array_search($row2["bmonth"], $array);
      $bday = $row2["bday"];
      $d1 = mktime(0,0,0, $bmonth, $bday);
    }
    
    $diff =  ceil(($d1 - time())/(60*60*24));
    
    $d2 = mktime(0,0,0, 12, 25); // christmas
    $xmas = ceil(($d2 - time())/(60*60*24));
    
    if(($diff > 0 and $diff <= 20) or ($xmas > 0 and $xmas <= 20)){
      // less than 20 days till bday or xmas so 
      // send an email to each person who has purchased this gift
      
      while($row1 = mysql_fetch_assoc($rs1)){
        $to .= $row1["email"] .", ";
      }

      // rewind cursor so that we can get the title and description
      mysql_data_seek($rs1, 0);
      $row1 = mysql_fetch_assoc($rs1);      

      $from = "";
      $subject = $fullname . "'s WishList has been modified";
      $message = "<p><font color=indianred><b>" . $fullname .
        "</b></font> has <b>deleted</b> the following item that you have already bought <dir>" .
        "<i>" . $row1["title"] . "</i> - " . $row1["description"] . "</dir>" .
        "Remember, " . $fullname . " had no way of knowing that the item " .
        "had been purchased. This warning is sent out if a person deletes an item (which has been " .
        "purchased) from their list " .
        "within 20 days of their birthday or Christmas<br>" .
        "<br>Day until " . $fullname . "'s bday is " . $diff . "<br>Days till xmas=" . $xmas . 
        "</body></html>";
      sendEmail($to,$from,$subject,$message,0);
    } 
  }
  return "Success";
}

?>
