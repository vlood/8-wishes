var url = "../priceHistory.php?js=true&iid="; // The server-side script

function handleHttpResponse() {
  if (http.readyState == 4) {
    results = http.responseText;
    makeDiv(results);
  }
}

var elem;

function getPrice(iid,element) {

  elem = element;

  http.open("GET", url + escape(iid), true);
  http.onreadystatechange = handleHttpResponse;
  http.send(null);

  return false;
}

function getHTTPObject() {
  var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
    } catch (e) {
      xmlhttp = false;
    }
  }
  return xmlhttp;
}
var http = getHTTPObject(); // We create the HTTP Object


function makeDiv(result)
{
  var body = document.getElementsByTagName("body")[0];
  var div = document.createElement("div");

  div.style.position = "absolute";
  div.style.top = findPosY(elem);
  div.style.left = findPosX(elem);

  div.style.margin = "-50px 0 0 -170px";

  div.style.padding = "10px";
  div.style.opacity = "1.00";
  div.style.backgroundColor = "white";

  div.style.borderBottom = "2.5px solid black";
  div.style.borderRight = "2.5px solid black";
  div.style.borderTop = "1px solid black";
  div.style.borderLeft = "1px solid black";
  div.style.textAlign = "left";
  
  body.appendChild(div);

  div.innerHTML = result;

  div.innerHTML = div.innerHTML + "<p>";


  var link=document.createElement("a");
  
  var close = document.createTextNode('close (x)')

  link.appendChild(close);

  div.appendChild(link);

  link.href="javascript:doNothing()";

  link.focus();

  link.onclick = function(e)
  {
    opacityDown(this.parentNode);
  }

  link.onblur = function(e)
  {
    opacityDown(this.parentNode);
  }

//  div.onmouseout = function(e)
//  {
//   opacityDown(this);
//  }

};

function doNothing(){}


function opacityDown(theElement)
{
  var opacity = parseFloat(theElement.style.opacity);

  if (opacity < 0.08)
  {
    theElement.parentNode.removeChild(theElement);
  }
  else
  {
    opacity -= 0.2;
    theElement.style.opacity = opacity;
    setTimeout(function(){opacityDown(theElement);}, 50);
  }
                             
  return true;
};




function verticalAlign(theElement, pixelSize)
{
  theElement.parentNode.style.top = document.documentElement.scrollTop + window.innerHeight/2 + "px";
  theElement.style.fontSize = pixelSize + "px";
  theElement.style.lineHeight = pixelSize + "px";
  
  var height = theElement.clientHeight;

  theElement.style.marginTop = -parseInt(height / 2) + "px";

  return true;
};


function findPosX(obj)
{
  var curleft = 0;
  if (obj.offsetParent)
  {
    while (obj.offsetParent)
    {
      curleft += obj.offsetLeft
      obj = obj.offsetParent;
    }
  }
 else if (obj.x)
   curleft += obj.x;
 return curleft;
}

function findPosY(obj)
{
  var curtop = 0;
  if (obj.offsetParent)
  {
    while (obj.offsetParent)
    {
       curtop += obj.offsetTop
       obj = obj.offsetParent;
    }
  }
  else if (obj.y)
    curtop += obj.y;
  return curtop;
}
