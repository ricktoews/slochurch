IE = (navigator.appName=="Microsoft Internet Explorer" && document.all);

// Menu Parameters
bgCol="#999EC4";
bgOverColor="#7C809F";
borCol="#7C809F"; 
fontCol="#ffffff";
fontOverCol="#ffffff";
fontFam="Arial, Helvetica";
fontSiz="8pt";
menuWidth=153;
windowWidth = IE ? document.body.clientWidth : innerWidth;

menuStyle='<style type="text/css">';
menuStyle+='.menu {';
menuStyle+='position:absolute;';
menuStyle+='}';
menuStyle+='.menuText {';
menuStyle+='font-family:'+fontFam+';';
menuStyle+='font-size:'+fontSiz+';';
menuStyle+='color:'+fontCol+';';
menuStyle+='width:'+menuWidth+';';
menuStyle+='border-width:1;';
menuStyle+='border-style:solid;';
menuStyle+='border-color:'+borCol+';';
if (IE) menuStyle+='padding:3;';
menuStyle+='}';
menuStyle+='.overText {';
menuStyle+='font-family:'+fontFam+';';
menuStyle+='font-size:'+fontSiz+';';
menuStyle+='color:'+fontOverCol+';';
menuStyle+='width:'+menuWidth+';';
menuStyle+='border-width:1;';
menuStyle+='border-style:solid;';
menuStyle+='border-color:'+borCol+';';
if (IE) menuStyle+='padding:3;';
menuStyle+='}';
menuStyle+='</style>';
document.write(menuStyle);

function getX(obj) {
  var x=0;
  while (obj.offsetParent.tagName != "BODY") {
    x += obj.offsetLeft;
    obj = obj.offsetParent;
  }
  return x + obj.offsetLeft;
}


function getY(obj) {
  var y=0;
  while (obj.offsetParent.tagName != "BODY") {
    y += obj.offsetTop;
    obj = obj.offsetParent;
  }
  return y + obj.offsetTop;
}


var currentMenu=-1;
var noMenu=true;
var currentLayer=null;
function setBack() {
  if (currentLayer) currentLayer.restoreBack();
  this.style.color=fontOverCol;
  this.style.backgroundColor=bgOverColor;
  this.style.cursor="hand";
  currentLayer=this;
  if (timerID) {
    clearTimeout(timerID);
    timerID=null;
  }
}


function restoreBack() {
  this.style.color=fontCol;
  this.style.backgroundColor=bgCol;
  noMenu=true;
  if (!timerID) initHide();
}


function linkIt() {
  if (this.mLink) {
    noMenu=false;  
    location.href=this.mLink;
  }
}


function makeMenu(n) {
  var thisMenu = document.createElement("div");
  thisMenu.id = "Menu_" + n;
  with (thisMenu.style) {
    position = "absolute";
    visibility = "hidden";
    width = menuWidth;
    top = 14;
    left = 0;
  }
  document.body.appendChild(thisMenu);
  thisMenu.makeOptions=makeOptions;
  return thisMenu;
}


function makeOptions(menuName) {
  var mArray=menuArray[menuName];
  var menuItems=mArray.length/2;
  for (var i=0; i<menuItems; ++i) {
    var mText = mArray[i*2];
    var mLink = mArray[i*2+1] ? path + mArray[i*2+1] : "";
    var suffix=menuName+"_"+i;
    var temp = document.createElement("div");
    this.appendChild(temp);
    var text = document.createTextNode(mText);
    temp.id = "menuOpt" + suffix;
    with (temp.style) {
      backgroundColor = bgCol;
      fontFamily = fontFam;
      fontSize = fontSiz;
      color = fontCol;
      width = menuWidth - 8;
      borderWidth = 1;
      borderStyle = "solid";
      borderColor = borCol;
      padding = 3;
    }
    temp.appendChild(text);
    temp.mLink = mLink;
  }
  var h=0;
  for (var i=0; i<this.childNodes.length; ++i) {
    temp=this.childNodes[i];
    temp.style.cursor="pointer";
    temp.style.cursor="hand";
    temp.style.pixelTop=h;
    temp.setBack=setBack;
    temp.restoreBack=restoreBack;
    temp.onmouseover=setBack;
    temp.onmouseup=linkIt;
    h+=(temp.offsetHeight);
  }
  this.menuHeight = h + 1;
}


function IEhide() {
  menus[currentMenu].style.visibility="hidden";
}


function IEshow(m) {
  menus[m].style.top = menus[m].menuY;
  menus[m].style.left=menus[m].menuX;
  menus[m].style.visibility="visible";
}


function hierinit() {
  loaded=true;
  menus=new Array();
  for (var i = 0; i < navImgArray.length; i++) {
    if (menuArray[navImgArray[i]]) {
      menus[navImgArray[i]]=makeMenu(navImgArray[i]);
      menus[navImgArray[i]].makeOptions(navImgArray[i]);
      menus[navImgArray[i]].menuY = getY(document.getElementById("nav_"+navImgArray[i])) + 19;
      menus[navImgArray[i]].menuX = getX(document.getElementById("nav_"+navImgArray[i]));
    }
  }

  if (IE) document.onmousemove=menuCheck;
  document.onclick=checkHide;
}


function checkHide() {
  if (noMenu) {
    hide()
  }
}


timerID=null;
function initHide() {
  timerID=setTimeout("hide()", 500);
}


function hide(e) {
  if (currentMenu==-1) return;
  IEhide();
  currentMenu=-1;
}

function show(n,e) {
  if (timerID) clearTimeout(timerID);
  hide();
  var m = menuArray[n];
  if (m.length > 0) { // World Nutrition and About do not have menus.
    currentMenu=n;
    IEshow(n);
  }
}

function menuCheck(e) {
  var hit=false;
  var x=event.clientX;
  var y=event.clientY;
  var o=event.srcElement;
  while (o.tagName != "BODY" && o.tagName != "MAP" && !hit) {
    if (o.id.substring(0,7)=="menuOpt") {
      o.setBack();
      hit=true;
    }
    o=o.offsetParent;
  }
  if (!hit && currentLayer) {
    currentLayer.restoreBack();
  }
}

