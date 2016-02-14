<?php

class PlaylistGenerator {

public function openFile() {
	return file('ftp.txt');
}

public function getFromFTP($ftp,$user,$pass,$dir) {
	$connect = ftp_connect($ftp);
	$login_result = ftp_login($connect, $user, $pass);
	$contents = ftp_nlist($connect,$dir);
	return $contents;
}

public function main() {
	$this->menu();
	$fileout = 'out.txt';
	$open = $this->openFile();
	$random = array();
	if (isset($_GET['src']))
		$src = $_GET['src'];
	if (isset($_GET['type']))
		$type = $_GET['type'];
	for ($i = 0; $i < count($open); $i++) {
		$det = explode(', ',$open[$i]);
		if (@$src == $det[3] || @$src == 'all') {
			if ( @$type == 'normal' || @$type == 'shuffle') {
				$ftp_music = $this->getFromFTP($det[0],$det[1],$det[2],'music');
				$ftp_jingle = $this->getFromFTP($det[0],$det[1],$det[2],'jingle');
				/* $ftp_promo = $this->getFromFTP($det[0],$det[1],$det[2],'promo');
				$ftp_reklama = $this->getFromFTP($det[0],$det[1],$det[2],'reklama'); */
				if ($type == 'shuffle') {
					$k = 0;
					while ($k != (count($ftp_music))) {
						$randVal = $ftp_music[array_rand($ftp_music)];
						if (in_array($randVal, $random)) {
							continue;
						} else if (($k > 0) && (substr($random[$k-1], 0, strpos($random[$k-1], '-')) == (substr($randVal, 0, strpos($randVal, '-'))))) {
							continue;
						} else {
							$random[$k] = $randVal;
							//echo $k . ' ' . $random[$k] . '<br />';
							$k++;					
						}
					}
					$dest = 'pls/shuffle/';
					$ftp_music = $random;
				}
			
			
				for ($j = 0; $j < count($ftp_music); $j++) {
					if ($type == 'normal') {
						sort($ftp_music);
						$dest = 'pls/normal/';
					}
					$list = array_fill(0,count($ftp_music),$ftp_music[$j]);
					$list[$j] = str_replace("\n",'',str_replace("\n\n",'',$list[$j])) . "\n";
					file_put_contents($fileout,$list[$j],FILE_APPEND);
				}
				$list2 = file($fileout);
				unlink($fileout);
				for ($k = 0; $k < count($list2); $k++) {
					shuffle($ftp_jingle);
					/* shuffle($ftp_promo);
					shuffle($ftp_reklama); */
					if ($k%5 == 0) {
						array_splice($list2,$k,0,$ftp_jingle[0]);
					} /* else if ($k%6 == 0) {
						array_splice($list2,$k,0,$ftp_promo[0]);
					} */
					$list2[$k] = '/home/sc/mp3/' . $det[1] . '/' . str_replace("\n",'',str_replace("\n\n",'',$list2[$k])) . "\n";
					file_put_contents($fileout,$list2[$k],FILE_APPEND);
				}
				$pls = $dest . $det[3] . '.' . $type . '.lst';
				unlink($pls);
				copy($fileout,$pls);
				if (file_exists($pls)) {
					unlink($fileout);
					echo 'Lista ' . ucwords($det[3]) . ' zawierajaca ' . count($ftp_music) . ' plikow i ' . count($list2) . ' pozycji zostala wygenerowana pomyslnie w trybie ' . ucwords($type) . '!<br />';
					echo 'Pobierz plik <a href="' . $pls . '">' . $det[3] . '.' . $type . '.lst</a><br /><br />';
				} else {
					echo 'Generator zwrocil blad w tworzeniu listy. Sprobuj wygenerowac ponownie.';
				}

			}
		}
	}
}

public function menu() {
	echo '<a href="index.php">Generuj playlisty</a>';
	echo '<hr size="1" />';
	//$menu = array('polska' => 'Polska','ukcharts' => 'UK Charts','trance' => 'Trance','chillout' => 'Chillout','discopolo' => 'Disco Polo','live' => 'Live','rock' => 'Absolute Rock','disco' => 'Disco Heaven','all' => 'All');
	$menu = array('polska' => 'Polska','ukcharts' => 'UK Charts','trance' => 'Trance','chillout' => 'Chillout','discopolo' => 'Disco Polo','all' => 'All');
	foreach ($menu as $key => $value) {
		echo '<a href="?src=' . $key . '">' . $value . '</a> ';
		
	}
	echo '<hr size="1" />';
	foreach ($menu as $key => $value) {
		if (@$_GET['src'] == $key) {
			echo 'Generujesz dla ' . $value . ' <a href="?src=' . $key . '&type=normal">Normal</a> | <a href="?src=' . $key . '&type=shuffle">Shuffle</a>';
			?>
			<!--
			<form action="index.php?p=logged" method="post" autocomplete="off">
				<p>Login: <input type="text" name="login" /></p>
				<p>Pass: <input type="text" name="pass" /></p>
				<p><input type="submit" name="Submit" value="Go!" /><input type="reset" name="Reset" />
			</form>
			-->
			<?php
			echo '<hr size="1" /><br />';
		}
	}
}

}

$playgen = new PlaylistGenerator();

$playgen->main();

?>