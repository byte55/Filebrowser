<?php

function bytesToSize($bytes, $precision = 2)
{  
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;
   
    if (($bytes >= 0) && ($bytes < $kilobyte)) {
        return $bytes . ' B';
 
    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
        return round($bytes / $kilobyte, $precision) . ' KB';
 
    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' MB';
 
    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
        return round($bytes / $gigabyte, $precision) . ' GB';
 
    } elseif ($bytes >= $terabyte) {
        return round($bytes / $terabyte, $precision) . ' TB';
    } else {
        return $bytes . ' B';
    }
}

function passwortgenerator($length)
{
	$zeichen = "qwertzupasdfghkyxcvbnm";
	$zeichen .= "1234567890";
	$zeichen .= "WERTZUPLKJHGFDSAYXCVBNM";
	srand((double)microtime()*1000000);
  	//Startwert für den Zufallsgenerator festlegen

	for($i = 0; $i < $length; $i++) $password .= substr($zeichen,(rand()%(strlen ($zeichen))), 1);

	return $password;
}

function get_file_dir($file)
{
	return str_replace(basename($file),"",str_replace("\\","/",str_replace(getcwd().DIRECTORY_SEPARATOR,"",$file)));
}

function deltree($dir) {

  $fh = opendir($dir);
  while($entry = readdir($fh)) {
    if($entry == ".." || $entry == ".")
      continue;
    if(is_dir($dir . $entry))
      deltree($dir . $entry . "/");
    else
      unlink($dir . $entry);
  }
  closedir($fh);
  rmdir($dir);

}

function check_mail($mail)
{
   if(!preg_match("/^[\w-]+(\.[\w-]+)*@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/",$mail)) return false;
   else return true;
}

function echo_js($text)
{
	echo "<script type=\"text/javascript\">alert(\"".$text."\");</script>";
}

function echo_msg($msg, $title = 'Hinweis',$error = false)
{
	if($error) $color = "red";
 	else $color="green";
 	?>
 	<table cellpadding="0" cellspacing="0" width="100%">
 	  <tr>
 	    <td align="left">
 	      <fieldset>
 	        <legend>
 	          <font color="<?=$color?>"><?=$title?></font>
 	        </legend>
 	        <?=$msg?>
 	      </fieldset>
 	    </td>
 	  </tr>
 	</table>
 	<?
}

function echo_array($array,$name = 'Array')
{
	$array = "<pre>".print_r($array,true)."</pre>";
    ?>
    <table cellpadding="0" cellspacing="0" width="100%" style="background-color:white;">
      <tr>
        <td align="left">
          <fieldset>
            <legend>
              <?=$name?>
            </legend>
            <?=$array?>
          </fieldset>
        </td>
      </tr>
    </table>
	<?
}

function sec2date($sec)
{
	$time = $sec;
	if ($time <= 0) $time = 0;
	$sekunden = floor($time % 60);
	if ($sekunden < 10) $sekunden = "0".$sekunden;
	
	$minuten = floor(($time / 60) % 60);
	if ($minuten < 10) $minuten = "0".$minuten;
	
	$stunden = floor(($time  / 60 / 60) % 24);
	if ($stunden < 10) $stunden = "0".$stunden;
	
	$tage = floor($time / 60 / 60 / 24);
	$zeit_str = "";
	if ($tage != 0) $zeit_str = $tage." Tage ".$stunden.":".$minuten.":".$sekunden;
	else $zeit_str = $stunden.":".$minuten.":".$sekunden;
	return $zeit_str;
}

function left_time($time,$length = 'long', $language = 'de')
{
	$time_units['short']['de']['min'] = 'Min.';
	$time_units['short']['de']['std'] = 'Std.';
	$time_units['short']['de']['day'] = 'Tag';
	
	$time_units['long']['de']['min'] = 'Minute';
	$time_units['long']['de']['std'] = 'Stunde';
	$time_units['long']['de']['day'] = 'Tag';

	$rest_time = time()-$time; 
	if($rest_time < 3600)
	{
		$type = 'min';
		$left_time = floor($rest_time / 60);
	}
	else if($rest_time < 86400)
	{
		$type = 'std';
		$left_time = floor($rest_time / 3600);
	}
	else
	{
		$type = 'day';
		$left_time = floor($rest_time / 86400);
	}
	
	#echo_msg($rest_time);
	$return = $left_time." ".$time_units[$length][$language][$type];
	if($type == 'short') return $return;
	else if($left_time == 1 ) return $return;
	else if($type == 'day') return $return."en";
	else return $return."n";
}

function array_kshift(&$arr)
{
  list($k) = array_keys($arr);
  $r  = array($k=>$arr[$k]);
  unset($arr[$k]);
  return $r;
}

function get_random()
{
	for ( $i = 0; $i <= 100; $i++ ) $random[] = rand(1,2);

	$count = array_count_values($random);
	if($count[1] >= $count[2]) return true;
	else return false;
}

function validateURL($url)
{
	$pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
	return preg_match($pattern, $url);
}

function get_execution_time()
{
    static $microtime_start = null;
    if($microtime_start === null)
    {
        $microtime_start = microtime(true);
        return 0.0; 
    }    
    return microtime(true) - $microtime_start; 
}

function downloadFile($fullPath)
{ 
	// Must be fresh start 
	if(headers_sent()) die('Headers Sent');  
	
	// Required for some browsers 
	if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off'); 
	
	// File Exists? 
	if(file_exists($fullPath))
	{ 
		// Parse Info / Get Extension 
		$fsize = filesize($fullPath); 
		$ext = strtolower(pathinfo($fullPath,PATHINFO_EXTENSION)); 
		
		// Determine Content Type 
		switch ($ext)
		{ 
			case "pdf": $ctype="application/pdf"; break; 
			case "exe": $ctype="application/octet-stream"; break; 
			case "zip": $ctype="application/zip"; break; 
			case "doc": $ctype="application/msword"; break; 
			case "xls": $ctype="application/vnd.ms-excel"; break; 
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break; 
			case "gif": $ctype="image/gif"; break; 
			case "png": $ctype="image/png"; break; 
			case "jpeg": 
			case "jpg": $ctype="image/jpg"; break; 
			default: $ctype="application/force-download"; 
		} 
		
		header("Pragma: public"); // required 
		header("Expires: 0"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Cache-Control: private",false); // required for certain browsers 
		header("Content-Type: $ctype"); 
		header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" ); 
		header("Content-Transfer-Encoding: binary"); 
		header("Content-Length: ".$fsize); 
		ob_clean(); 
		flush(); 
		readfile( $fullPath ); 
	}
	else die('File Not Found: '.$fullPath); 
}

function string2int($value)
{
	return str_replace(',','.',$value);
}

function jsForward($url)
{
	global $website_basedir;
	echo '<script type="text/javascript">window.setTimeout("forward(\''.$website_basedir.$url.'\')", 3000);</script>';
}

function trim_post(&$item, &$key)
{
	$item = trim($item);
}

function getJSlocales($lang)
{
	global $l;
	if($lang != '')
	{
	
		echo "var locale = {\n";
		foreach(locale::$basicLocales[$lang] as $name => $value)
		{
			if(!is_numeric($value)) echo "             '".$name."' : '".$value."',\n";
			else echo " '".$name."' : ".$value.",\n";
		}
		echo "          };\n";
	}
	else echo "// Language Error\n";
}

function hasSubDirs($path)
{
	if ($handle = opendir($path))
	{
		$i = 0;
		while (false !== ($file = readdir($handle))) {
		    if($file != '.' and $file != '..')
			{
				if(filetype($path.'/'.$file) == 'dir')
				{
					return true;
				}
			}
		}
	}
	return false;
}

?>