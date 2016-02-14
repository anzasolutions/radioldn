<?php

class Functions {

public $ip;
public $port;
public $song;
public $chan;
public $name;

function main() {
	$channel = $this->openFile();
	foreach ($channel as $key => $value) {
		$this->getServerInfo($value);
	}
}

public function openFile() {
	$file = file('db/stub_channels.txt');
	return $file;
}

public function getServerInfo($value) {
	$ar = explode(", ", $value);
	$open = fsockopen($ar[0],$ar[1]); 
	if ($open) { 
		fputs($open,"GET /7.html HTTP/1.1\nUser-Agent:Mozilla\n\n"); 
		$read = fread($open,1000); 
		$text = explode("content-type:text/html",$read); 
		$text = explode(",",$text[1]); 
	} else { 
		$er="Connection Refused!";
	}
	$this->ip = $ar[0];
	$this->port = $ar[1];
	$this->chan = $ar[2];
	$this->name = str_replace("\n",'',str_replace("\r",'',$ar[3]));
	$this->song = ucwords(str_replace('_',' ',str_replace('</body></html>','',$text[6])));
	if ($this->song == '') {
		$this->song = $ar[3];
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

public function convert2HTML($page) {
	$read = file($page);
	$pl = array('�','�','�','�','�','�','�','�','�','�','�','�','�','�','�');
	$utf = array('&#322;','&#263;','&#378;','&#380;','&#281;','&#261;','&#242;','&#379;','&#377;','&#262;','&#321;','&#243;','&#324;','&#347;','-');
	for ($i = 0; $i < 1; $i++) {
		for ($j = 0; $j < count($pl); $j++) {
			$read[0] = str_replace($pl[$j] , $utf[$j] ,$read[0]);
		}
		echo '<span class="big-red">' . $read[0] . '</span>';
	}
	echo '<div id="hr"></div>';
	for ($i = 1; $i < count($read); $i++) {
		$content = stripcslashes(str_replace('�','-',str_replace('[/b]','</b>',str_replace('[b]','<b>',str_replace('/"','"',str_replace('[/link]','"></a>',str_replace('[link]','<a href="http://',str_replace('http://','',$read[$i]))))))));
		$takelink = $this->getStringBetween($content,'<a href="http://','"></a>');
		$content = str_replace('<a href="http://' . $takelink . '"></a>','<a href="http://' . $takelink . '" title="' . $takelink . '" target="_blank">' . $takelink . '</a>',$content);
			for ($j = 0; $j < count($pl); $j++) {
				$content = str_replace($pl[$j] , $utf[$j] ,$content);
			}
		echo nl2br($content);
	}
	
}

}

?>