<?php

class db {
	var $conn;
	var $db;
	var $erg;

	function db($host,$user,$password,$db)
	{
		$this->conn = @mysqli_connect($host,$user,$password);
		if($this->conn)
		{
			$this->db = mysqli_select_db($this->conn,$db);
			$this->query("SET NAMES 'utf8'");
		}
		//else echo "keine Verbindung";
	}

	function error($query,$file = '',$line = '')
	{
		//Bei einem Mysql-Fehler wird eine E-Mail verschickt
		$error = mysqli_error($this->conn);
		if($error != '') {
			echo_msg ("<p>MySQL-Fehler in Datei <b>".$file."</b>, Zeile <b>".$line."</b>:<br />".$error."<br />Query: ".$query."</p>","MySQL-Fehler",true);
	        $head = "From: mysql@walter-it.de";
	        $body = "MySQL-Error: ".$error;
	        $body .= "\n\nMySQL-Query: ".$query;
	        $body .= "\n\nDatei: ".$file;
	        $body .= "\n\nZeile: ".$line;
	        $body .= "\n\nGLOBALS: ".print_r($GLOBALS,true);
			$body .= "\n\nDEBUG_BACKTRACE: ".print_r(debug_backtrace(),true);
//	        mail("mysql@walter-it.de","DMIC - MySQL-Error",$body,$head);
	        echo_array(debug_backtrace());
	        return true;
	    }
	    else return false;
	}
	
	function get_array($sql)
	{
		$result = $this->query($sql);
		while($array = $this->assoc($result))
		{
			foreach($array as $index => $value) $new_array[$index][] = $value;
		}
		return $new_array;
	}

	function query($query,$file = '', $line = '') {
		//Führt ein Mysql-Querry aus
		$result = mysqli_query($this->conn,$query);
		if(!$this->error($query,$file,$line)) return $result;
		else return false;
	}
	
	function multi_query($query,$file = '', $line = '') {
		//Führt ein Mysql-Multi-Querry aus
		$result = mysqli_multi_query($this->conn,$query);
		if(!$this->error($query,$file,$line)) return $result;
		else return false;
	}
	
	function multi_query_one($query,$file = '', $line = '') {
		//Führt ein Mysql-Multi-Querry aus und achtet nicht auf die rückgabe
		$result = mysqli_multi_query($this->conn,$query);
		
		do
		{
			/* store first result set */
			if ($result = mysqli_store_result($this->conn))
			{
				//do nothing since there's nothing to handle
				mysqli_free_result($result);
			}
			/* print divider */
			if (mysqli_more_results($this->conn))
			{
				//I just kept this since it seems useful
				//try removing and see for yourself
			}
		} while (@mysqli_next_result($this->conn));
		
		if(!$this->error($query,$file,$line)) return $result;
		else return false;
	}
	
	function store_result()
	{
		return mysqli_store_result($this->conn);
	}
	
	function more_results()
	{
		return mysqli_more_results($this->conn);
	}
	
	function next_result()
	{
		return mysqli_next_result($this->conn);
	}

	function assoc($result,$file = '',$line = '') {
		//Gibt das Array mit den Mysql-Daten zurück
		$erg = mysqli_fetch_assoc($result);
		return $erg;
	}

	function id() {
		//Gibt die zuletzt angelegete ID zurück
		$erg = mysqli_insert_id($this->conn);
		return $erg;
	}
	
	function affected()
	{
		return mysqli_affected_rows($this-conn);
	}

	function count($query) {
		$result = $this->query($query);
		$erg = mysqli_num_rows($result);
		return $erg;
	}

	function num($result) {
		$erg = mysqli_num_rows($result);
		return $erg;
	}

	function close() {
		mysqli_close($this->conn);
	}

	function one($query) {
		//Gibt nur einen Datensatz direkt als Array zurück
		$result = $this->query($query);
    	$data = $this->assoc($result);
    	return $data;
	}
	
	function oneCol($table,$needle,$haystack,$col) {
		//Gibt nur einen Wert von einem Datensatz direkt als String zurück
		
		if(is_numeric($needle)) $sql = "SELECT `".$col."` FROM `".$table."` WHERE `".$haystack."` = ".$needle;
		else $sql = "SELECT `".$col."` FROM `".$table."` WHERE `".$haystack."` = '".$needle."'";
		$result = $this->query($sql);
    	$data = $this->assoc($result);
    	$return = array_pop($data);
    	return $data;
	}

	function escape($string)
	{
		return mysqli_real_escape_string($this->conn,$string);
	}
	
	function output($result)
	{
		//Gibt das Ergebnis als Tabelle in HTML aus
		echo '<table border="1">';
		$array = $this->assoc($result);
		$keys = array_keys($array);
		echo "\n  <tr>";

		foreach($keys as $key)
		{
			if($key == '') echo "\n    <td>&nbsp;</td>";
			else echo "\n    <td>".$key."</td>";
		}

		echo "\n  </tr>";

		while($array = $this->assoc($result))
		{
			echo "\n  <tr>";
			foreach($keys as $key)
			{
				if($array[$key] == '') echo "\n    <td>&nbsp;</td>";
				else echo "\n    <td>".$array[$key]."</td>";
			}
			echo "\n  </tr>";
		}
		echo "\n</table>";
	}

	function exists($table, $col,$value)
	{
		// Prüft, ob ein Datensatz in der DB existiert.
		if(is_numeric($value)) $sql = "SELECT 1 FROM ".$table." WHERE ".$col." = ".$value;
		else $sql = "SELECT 1 FROM ".$table." WHERE ".$col." = '".$value."'";		
		$result = $this->query($sql);
		if($this->num($result) > 0) return true;
		else return false;

	}
	
	function get_id($table, $col,$value)
	{
		// Überprüft, ob ein Wert existiert und gibt die ID des Datensatzes zurück
		if(is_numeric($value)) $sql = "SELECT `id` FROM ".$table." WHERE ".$col." = ".$value;
		else $sql = "SELECT `id` FROM ".$table." WHERE ".$col." = '".$value."'";
		$array = $this->one($sql);
		if(count($array) > 0) return $array['id'];
		else return false;
	}
}
?>