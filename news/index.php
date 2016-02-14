<?php 

class Dodaj {

	public $pages = '../db/pages/';
	public $newsdb = '../db/pages/news.txt';
	public $viddb = '../db/pages/vid.txt';
	public $musdb = '../db/pages/music.txt';
	
	public function loginForm() {
		@ $p = $_GET['p'];
		@ $login = $_POST['login'];
		@ $pass = $_POST['pass'];
		@ $title = $_POST['title'];
		@ $txt = $_POST['txt'];
		@ $db = $_POST['db'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$users = array('andre' => 'pumpol','lukasz' => 'mumbasa','jim' => 'lizard1');
		$cookie = 'cookie';
		if ($p == '' || $p == 'logout') {
			if (file_exists($cookie)) {
				unlink($cookie);
			}
	
			?>

			<form action="index.php?p=logged" method="post" autocomplete="off">
				<p>Login: <input type="text" name="login" /></p>
				<p>Pass: <input type="password" name="pass" /></p>
				<p><input type="submit" name="Submit" value="Go!" /><input type="reset" name="Reset" />
			</form>
			
			<?php
			
		} else if ($p == 'logged' && !empty($login) && !empty($pass) && @($pass == $users[$login])) {
			$content = base64_encode($login) . "\n" . base64_encode($pass) . "\n" . date("Y-m-d");
			if (!file_exists($cookie)) {
				touch($cookie);
			}
			file_put_contents($cookie,$content);
			echo 'Witaj ' . ucfirst($login) . ' !<br /><br />';
			echo ' - <a href="?p=news">Dodaj newsa</a><br />';
			echo ' - <a href="?p=delete">Usun newsa</a><br />';
			echo ' - <a href="?p=page">Dodaj strone</a><br />';
			echo ' - <a href="?p=video">Dodaj video</a><br />';
			echo ' - <a href="?p=deletev">Usun video</a><br />';
			echo ' - <a href="?p=music">Dodaj music</a><br />';
			echo ' - <a href="?p=deletem">Usun music</a><br />';
			echo ' - <a href="../playgen/">Generuj playliste</a><br />';
			echo ' - <a href="?p=logout">Wyloguj</a>';
		} else if ((isset($login, $pass) && (empty($login) || empty($pass))) || @($pass != $users[$login])) {
			echo 'Not Authorized!';
			echo '<br /><a href="index.php">Try again...</a>';
		} else if ($p == 'news' || $p == 'page' || $p == 'video' || $p == 'music' || $p == 'delete' || $p == 'deleted' || $p == 'deletev' || $p == 'deletedv' || $p == 'deletem' || $p == 'deletedm') {
			if (file_exists($cookie)) {
				$read = file($cookie);
				$read[0] = base64_decode($read[0]);
				if ($read[2] != date("Y-m-d")) {
					echo 'Cookie expired!';
					echo '<br /><a href="index.php">Please relogin...</a>';
					unlink($cookie);
				} else {
					echo 'Witaj ' . ucfirst($read[0]) . '!';
					echo '<br /><a href="?p=logout">Wyloguj</a>';
					
					if (($p == 'news' || $p == 'page' || $p == 'video' || $p == 'music') && !isset($txt,$title)) {
					$this->header();
					?>

					<form action="?p=<?php
					
					if ($p == 'news') {
						echo $p;
					} else if ($p == 'page') {
						echo $p;
					} else if ($p == 'video') {
						echo $p;
					} else if ($p == 'music') {
						echo $p;
					}
					
					?>" method="post">
						<p>Tytul: <input type="text" name="title" size="100" /></p>
						<p>Tekst: <textarea name="txt" cols="75" rows="10"></textarea></p>
						<p><input type="submit" name="Submit" value="Publikuj" /><input type="reset" name="Anuluj" />
					</form>
					
					<?php
					
					$this->footer();
					
					} else if (($p == 'news' || $p == 'page' || $p == 'video' || $p == 'music') && isset($txt,$title)) {
						if ($title == '' && $txt == '') {
							echo 'Brak tytulu i tresci!';
						} else if ($title == '') {
							echo 'Brak tytulu!';
						} else if ($txt == '') {
							echo 'Brak tresci!';
						} else {
							echo $p;
							echo $title;
							echo $txt;
							$txt = str_replace("'" , '\'', str_replace(strstr($txt, '&'), '', $txt));
							if ($p == 'news') {
								$this->writeNews($title,$txt);
								echo 'News zostal zapisany.<br />';
								echo '<a href="?p=news">Chcesz dodac kolejnego newsa?</a><br />';
							} else if ($p == 'page') {
								$this->writePage($title,$txt);
								echo 'Strona zostala zapisana.<br />';
								echo '<a href="?p=page">Chcesz dodac kolejna strone?</a><br />';
							} else if ($p == 'video') {
								$this->writeVideo($title,$txt);
								echo '<br />Video zostalo zapisane.<br />';
								echo '<a href="?p=video">Chcesz dodac kolejne video?</a><br />';
							} else if ($p == 'music') {
								$this->writeMusic($title,$txt);
								echo '<br />Music zostalo zapisane.<br />';
								echo '<a href="?p=music">Chcesz dodac kolejne music?</a><br />';
							}
						}
					} else if ($p == 'delete') {
						$this->readNews();
					} else if ($p == 'deleted') {
						$this->deleteNews();
					} else if ($p == 'deletev') {
						$this->readV();
					} else if ($p == 'deletedv') {
						$this->deleteV();
					} else if ($p == 'deletem') {
						$this->readM();
					} else if ($p == 'deletedm') {
						$this->deleteM();
					}
				}
			} else {
				echo 'Cookie doesn\'t exist!';
				echo '<br /><a href="index.php">Please login...</a>';
			}
		}
			/* phpinfo(INFO_VARIABLES); */
	}
	
	public function writeNews($title,$txt) {
		$content = $title . ';; ' . date("Y-n-j, H:i:s") . ';; ' . str_replace("\r",'<br />',str_replace("\n",'',$txt)) . "\n";
		file_put_contents($this->newsdb, $content, FILE_APPEND | FILE_TEXT);
	}
	
	public function writeVideo($title,$txt) {
		$content = $title . ';; ' . $txt . "\n";
		file_put_contents($this->viddb, $content, FILE_APPEND | FILE_TEXT);
	}
	
	public function writeMusic($title,$txt) {
		$content = $title . ';; ' . $txt . "\n";
		file_put_contents($this->musdb, $content, FILE_APPEND | FILE_TEXT);
	}
	
	public function writePage($title,$txt) {
		$content = $title  . "\n\n" . $txt;
		$title = strtolower(str_replace(' ','',$title));
		file_put_contents($this->pages . $title . '.txt', $content);
	}
	
	public function displayNews() {
		$readfile = array_reverse(file($this->newsdb));
		for ($i = 0; $i < count($readfile); $i++) {
			$roz = explode(';; ',$readfile[$i]);
			echo '<span class="big-red">' . $roz[0] . '</span><br />';
			echo '<span class="small-red">( ' . $roz[1] . ' )</span><br />';
			echo $roz[2] . '<br /><br />';
		}
	}
	
	public function readNews() {
		$readfile = array_reverse(file($this->newsdb));
		
?>
				<form action="index.php?p=deleted" method="post">
<?php
		for ($i = 0; $i < count($readfile); $i++) {
			echo '<input type="checkbox" name="' . $i . '" value="' . $i . '">' . $readfile[$i] . '<br />';
		}
?>
				<p><input type="submit" value="Go!" /><input type="reset" name="Reset" />
			</form>
<?php
	}
	
	public function deleteNews() {
		$readfile = array_reverse(file($this->newsdb));
		$lol = array();
		for ($i = 0, $k = 0; $i < count($readfile); $i++) {
			if (isset($_POST[$i])) {
				$_POST[$i] = '';
				continue;
			}
			$lol[$k] = $readfile[$i];
			$k++;
		}
		$lol = array_reverse($lol);
		$content = '';
		file_put_contents($this->newsdb, $content);
		for ($i = 0; $i < count($lol); $i++) {
			file_put_contents($this->newsdb, $lol[$i], FILE_APPEND | FILE_TEXT);
		}
		echo '<br /><br /> Usunieto wybrane newsy';
	}
	
	public function readV() {
		$readfile = array_reverse(file($this->viddb));
		
?>
				<form action="index.php?p=deletedv" method="post">
<?php
		for ($i = 0; $i < count($readfile); $i++) {
			echo '<input type="checkbox" name="' . $i . '" value="' . $i . '">' . $readfile[$i] . '<br />';
		}
?>
				<p><input type="submit" value="Go!" /><input type="reset" name="Reset" />
			</form>
<?php
	}
	
	public function deleteV() {
		$readfile = array_reverse(file($this->viddb));
		$lol = array();
		for ($i = 0, $k = 0; $i < count($readfile); $i++) {
			if (isset($_POST[$i])) {
				$_POST[$i] = '';
				continue;
			}
			$lol[$k] = $readfile[$i];
			$k++;
		}
		$lol = array_reverse($lol);
		$content = '';
		file_put_contents($this->viddb, $content);
		for ($i = 0; $i < count($lol); $i++) {
			file_put_contents($this->viddb, $lol[$i], FILE_APPEND | FILE_TEXT);
		}
		echo '<br /><br /> Usunieto wybrane video';
	}
	
	public function readM() {
		$readfile = array_reverse(file($this->musdb));
		
?>
				<form action="index.php?p=deletedm" method="post">
<?php
		for ($i = 0; $i < count($readfile); $i++) {
			echo '<input type="checkbox" name="' . $i . '" value="' . $i . '">' . $readfile[$i] . '<br />';
		}
?>
				<p><input type="submit" value="Go!" /><input type="reset" name="Reset" />
			</form>
<?php
	}
	
	public function deleteM() {
		$readfile = array_reverse(file($this->musdb));
		$lol = array();
		for ($i = 0, $k = 0; $i < count($readfile); $i++) {
			if (isset($_POST[$i])) {
				$_POST[$i] = '';
				continue;
			}
			$lol[$k] = $readfile[$i];
			$k++;
		}
		$lol = array_reverse($lol);
		$content = '';
		file_put_contents($this->musdb, $content);
		for ($i = 0; $i < count($lol); $i++) {
			file_put_contents($this->musdb, $lol[$i], FILE_APPEND | FILE_TEXT);
		}
		echo '<br /><br /> Usunieto wybrane music';
	}
	
	public function header() {
		
		?>
		
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
		<head>
		  <meta http-equiv="Content-type" content="text/html; charset=iso-8859-1" />
		  <meta name="Description" content="www.RadioLDN.net" />
		  <meta name="Keywords" content="www.RadioLDN.net" />
		  <meta name="Author" content="www.RadioLDN.net" />

		  <title>www.RadioLDN.net</title>

		</head>
		<body>
		
		<?php
		
	}
	
	public function footer() {
		
		?>
		
		</body>
		</html>
		
		<?php
		
	}

}

$dod = new Dodaj();
$dod->loginForm();

?>

