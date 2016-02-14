<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>

  <meta name="Description" content="Radio internetowe. Polskie radio online w super jakoœci. Kilkadziesiat radiowych kana³ów za darmo. ">
  <meta name="Keywords" content="radio, radio internetowe, radio online, rozg³oœnie radiowe, rmf, eska, zet, polskie radio, radiostacja, radio z³ote przeboje, radio disco polo">
  <meta name="revisit-after" content="1 days" >
  <META NAME="GOOGLEBOT" CONTENT="INDEX,FOLLOW">
  <META NAME="ROBOTS" CONTENT="INDEX,FOLLOW">
  <META NAME="MSNBOT" CONTENT="INDEX,FOLLOW">
  <meta name="Language" content="pl" > 
  <META NAME="distribution" CONTENT="global" >
  <meta name="copyright" content="radio internetowe radioldn.net" >
  <meta name="rating" content="general" >

  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <meta name="Author" content="www.RadioLDN.net" />
	<meta http-equiv="Expires" content="0" />
	<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
	<meta http-equiv="Cache-Control" content="post-check=0, pre-check=0" />
	<meta http-equiv="Pragma" content="no-cache" />

  <title>RADIO LDN - www.radioldn.net - polskie radio w internecie</title>

  <link rel="stylesheet" href="style.css" type="text/css" />
  
</head>
<body>

<a href="index.php"><div id="top">
</div></a>
<div id="top-shade">
<div id="top-before">
<div id="top-menu-shade">
<div id="top-menu-back">
<div id="top-menu">

<script type="text/javascript">
<!--
function playLDN() {
    window.open("http://citiradio.ovh.org/rl/panel/panel200b.html","WindowLDN","menubar=no,width=260,height=410,toolbar=no");
}
//-->
</script>

<?php

$menu = array(
					'index.php' => 'strona g&#322;&#243;wna',
					'?page=news&sub=1' => 'newsy', 
					'?page=video' => 'video', 
					'?page=music' => 'music', 
					'?page=listen' => 'jak s&#322;ucha&#263;?', 
					'?page=promo' => 'pole&#263; nas innym', 
					'?page=whoweare' => 'kim jeste&#347;my', 
					'javascript:playLDN()" return true;" title="Poluchaj Radio LDN' => 'webplayer', 
					// NIE ZMIENIAC OSTATNIEJ POZYCJI W MENU!
					'?page=contact' => 'kontakt'
				);
				
				$licz = count($menu);

foreach($menu as $key => $value) {
	if ($value == 'kontakt') {
		echo '<a href="' . $key . '">' . $value . '</a>';
	} else {
		echo '<a href="' . $key . '">' . $value . '</a>   /  ';
	}
}

?>

</div>
</div>
<div id="main">
<div id="no-ticker"></div>
<div id="main-left">

<?php
require_once('news.php');
$news = new News();
?>

<?php
echo '<div id="title" style="width: 98%;">LDN Shoutcast Messenger</div>';
$news->sayHiForm(7);
	
?>

<div id="sluchaj-w-n">
<img src="img/n_logo.gif">
<b>Uwaga!</b> Mo&#380;ecie nas s&#322;ucha&#263; tak&#380;e w cyfrowej jako&#347;ci na platformie <b>n</b> w pakiecie <b>Polskie Stacje Internetowe</b>.
</div>
<!--
<?php
echo '<div id="title" style="width: 98%;">Nasz partner</div>';
?>

<div id="nolink"><a href="http://www.kwupr.pl/" target="_blank"><img src="img/upr1.jpg" style="border: 0px;"></a></div>

-->

<?php
echo '<div id="title" style="width: 98%;">Co nowego w LDN?</div><br />';
$news->displayNewsSimple(3);
?>

</div>
<div id="main-right">

<?php

require_once('staty.php');
require('functions.php');
$obj = new Functions();

$dir = 'img/chan/';
$allimg = array_slice(scandir($dir), 2);
$lopo = $obj->openFile();
$pages = array('contact','listen','promo','history','desc','whoweare','news','video','music','chillout','discopolo','polska','disco','ukcharts','trance','live','rock','dnb','electro');
$pagesdb = 'db/pages/';

if (isset($_GET['page'])) {
	$page = $_GET['page'];
}
if (isset($_GET['detail'])) {
	$detail = $_GET['detail'];
}
if (in_array(@$page,$pages)) {
	if (in_array(@$detail,$pages) && $detail == 'history') {
		for ($j = 0; $j < count($allimg); $j++) {
			$lop = explode(", ", $lopo[$j]);
			if ($lop[2] == $page) {
				$obj->getServerInfo($lopo[$j]);
				echo '<span class="big-red">Kana&#322; ' . $lop[3] . ' - historia utwor&#243;w</span>';
				echo '<div id="hr"></div>';
				echo '<br />';
				echo '<span style="float: left; width: 190px; height: 250px;">';
				echo '<a href="?page=' . $obj->chan . '&detail=desc"><span class="channel-img" style="background: transparent url(' . $dir . $allimg[$j] . ') repeat;"></span></a></span>';
				echo '<span style="float: left;">' . winstat($obj->ip,$obj->port) . '</span>';
			}
		}
	} else if (in_array(@$detail,$pages)) {
		for ($j = 0; $j < count($allimg); $j++) {
			$lop = explode(", ", $lopo[$j]);
			if ($lop[2] == $page) {
				echo '<span class="big-red">Kana&#322; ' . $lop[3] . ' - taki tu gramy rodzaj muzyki</span>';
				if (file_exists($pagesdb . $page . '.txt')) {
					echo '<span style="float: left;">' . $obj->convert2HTML($pagesdb . $page . '.txt') . '</span>';
				} else {
					echo '<div id="hr"></div>';
					echo '<br />Wkrotce uzupelnimy...';
				}
			}
		}
	} else if ($page == 'news') {
		echo '<span class="big-red">Newsy</span>';
		echo '<div id="hr"></div>';
		echo '<br />';
		$news->allNews(8);
		//@ $news->displayNews(7);
	} else if ($page == 'video') {
		echo '<span class="big-red">Video</span>';
		echo '<div id="hr"></div>';
		echo '<br />';
		$news->allVideo(32);
	 } else if ($page == 'music') {
		echo '<span class="big-red">Music</span>';
		echo '<div id="hr"></div>';
		echo '<br />';
		$news->allMusic(20);
	} else {
		$obj->convert2HTML($pagesdb . $page . '.txt');
	}
} else {
	$banner = true;
	if ($banner) {
		if (isset($page) && ($page == 'addvid')) {
			echo '<div id="title">Dodaj video</div>';
			//echo '<br />';
			//echo 'tu dodajemy video';
			$news->addVid();
		}
	$news->displayVideo();

?>

<!--
<a href="http://osnews.pl/">
<img src="http://linuxnews.pl/stuff/banners/wiki/wikibanner02.png" style="padding-left: 40px; margin-bottom: 30px; border: 0px;"
alt="OSnews.pl: obywatelski serwis IT"
title="OSnews.pl: IT news"/>
</a>
-->
<div style="height: 14px;"></div>
<?php
	echo '<div id="title">Kanaly Radio LDN</div>';
	echo '<br />';
	
}
	for ($i = 0; $i < count($allimg); $i++) {
		$obj->getServerInfo($lopo[$i]);

?>

<span class="channel">
<a href="?page=<?php echo $obj->chan; ?>&detail=desc"><span class="channel-img" style="background: transparent url('<?php echo $dir . $allimg[$i] ?>') repeat;">
</span></a>
<span class="channel-link">
<a href="play/<?php echo $obj->chan; ?>.m3u" title="play <?php echo $obj->chan; ?>">Winamp</a> |    
<a href="play/<?php echo $obj->chan; ?>.asx" title="play <?php echo $obj->chan; ?>">WMP</a> |    
<a href="play/<?php echo $obj->chan; ?>.ram" title="play <?php echo $obj->chan; ?>">Real Player</a>
</span>
<span class="channel-gramy">
<b>Teraz gramy:</b>
<br />
<?php echo $obj->song; ?>
</span>
<span class="channel-history">
<a href="?page=<?php echo $obj->chan; ?>&detail=history">zobacz histori&#281; utwor&#243;w</a>
</span>
</span>

<?php

	}
}

?>

</div>
<div id="footer">

<script>
	var adr = 'www.radioldn.net';
	var tit = 'RadioLDN.net';
	var note = '';
	var url = 'http://elefanta.pl/member/bookmarkNewPage.action?url=' + adr + '&title=' + tit + '&bookmarkVO.notes=' + note;
	function openElefanta() {
		window.open(url,'_blank');
	}
</script>

<b>Partnerzy: </b>

<?php

$partners = array(
					'http://www.pokojwustroniu.pl/' => 'Pokoj w Ustroniu',
					'http://www.swansea.com.pl/' => 'Polacy w Swansea',
					'http://komuna.org' => 'Komuna',
					'http://www.apsik.co.uk/' => 'APSIK.CO.UK',
					'http://www.forumpolonii.co.uk/' => 'Forum Polonii',
					'http://www.gbritain.net/' => 'GBritain.net',
					'http://www.cafepl.com/' => 'Cafe PL'
				);

foreach($partners as $key => $value) {
	echo '<a href="' . $key . '" target="_blank" title="' . $value . '">' . $value . '</a>, ';
}

?>

</div>
</div>
<div id="top-menu-back">
<div id="top-menu" style="color: #c0c0c0; background: transparent url('img/stat24b.png') no-repeat 96% 50%; background-color: #000000;">
Radio LDN 2008-2009. All Rights Reserved.
</div>
</div>
</div>
</div>
</div>
<!-- (C) stat24 / Strona glowna -->
<script type="text/javascript">
<!--
document.writeln('<'+'scr'+'ipt type="text/javascript" src="http://s4.hit.stat24.com/_'+(new Date()).getTime()+'/script.js?id=ncCQzXdtd1leRFfUE5SOXpdQHUcIviLoEqZEZQT89k..h7/l=11"></'+'scr'+'ipt>');
//-->
</script>

</body>
</html>