ERR = 1;
function message(type, str) {
  var intro;
  if (type == ERR) {
    intro = "Input Error";
  }
  var msg = "========================================\n" +
            intro + "\n" +
            "--------------------------------------------------------------------------------\n\n" +
            str + "\n\n" +
            "--------------------------------------------------------------------------------\n";
  alert(msg);
}


String.prototype.right = function (n) {
  if (n < this.length) {
    return this.substring (this.length - n, this.length);
  } else {
    return this;
  }
}


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


function contact() {
  var attr = "width=640,height=480,top=50,left=50,scrollbars";
  var cpopup = open("contact.php", "cpopup", attr);
}


function valNumber(f) {
  var num=f.value;
  if (/[^\d]/.test(num)) {
    message(ERR,"You must enter a number in this field.");
    f.value="";
    f.focus();
  }
}


function valSSN(fld) {
  if (!/^(\d{3})-?(\d{2})-?(\d{4})/.test(fld.value)) {
    message(ERR,"You must enter a valid SSN.");
    fld.value="";
    fld.focus();
    return false;
  }
  else {
    fld.value=RegExp.$1+"-"+RegExp.$2+"-"+RegExp.$3;
  }
}


function valEmail (fld) {
  if (fld.value.length == 0 || fld.value == "Email Address") return;
  if (!/^([a-zA-Z0-9._-]+\.)*[a-zA-Z0-9._-]+@([a-zA-Z0-9._-]+\.)*[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]{2,5}$/.test(fld.value)) {
    message(ERR,"You must enter a valid Email address.");
    fld.value="";
    fld.focus();
    return false;
  }
  return true;
}


function valZip (fld) {
  if (!/^\d{5}(-?\d{4})?$/.test(fld.value)) {
    message(ERR,"Invalid Zip Code.");
    fld.value="";
    fld.focus();
    return false;
  }
  return true;
}


function formatSSN(f) {
  var ssn=f.value;
  if (/^(\d{3})[-\s]?(\d{2})[-\s]?(\d{4})/.test(ssn)) {
    f.value=RegExp.$1+"-"+RegExp.$2+"-"+RegExp.$3;
  }
  else {
    message(ERR,"You must enter a valid SSN or Tax ID.");
    f.value="";
    f.focus();
  }
}


function formatPhone(f) {
  var phone=f.value;
  if (phone.length == 0) return;
  if (/^\((\d{3})\)\s?(\d{3})[-\.\s]?(\d{4})((\s|\s?[xX])(\d+))?$/.test(phone) ||
      /^(\d{3})[-\s.]?(\d{3})[-\.\s]?(\d{4})((\s|\s?[xX])(\d+))?$/.test(phone)) {
    var fPhone="("+RegExp.$1+") "+RegExp.$2+"-"+RegExp.$3;
    if (RegExp.$6.length > 0) fPhone += " x"+RegExp.$6;
    f.value=fPhone;
  }
  else {
    message(ERR,"You must enter a valid phone number.");
    f.value="";
    f.focus();
  }
}


function formatMoney(f) {
  var amount=f.value;
  if (/(\$\s?)?\d{1,2}(,?\d{3})*(\.\d{1,2})?/.test(amount)) {
    amount=amount.replace(/\s/g,"");
    amount=amount.replace(/\$/,"");
    amount=amount.replace(/,/,"");
    f.value="$"+amount;
  }
  else {
    message(ERR,"You must enter a valid dollar amount.");
    f.value="";
    f.focus();
  }
}


function formatDate(fld) {
  var field = fld.value;
  var errmsg = "";
  if (field.length == 0) return;
// First, check the date to make sure it's valid.
  var datetest = /^(\d{1,2})([-\/\.])(\d{1,2})\2(\d{2,4})$/;
  if (datetest.test(field)) {
    var mo = RegExp.$1;
    var da = RegExp.$3;
    var yr = RegExp.$4;
    if (1*yr < 5) { yr = 1*yr + 2000; }
    var datestr = mo + "/" + da + "/" + yr;
    var checkdate = new Date(datestr);
    var checkmo = 1 + checkdate.getMonth();
    var checkda = checkdate.getDate();
    var checkyr = checkdate.getFullYear();
    if (mo != checkmo || da != checkda) {
      errmsg = "Please enter a valid date.";
    }
  }
  else {
    errmsg = "Please enter a date (MM/DD/YYYY).";
  }
  if (errmsg.length > 0) {
    message(ERR, errmsg);
    fld.value = "";
    fld.focus();
  }
  else {
    fld.value = checkmo + "/" + checkda + "/" + checkyr;
  }
}


IE = (!document.layers);
function validate() {
  var fldList="";
  var f=document.forms[0];
  for (var i=0; i < f.elements.length; ++i) {
    if (f.elements[i].onfocus) {
      s=f.elements[i].onfocus.toString();
      if (s.indexOf("required = true") > -1) {
        var pattern=/fldDesc\s*=\s*(["'])(.*)\1/;
        pattern.test(s);
        var desc=(RegExp.$2.length > 0) ? RegExp.$2 : f.elements[i].name;
        if (f.elements[i].type=="text") {
          if (f.elements[i].value.length==0) {
            fldList+="  "+desc+"\n";
          }
          else if (IE) f.elements[i].style.backgroundColor="#ffffff";
        }
        else if (f.elements[i].type.substr(0, 6)=="select") {
          if (f.elements[i].selectedIndex==0) {
            fldList+="  "+desc+"\n";
          }
          else if (IE) f.elements[i].style.backgroundColor="#ffffff";
        }
        else if (f.elements[i].type=="textarea") {
          if (f.elements[i].value.length==0) {
            fldList+="  "+desc+"\n";
          }
          else if (IE) f.elements[i].style.backgroundColor="#ffffff";
        }
      }
    }
  }
  if (fldList.length > 0) {
    alert("Before submitting this form, please fill in the following:\n\n"+fldList);
    return false;
  }
  else {
    return true;
  }
}


function resetColor() {
  this.style.backgroundColor="#ffffff";
}

