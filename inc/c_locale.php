<?php

class locale {
 private static $deflang;
 public static $langs = array();

 private static $locales = array();

 public static $basicLocales = array(
 /**************************************** GERMAN ****************************************/
	'de' => array(
	    /************************* Login ********************************/
		'login_label_mail'				=>  'Mail-Adresse',
		'login_label_password'			=>  'Passwort',
		'login_btn_login'				=>  'Login',
		
		/************************* Time *********************************/
		'month'							=>  'Monat',
		'months'						=>  'Monate',
		'months2'						=>  'Monaten',
		'week'							=>  'Woche',
		'weeks'							=>  'Wochen',
		'day'							=>  'Tag',
		'days'							=>  'Tage',
		'days2'							=>  'Tagen',
		'hour'							=>  'Stunde',
		'hours'							=>  'Stunden',
		'minute'						=>  'Minute',
		'minutes'						=>  'Minuten',
		'second'						=>  'Sekunde',
		'seconds'						=>  'Sekunden',
		'format_date'					=>  'd.m.Y',
		'format_datetime'				=>  'd.m.Y H:i',
		'jquery_format_datetime' 		=>  'dd.mm.yy',
		'format_datetime_seconds'		=>  'd.m.Y H:i:s',
		
		/************************* Misc *********************************/
		'per'							=>	'pro',
		'interval'						=>	'Intervall',
		'delete'						=>	'löschen',
		'cancel'						=>	'abbrechen',
		'add'							=>	'hinzufügen',
		
		/************************* Errors *********************************/
		'ajax_function_unknown'			=>	'Funktion unbekannt',
		'dont_cheat'					=>	'Nicht mogeln ;)',
		'unknown_skill'					=>	'Eigenschaft nicht bekannt',
		'fill_all_fields'				=>  'Bitte alle Felder ausfüllen',
		
		/************************* Buttons *******************************/
		'add_to_watchlist'				=>	'zur Wunschliste',
		'remove_from_watchlist'			=>	'von der Wunschliste',
		'watchlist'						=>	'Beobachten',
		'watchlist'						=>	'beobachten',
		'Password'						=>	'Passwort',
		'Password_repeat'				=>	'Passwort (wiederholen)',
		'mail_only_for_reminder'		=>	'Wir senden dir nur eine Mail mit einer Erinnerung, wenn ein Film von deiner Wunschliste released wurde',
		'activate_reminder'				=>	'Reminder aktivieren',
		
		'name'							=>	'Name',

		
  	),
  	
/**************************************** ENGLISH ****************************************/
	'en' => array(
	    /************************* Login ********************************/
		'login_label_mail'				=>  'Mail-Adresse',
		'login_label_password'			=>  'Password',
		'login_btn_login'				=>  'Login',
		
		'ajax_function_unknown'			=>	'Function unknown',
		'jquery_format_datetime'		=>  'yy-mm-dd',
		'format_datetime'				=>  'Y-m-d H:i',

		/************************* Buttons *******************************/
		'add_to_watchlist'				=>	'add to watchlist',
		'remove_from_watchlist'			=>	'remove from watchlist',
		'watchlist'						=>	'watchlist',
		'watchlist'						=>	'watchlist',
		'Password'						=>	'Password',
		'Password_repeat'				=>	'Password (repeat)',				
		'name'							=>	'Name',
		'mail_only_for_reminder'		=>	'We will send you only reminder mails, if a movie/serie from your watchlist is released',
		'activate_reminder'				=>	'Reminder aktivieren',
	)
 );

 function __construct() {
  $args = func_get_args();
  self::$langs = $args;
  self::$deflang = self::$langs[0];

  self::add(self::$basicLocales);
 }

 function __get($var)
 {
	if(isset(self::$locales[$var])) return self::$locales[$var];
	else if(isset(self::${$var})) return self::${$var};
	else return $var;
//	else echo "mist";
 }

 function add(array $arr) {
  $cnt = count(self::$langs);
  for($a = 0 ; $a < $cnt ; $a++)
   if(isset($arr[self::$langs[$a]]))
    self::$locales = array_merge(self::$locales, $arr[self::$langs[$a]]);
 }
 
 function getDate($time = false)
 {
 	$time = (!$time ? time() : $time);
 	if(self::$deflang == 'de') return date("d.m.Y");
 	else return date("m-d-Y");
 }
 
 function getDateTime($time = false)
 {
 	$time = (!$time ? time() : $time);
 	if(self::$deflang == 'de') return date("d.m.Y H:i:s");
 	else return date("m-d-Y H:i:s");
 }

 function array_define(array $arr) {
  $cnt = count($keys = array_keys($arr));
  for($a = 0 ; $a < $cnt ; $a++) {
   if(is_numeric($keys[$a]))
    $actKey = &$arr[$keys[$a]];
   else
    $actKey = &$keys[$a];

   $loc_arr[$actKey] = self::__get($arr[$actKey]);
  }

  return $loc_arr;
 }

}

?>