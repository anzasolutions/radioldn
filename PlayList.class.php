<?php

require_once('functions.php');

class PlayList {

	private $path = 'play/';

	public function genPls() {
		$read = new Functions();
		$channel = $read->openFile();
		foreach ($channel as $key => $value) {
			$read->getServerInfo($value);
			$name = $read->name;
			$this->genRam($read->chan, $read->ip, $read->port);
			$this->genAsx($read->chan, $read->ip, $read->port);
			$this->genM3u($read->chan, $read->ip, $read->port, $name);
		}
		echo 'Generowanie plikow playlist zakonczone sukcesem.';
	}

	public function genRam($chan, $ip, $port) {
		$file = $this->path . $chan . '.ram';
		$content = 'http://' . $ip . ':' . $port;
		file_put_contents($file, $content);
	}

	public function genAsx($chan, $ip, $port) {
		$file = $this->path . $chan . '.asx';
		$content = 'http://' . $ip . ':' . $port;
		file_put_contents($file, $content);
	}

	public function genM3u($chan, $ip, $port, $name) {
		$file = $this->path . $chan . '.m3u';
		$content = "#EXTM3U\n#EXTINF:-1,Radio LDN: " . strtoupper($name) . " (www.RadioLDN.net)\nhttp://" . $ip . ":" . $port;
		file_put_contents($file, $content);
	}

}

?>