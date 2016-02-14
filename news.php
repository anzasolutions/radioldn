<?php

class News {

	public $txt;
	public $title;
	public $file = 'db/pages/news.txt';
	public $viddb = 'db/pages/vid.txt';
	public $viddb2 = 'db/pages/vid2.txt';
	public $musdb = 'db/pages/music.txt';
	public $msg;
	public $usr;

	public function submitNews() {
		echo '<form action="news.php" method="post">
			<p>
			<input type="text" name="title" size="100" /><br />
			<textarea name="txt" cols="75" rows="10"></textarea>
			</p><p>
			<input type="submit" value="Submit" />
			<input type="reset" name="Reset" />
			</form><br><br>';
		$news = $_POST['txt'];
		$title = $_POST['title'];
		if ($news == '' || $title == '') {
			echo('ERROR: brak tytulu i newsa!<br /><br />');
			$this->displayNews();
			die();
		} else {
			$this->txt = $news;
			$this->title = $title;
		}
	}


	public function getStringBetween($content,$start,$end) {
		$r = explode($start, $content);
		if (isset($r[1])){
			$r = explode($end, $r[1]);
			return $r[0];
		}
		return false;
	}
	
	public function displayNews($number) {
		$pl = array('�','�','�','�','�','�','�','�','�','�','�','�','�','�');
		$utf = array('&#322;','&#263;','&#378;','&#380;','&#281;','&#261;','&#242;','&#379;','&#377;','&#262;','&#321;','&#243;','&#324;','&#347;');
		$readfile = array_reverse(file($this->file));
		if (!isset($number)) {
			$number = count($readfile);
		} else if ($number > count($readfile)) {
			$number = $number - ($number - count($readfile));		
		} else if ($number < count($readfile)) {
			count($readfile);
			$divider = round(count($readfile) / $number, 0);
			if (count($readfile) % $divider != 0) {
				$divider = $divider + 1;
			}
			if (isset($_GET['sub']) && ($_GET['sub'] <= $divider)) {
				$sub = $_GET['sub'];
			} else if (isset($_GET['sub']) && ($_GET['sub'] > $divider)) {
				die('Page doesn\'t exist!');
			}
			$readfile = array_chunk($readfile,$number);
		}
		for ($i = 0; $i < $number; $i++) {
			if (!isset($_GET['sub'])) {
				$roz = explode(';; ',$readfile[0][$i]);
			} else {
				$coulines = count($readfile[$sub-1]);
				if ($coulines < ($number + 1))
				$number = $coulines;
				$roz = explode(';; ',$readfile[$sub-1][$i]);
			}
			$content = stripcslashes(str_replace('�','-',str_replace('[/b]','</b>',str_replace('[b]','<b>',str_replace('/"','"',str_replace('[/link]','"></a>',str_replace('[link]','<a href="http://',str_replace('http://','',$roz[2]))))))));
			$takelink = $this->getStringBetween($content,'<a href="http://','"></a>');
			$content = str_replace('<a href="http://' . $takelink . '"></a>','<a href="http://' . $takelink . '" title="' . $takelink . '" target="_blank">' . $takelink . '</a>',$content);
			for ($j = 0; $j < count($pl); $j++) {
				$content = str_replace($pl[$j] , $utf[$j] ,$content);
			}
			echo '<span class="big-red">' . $roz[0] . '</span><br />';
			echo '<span class="small-red">( ' . $this->formatDate($roz[1]) . ' )</span><br />';
			echo nl2br($content) . '<br />';			
		}
		if ($divider > 1) {
			for ($k > 1; $k <= $divider; $k++) {
				if ($k == $sub) {
					echo $k;
				} else {
					echo ' <a href="?page=news&sub=' .  $k . '">' . $k . '</a> ';
				}
			}
		}
	}
	
	public function displayNewsSimple($number) {
		$pl = array('�','�','�','�','�','�','�','�','�','�','�','�','�','�');
		$utf = array('&#322;','&#263;','&#378;','&#380;','&#281;','&#261;','&#242;','&#379;','&#377;','&#262;','&#321;','&#243;','&#324;','&#347;');
		$readfile = array_reverse(file($this->file));
		if (!isset($number)) {
			$number = count($readfile);
		} else {
			if ($number > count($readfile)) {
				$number = $number - ($number - count($readfile));
			}
		}
		for ($i = 0; $i < $number; $i++) {
			$roz = explode(';; ',$readfile[$i]);
			$content = stripcslashes(str_replace('�','-',str_replace('[/b]','</b>',str_replace('[b]','<b>',str_replace('/"','"',str_replace('[/link]','"></a>',str_replace('[link]','<a href="http://',str_replace('http://','',$roz[2]))))))));
			$takelink = $this->getStringBetween($content,'<a href="http://','"></a>');
			$content = str_replace('<a href="http://' . $takelink . '"></a>','<a href="http://' . $takelink . '" title="' . $takelink . '" target="_blank">' . $takelink . '</a>',$content);
			for ($j = 0; $j < count($pl); $j++) {
				$content = str_replace($pl[$j] , $utf[$j] ,$content);
			}
			echo '<span class="big-red">' . $roz[0] . '</span><br />';
			echo '<span class="small-red">( ' . $this->formatDate($roz[1]) . ' )</span><br />';
			echo '<span style="background: transparent url(\'img/user.png\') no-repeat center left; align: left; border: 0; margin-top: 10px; width: 30px; height: 30px;"> </span>' . nl2br($content) . '<br />';
		}
	}
	
	public function formatDate($date) {
		$months = array('styczen','luty','marzec','kwiecien','maj','czerwiec','lipiec','sierpien','wrzesien','pazdziernik','listopad','grudzien');
		$tmpDate = explode('-',$date);
		$tmpHour = explode(', ',$tmpDate[2]);
		$m = $months[$tmpDate[1]-1];
		$newDate = $tmpHour[0] . ' ' . $m . ' ' . $tmpDate[0] . ', ' . $tmpHour[1];
		return $newDate;
	}
	
	public function convert2PL() {
		$pl = array('&#263;');
		
	}
	
	public function displayVideo() {
		$arrayTen = array();
		$readvid = array_reverse(file($this->viddb));
		for ($i = 0; $i < 10; $i++) {
			$arrayTen[$i] = $readvid[$i];
		}
		
		$explvid = explode(';; ',$arrayTen[array_rand($arrayTen)]);
		if (strlen($explvid[0]) > 46) {
			$explvid[0] = substr($explvid[0], 0, 46) . '...';
			
		}
		echo '<div id="title"><span style="width: 128px; float: left;">Top Ten Video LDN: </span><span class="red" style="float: left;"><a href="' . $explvid[1] . '" target="_blank" style="border: 0">' . utf8_encode($explvid[0]) . '</a></span> <span class="red" style="width: 80px; float: right; text-align: right; padding-right: 10px;"><a href="?page=addvid" style="border: 0">dodaj video</a></span></div>';
		echo '<br />';
		if ($this->getStringBetween($explvid[1],'http://','/') == ('www.youtube.com' || 'youtube.com')) {
			$this->getStringBetween($explvid[1],'http://','/');
			$explvid[1] = str_replace('/watch?v=','/v/',$explvid[1]);
			echo '<object width="425" height="344"><param name="movie" value="' . $explvid[1] . '&fmt=18&color1=0xb1b1b1&color2=0xcfcfcf&feature=player_embedded&fs=1&fmt=18"></param><param name="allowFullScreen" value="true"></param><embed src="' . $explvid[1] . '&fmt=18&color1=0xb1b1b1&color2=0xcfcfcf&feature=player_embedded&fs=1&fmt=18" type="application/x-shockwave-flash" allowfullscreen="true" width="560" height="340"></embed></object>';
		}
	}
	
	public function allVideo($displayNumber) {
		$readFile = array_reverse(file($this->viddb));
		$number = count($readFile);
		$divideMusic = array_chunk($readFile, $displayNumber);
		$pageNumber = count($divideMusic);
		$line;
		$subPage;
		
		//set value of subpage
		if (isset($_GET['sub']) && !empty($_GET['sub'])) {
			$subPage = $_GET['sub'];
		} else {
			$subPage = 1;
		}
		
		//display main video
		if (isset($_GET['play']) && !empty($_GET['play'])) {
			echo '<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/' . $_GET['play'] . '&fmt=18&color1=0xb1b1b1&color2=0xcfcfcf&feature=player_embedded&fs=1&fmt=18&autoplay=1"></param><param name="allowFullScreen" value="true"></param><embed src="http://www.youtube.com/v/' . $_GET['play'] . '&fmt=18&color1=0xb1b1b1&color2=0xcfcfcf&feature=player_embedded&fs=1&fmt=18&autoplay=1" type="application/x-shockwave-flash" allowfullscreen="true" width="560" height="340"></embed></object>';
			echo '<br />';
			echo '<br />';
		} else {
			$line = explode(';; ', $divideMusic[$subPage - 1][0]);
			//$title = $line[0];
			$link = $line[1];
			$isYouTube = strpos($link, 'youtube.com');
			if ($isYouTube) {
				$link = str_replace('/watch?v=','/v/',$link);
				echo '<object width="425" height="344"><param name="movie" value="' . $link . '&fmt=18&color1=0xb1b1b1&color2=0xcfcfcf&feature=player_embedded&fs=1&fmt=18"></param><param name="allowFullScreen" value="true"></param><embed src="' . $link . '&fmt=18&color1=0xb1b1b1&color2=0xcfcfcf&feature=player_embedded&fs=1&fmt=18" type="application/x-shockwave-flash" allowfullscreen="true" width="560" height="340"></embed></object>';
				echo '<br />';
				echo '<br />';
			}
		}
		
		//display content on particular subpage
		echo '<div id="title"><span style="width: 136px; float: left;">Pozostale video (' . $number . ')</span><span class="red" style="width: 80px; float: right; text-align: right; padding-right: 10px;"><a href="?page=addvid" style="border: 0">dodaj video</a></span></div>';
		echo '<br />';
		for ($i = 0; $i < count($divideMusic[$subPage - 1]); $i++) {
			$line = explode(';; ', $divideMusic[$subPage - 1][$i]);
			$title = $line[0];
			$link = $line[1];
			$isYouTube = strpos($link, 'youtube.com');
			if ($isYouTube) {
				$link = str_replace("\r\n", '', str_replace('www.youtube.com/watch?v=','i.ytimg.com/vi/',$link));
				$id = $link;
				$id = str_replace('http://i.ytimg.com/vi/', '', $id);
				echo '<a href="?page=' . $_GET['page'] . '&sub=' . $subPage . '&play=' . $id . '" style="border: 0;" title="' . utf8_encode($title) . '"><img src="' . $link . '/default.jpg" width="120px" height="90px" style="margin: 0 10px 10px 10px;"></a>';
			}
		}
		
		//display page numbers at the bottom
		echo '<div id="hr"></div>';
		echo '<br />';
		echo '<div style="text-align: center;">';
		$j = 1;
		while ($j <= $pageNumber) {
			if ($j == $subPage) {
				echo $j . ' ';
			} else {
				echo '<a href="?page=' . $_GET['page'] . '&sub=' . $j . '">' . $j . '</a> ';
			}
			$j++;
		}
		echo '</div>';
	}
	
	public function allMusic($displayNumber) {
		$readFile = array_reverse(file($this->musdb));
		$number = count($readFile);
		$divideMusic = array_chunk($readFile, $displayNumber);
		$pageNumber = count($divideMusic);
		$line;
		$subPage;
		
		//set value of subpage
		if (isset($_GET['sub']) && !empty($_GET['sub'])) {
			$subPage = $_GET['sub'];
		} else {
			$subPage = 1;
		}
		
		//display content on particular subpage
		for ($i = 0; $i < count($divideMusic[$subPage - 1]); $i++) {
			$line = explode(';;', $divideMusic[$subPage - 1][$i]);
			$title = $line[0];
			$link = $line[1];
			echo '<div id="link"><a href="' . $link . '" title="' . $title . '" target="_blank">' . $title . '</a><br /><br />';
			echo '<embed wmode="transparent" src="http://beemp3.com/player/player.swf" quality="high" bgcolor="#ffffff" width="290" height="24" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="playerID=1&bg=0xDDDDDD&leftbg=0x000000&lefticon=0xF2F2F2&rightbg=0x959595&rightbghover=0xBE0202&righticon=0xF2F2F2&righticonhover=0xFFFFFF&text=BE0202&slider=0x000000&track=0xFFFFFF&border=0xFFFFFF&loader=0xBE0202&soundFile=' . $link . '"></embed>';
			echo '<br /><br /></div>';
		}
		
		//display page numbers at the bottom
		echo '<div id="hr"></div>';
		echo '<br />';
		echo '<div style="text-align: center;">';
		$j = 1;
		while ($j <= $pageNumber) {
			if ($j == $subPage) {
				echo $j . ' ';
			} else {
				echo '<a href="?page=' . $_GET['page'] . '&sub=' . $j . '">' . $j . '</a> ';
			}
			$j++;
		}
		echo '</div>';
	}
	
	public function allNews($displayNumber) {
		$readFile = array_reverse(file($this->file));
		$number = count($readFile);
		$divideMusic = array_chunk($readFile, $displayNumber);
		$pageNumber = count($divideMusic);
		$line;
		$subPage;
		
		//set value of subpage
		if (isset($_GET['sub']) && !empty($_GET['sub'])) {
			$subPage = $_GET['sub'];
		} else {
			$subPage = 1;
		}
		
		//display content on particular subpage
		$pl = array('�','�','�','�','�','�','�','�','�','�','�','�','�','�');
		$utf = array('&#322;','&#263;','&#378;','&#380;','&#281;','&#261;','&#242;','&#379;','&#377;','&#262;','&#321;','&#243;','&#324;','&#347;');
		for ($i = 0; $i < count($divideMusic[$subPage - 1]); $i++) {
			$line = explode(';;', $divideMusic[$subPage - 1][$i]);
			$title = $line[0];
			$date = $line[1];
			$text = $line[2];
			for ($j = 0; $j < count($pl); $j++) {
				$title = str_replace($pl[$j] , $utf[$j] ,$title);
				$text = str_replace($pl[$j] , $utf[$j] ,$text);
			}
			echo '<span class="big-red">' . $title . '</span><br />';
			echo '<span class="small-red">( ' . $this->formatDate($date) . ' )</span><br />';
			echo '<span style="background: transparent url(\'img/user.png\') no-repeat center left; align: left; border: 0; margin-top: 10px; width: 30px; height: 30px;"> </span>' . nl2br($text) . '<br />';
		}
		
		//display page numbers at the bottom
		echo '<div id="hr"></div>';
		echo '<br />';
		echo '<div style="text-align: center;">';
		$j = 1;
		while ($j <= $pageNumber) {
			if ($j == $subPage) {
				echo $j . ' ';
			} else {
				echo '<a href="?page=' . $_GET['page'] . '&sub=' . $j . '">' . $j . '</a> ';
			}
			$j++;
		}
		echo '</div>';
	}
	
	public function displayYT($word) {
		$word = str_replace(' ', '%20', $word);
		$url = 'http://gdata.youtube.com/feeds/base/videos?q=' . $word . '&client=ytapi-youtube-search&alt=rss&v=2';
		$ytString = str_replace("\n", '', str_replace('&gt;', '>', str_replace('&lt;', '<', file_get_contents($url))));
		$ar = explode('<img alt=', $ytString);
		$ar2 = array();
		for ($i = 0; $i < count($ar); $i++) {
			$ar2[$i] = $this->getStringBetween($ar[$i], '<a href="http://www.youtube.com/watch?v=' ,'">') . "\n";
			$ar2[$i] .= $this->getStringBetween($ar[$i], '<title>' ,'</title>');
		}
		$div = explode("\n", $ar2[array_rand($ar2)]);
		$yt = $div[0];
		$yt2 = $div[1];
		echo '<span class="big-red">' . $yt2 . '</span><br /><br />';
		echo '<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/' . $yt . '&color1=0xb1b1b1&color2=0xcfcfcf&feature=player_embedded&fs=1"></param><param name="allowFullScreen" value="true"></param><embed src="http://www.youtube.com/v/' . $yt . '&color1=0xb1b1b1&color2=0xcfcfcf&feature=player_embedded&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" width="100%"></embed></object>';
	}
	
	public function sayHiForm($number) {
		$db = 'db/pages/sayhi.txt';
		$sep = ';;';
		$bluzgi = array('chuj', 'kurwa', 'pizda', 'suka', 'wyjebany', 'pierdolony', 'zajebany', 'kutas', 'skurwiel');
		if (isset($_POST['msg']) && isset($_POST['usr'])) {
			$msg = trim($_POST['msg'], "\t\n ");
			for ($i = 0; $i < count($bluzgi); $i++) {
				$msg = str_replace($bluzgi[$i], '***', $msg);
			}
			$usr = $_POST['usr'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$date = date("Y-n-d");
			$time = date("H:i");
			$content = $usr . $sep . $ip . $sep . $date . $sep . $time . $sep . $msg;
			if (empty($usr) || empty($msg) || ($msg == 'Wpisz wiadomosc do 100 znakow') || ($usr == 'Wpisz swoje imie albo nicka')) {
				echo '<div id="alert">You must fill all fields</div>';
			} else {
				$read = array_reverse(file($db));
				$exp = explode($sep, $read[0]);
				if (($exp[1] == $ip) && $this->processDate($exp[2], $exp[3])) {
					
				} else  {
					if (file_exists($db)) {
						file_put_contents($db, $content . "\n", FILE_APPEND);
					} else {
						file_put_contents($db, $content);
					}
				}
			}
		}
		$read = array_reverse(file($db));
		for ($i = 0; $i < $number; $i++) {
			$exp = explode($sep, $read[$i]);
			echo '<div id="chat"><span class="red">' . $exp[0] . ' @ ' . $exp[2] . ' ' . $exp[3] . '</span><br /> ' . $exp[4] . "</div>\n";
		}
		echo '<form action="index.php" method="post">
			<br /><input type="text" value="Wpisz wiadomosc do 100 znakow" onblur="if(this.value == \'\') { this.value=\'Wpisz wiadomosc do 100 znakow\'}" onfocus="if (this.value == \'Wpisz wiadomosc do 100 znakow\') {this.value=\'\'}"name="msg" size="38" maxlength="90" id="chat-input">
			<input type="text" value="Wpisz swoje imie albo nicka" onblur="if(this.value == \'\') { this.value=\'Wpisz swoje imie albo nicka\'}" onfocus="if (this.value == \'Wpisz swoje imie albo nicka\') {this.value=\'\'}" name="usr" size="31"  id="chat-input"  />
			<input type="submit" value="Dodaj"  id="chat-button" /><input type="reset" name="Reset" id="chat-button" /></form><br />';
	}
	
	public function processDate($date, $time) {
		$czas = date("Hi");
		$time = str_replace(':', '', $time);
		if ($date == date("Y-n-d")) {
			if (($czas - $time) < 100) {
				echo '<div id="alert">Mozesz dodac nowa wiadomosc po godzinie lub gdy ktos inny doda wiadomosc</div>' . "\n";
				return true;
			}
		}
	}
	
	public function addVid() {
		if (isset($_POST['title']) && isset($_POST['link'])) {
			$title = $_POST['title'];
			$link = $_POST['link'];
			if (empty($title) || empty($link) || ($title == 'Wpisz autora i tytul video') || ($link == 'Podaj link do video na YouTube')) {
				echo '<div id="alert" style="background-position: 1% 50%; width: 528px;">You must fill all fields</div>';
				//return;
			}
			else if (isset($_POST['linkadded']) && ($_POST['linkadded'] == 'Dodaj')) {
				$content = $title . ';; ' . $link . "\n";
				if (file_exists($this->viddb2)) {
					file_put_contents($this->viddb2, $content, FILE_APPEND);
				} else {
					file_put_contents($this->viddb2, $content);
				}
				echo '<div id="ok">Video dodano!</div>' . "\n";
			}
		}
		echo '<form action="index.php?page=addvid" method="post">
			<br /><input type="text" value="Wpisz autora i tytul video" onblur="if(this.value == \'\') { this.value=\'Wpisz autora i tytul video\'}" onfocus="if (this.value == \'Wpisz autora i tytul video\') {this.value=\'\'}"name="title" size="34" maxlength="90" id="chat-input">
			<input type="text" value="Podaj link do video na YouTube" onblur="if(this.value == \'\') { this.value=\'Podaj link do video na YouTube\'}" onfocus="if (this.value == \'Podaj link do video na YouTube\') {this.value=\'\'}" name="link" size="33"  id="chat-input"  />
			<input type="submit" name="linkadded" value="Dodaj"  id="chat-button" /><input type="reset" name="Reset" id="chat-button" /></form><br />';
	}
}

?>