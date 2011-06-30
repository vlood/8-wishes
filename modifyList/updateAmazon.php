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

include "../include/nusoap.php";

@(include "../config.php");

$mquery = "select * from items where link1url like '%amazon%'";

$mrs = mysql_query($mquery) or die("Could not query: " . mysql_error());

while($mrow = mysql_fetch_assoc($mrs)){

  $url = $mrow["link1url"];

  if(strpos($url, "amazon") != 0){
    $amazon = 1;

    $regs = Array();
    $asin = ereg("([0-9A-Za-z]{10})", $url, $regs);
    
    if($regs[1] == "")
      continue;

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
    
    $OurPrice = $items[0]['OurPrice'];
    $OurPrice = substr($OurPrice,1);

    if(!is_numeric($OurPrice)) 
      continue;
    
    $ProductName = $items[0]['ProductName'];

    if($OurPrice != $mrow["price"]){
        $iid = $mrow['iid'];

        print $ProductName . " - " . $OurPrice . "/" . $mrow["price"] . "\n";
        $update = "UPDATE items set price='$OurPrice' WHERE iid='" . $iid . "'";
        print $update . "\n\n";

        $rs = mysql_query($update) or die("Could not query: " . mysql_error());

        $update = "INSERT INTO itemPriceHistory VALUES (NOW(), $iid, " . $mrow["price"] . ")";
        print $update . "\n\n";

        $rs = mysql_query($update) or die("Could not query: " . mysql_error());
    }
  }
}
?>
