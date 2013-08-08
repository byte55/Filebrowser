<?php
class curl
{
	public function __construct()
	{

	}
	
	public function curl_php($options,$data = null, $headers = null)
	{
		$this->curl = curl_init($options['url']);
		if(preg_match('/https:/',$options['url']))
		{
			//echo 'SSL curl';
			curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
    		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
		}
		//curl_setopt($this->curl, CURLOPT_URL, $options['url']);
		#curl_setopt($this->curl, CURLOPT_HEADER, true);
		if(!isset($options['no_cookie']))
		{
			curl_setopt($this->curl, CURLOPT_COOKIEJAR, COOKIE_FULLPATH);
			curl_setopt($this->curl, CURLOPT_COOKIEFILE, COOKIE_FULLPATH);			
		}
		
		if(!isset($options['no_follow']))
			curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, TRUE);			
		else 
			curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, FALSE);
			
		curl_setopt($this->curl, CURLOPT_USERAGENT, CURL_USERAGENT);
		if($headers != 'null')
		{
			$headers[] = 'Expect:';
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
		}
		else
		{
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Expect:'));
		}
		
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
		if($options['ref_url']) curl_setopt($this->curl, CURLOPT_REFERER, $options['ref_url']);
		if($data != null)
		{
			curl_setopt($this->curl, CURLOPT_POST, true);
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);	
		}
		$this->response = curl_exec($this->curl);
		if(isset($options['info']))
		{
			if($options['info'] === true)
				echo_array(curl_getinfo($this->curl));
			else
				echo_array(curl_getinfo($this->curl,($options['info'])));
		}
		if($this->response === false) echo 'Curl-Fehler: ' . curl_error($this->curl);
		curl_close($this->curl);
//		echo $this->response."<br /><br />";
		return $this->response;
	}
	
	public function curl_php_http($url)
	{
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_URL, $url);
		curl_setopt($this->curl, CURLOPT_USERAGENT, CURL_USERAGENT);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_exec($this->curl);
		$info = curl_getinfo($this->curl);
		return $info['http_code'];
	}
	
	public function curl_cmd($options,$data,$file)
	{
		if(!isset($options['no_cookie'])) $cookie = ' --cookie '.COOKIE_FULLPATH.' --cookie-jar '.COOKIE_FULLPATH.' ';
		if(!isset($options['no_agent']))  $agent  = ' -A "'.CURL_USERAGENT.'" ';
		if(!isset($options['no_follow'])) $nofollow  = ' -L ';
		if( isset($options['host']))      $host  = $options['host'];
		$cmd = 'curl -F "'.$options['upload_field'].'=@'.$file.'" '.$this->array2post4curl($data).$cookie.$agent.' --output "'.$file.'_'.$host.'.out" '.$options['extras'].' '.$nofollow.' "'.$options['url'].'"';
		$cmd .= ' 2> "'.$file.'_'.$host.'.status"';
//		echo "cmd: ".$cmd."<br /><br /><br />";
//		echo "\n\n\n\n".$cmd."\n\n\n\n";
		return exec($cmd);
		//todo: 2> output.file
	}
	
	public function array2post4curl($array)
	{
		if(is_array($array))
		{
			$str = '';
			foreach($array as $num => $val)
			{
				if($str == '') $str = '-F "'.$num.'='.urlencode($val).'"';
				else $str .= ' -F "'.$num.'='.urlencode($val).'"';
			}
			return $str;
		}
		else return '';
	}
}
?>
