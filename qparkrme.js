function resize(){
	//document.getElementById("debug").innerHTML = window.orientation;
	//alert("Orientation: "+window.orientation+"\n"+pageWidth());
	if(window.orientation == 90 || window.orientation == -90){
		document.getElementById("image").width="480";
	}else{
		if(window.orientation == 0 || window.orientation == 180){
			document.getElementById("image").width="320";
		}
	}
}
function sendhome() { window.location = "http://q.parkr.me"; }
function pageWidth() {return window.innerWidth != null? window.innerWidth: document.body != null? document.body.clientWidth:null;}
function pageHeight() {return window.innerHeight != null? window.innerHeight: document.body != null? document.body.clientHeight:null;}
function repositionBg(){
	$("#bgimg").css('position', 'absolute');
	$("#bgimg").css('left', $("#container").offset().left-91);
	$("#bgimg").css('top', 0);
}
/**
 * Returns the absolute X and Y positions of an object.
 * @param {HTMLObject} obj HTML Object.
 * @return {Object} Returns an accessor with .x and .y values.
 */
function getXY(obj)
{
  var curleft = 0;
  var curtop = obj.offsetHeight + 5;
  var border;
  if (obj.offsetParent)
  {
    do
    {
      // XXX: If the element is position: relative we have to add borderWidth
      if (getStyle(obj, 'position') == 'relative')
      {
        if (border = _pub.getStyle(obj, 'border-top-width')) curtop += parseInt(border);
        if (border = _pub.getStyle(obj, 'border-left-width')) curleft += parseInt(border);
      }
      curleft += obj.offsetLeft;
      curtop += obj.offsetTop;
    }
    while (obj = obj.offsetParent)
  }
  else if (obj.x)
  {
    curleft += obj.x;
    curtop += obj.y;
  }
  return {'x': curleft, 'y': curtop};
}
/**
 * Returns the specified computed style on an object.
 * @param {HTMLObject} obj HTML Object
 * @param {String} styleProp Property name.
 * @return {Mixed} Computed style on object.
 */
function getStyle(obj, styleProp)
{
  if (obj.currentStyle)
    return obj.currentStyle[styleProp];
  else if (window.getComputedStyle)
    return document.defaultView.getComputedStyle(obj,null).getPropertyValue(styleProp);
}