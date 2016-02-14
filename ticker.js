var tWidth='900px';                  // width (in pixels)

var tHeight='16px';                  // height (in pixels)

var tcolour='#ffffff';               // background colour:

var moStop=true;                     // pause on mouseover (true or false)

var fontfamily = 'Verdana'; // font for content

var tSpeed=5;                        // scroll speed (1 = slow, 5 = fast)

 

// enter your ticker content here (use \/ and \' in place of / and ' respectively)

var content='Masz kogos za granica albo Polsce? Kochasz i chcesz o tym powiedziec lub po prostu masz ochote pozdrowic? Od teraz mozesz zrobic to u nas. Wystarczy, ze wyslesz nam krotkiego maila na <a href="mailto:napisz@radioldn.net" style="color: #BF0202;"><b>napisz@radioldn.net</b></a> a my szybko wstawimy go na strone, specjalnie dla Ciebie i Twoich bliskich lub znajomych ;) ';
//var content='<b>Halinka </b>[13:15]: Dla mojego kochangeo misia pysia slodkie pozdrowionka';

 

// Simple Marquee / Ticker Script

// copyright 3rd January 2006, Stephen Chapman

// permission to use this Javascript on your web page is granted

// provided that all of the below code in this script (including this

// comment) is used without any alteration

var cps=tSpeed; var aw, mq; var fsz = parseInt(tHeight) - 4; function startticker(){if (document.getElementById) {var tick = '<div style="position:relative;width:'+tWidth+';height:'+tHeight+';overflow:hidden;background-color:'+tcolour+'; margin: 0 0 20px 0;"'; if (moStop) tick += ' onmouseover="cps=0" onmouseout="cps=tSpeed"'; tick +='><div id="mq" style="position:absolute;left:0px;top:0px;font-family:'+fontfamily+';font-size:'+fsz+'px;white-space:nowrap;"><\/div><\/div>'; document.getElementById('ticker').innerHTML = tick; mq = document.getElementById("mq"); mq.style.left=(parseInt(tWidth)+10)+"px"; mq.innerHTML='<span id="tx">'+content+'<\/span>'; aw = document.getElementById("tx").offsetWidth; lefttime=setInterval("scrollticker()",50);}} function scrollticker(){mq.style.left = (parseInt(mq.style.left)>(-10 - aw)) ?parseInt(mq.style.left)-cps+"px" : parseInt(tWidth)+10+"px";} window.onload=startticker;
