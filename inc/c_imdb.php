<?php
class imdb
{
	private $url;
	private $type;
	
	#private $types;
	
	public function __construct($type)
	{	
		$this->type = $type;
		#$this->types = array('json');
		$this->url = 'http://imdbapi.org/';
	}
	
	private function curl()
	{
		$curl = curl_init($this->url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$return = curl_exec($curl);
		curl_close($curl);
	}
	
	public function searchByTitle($title,$details = array())
	{
		$parameters = array();
		if(isset($details['year']) and (is_numeric($details['year']) and $details['year'] >= 1700 and $details['year'] <= date('Y') + 10))
			$parameters['year'] = $details['year'];
		else echo '<span style="color:red">wrong year</span><br />';
		
		if(isset($detail['year']))
			$parameters['yg'] = 1;
		else if(isset($details['yg']) and $details['yg'] == 1)
			$parameters['yg'] = 1;
		else $parameters['yg'] = 0;
		
		if(isset($details['movie_type']))
		{
			if($details['movie_type'] 		== 'Movie') $parameters['mt']		= 'M';
			else if($details['movie_type']	== 'TV Serie') $parameters['mt']	= 'TVS';
			else if($details['movie_type']	== 'TV Movie') $parameters['mt']	= 'TV';
			else if($details['movie_type']	== 'Video') $parameters['mt']		= 'V';
			else if($details['movie_type']	== 'Video Game') $parameters['mt']	= 'VG';
			else if($details['movie_type']	== 'none') $parameters['mt']		= 'none';
			else echo '<span style="color:red">wrong year</span><br />';
		}
		
		if(isset($details['plot']))
	}
}
?>