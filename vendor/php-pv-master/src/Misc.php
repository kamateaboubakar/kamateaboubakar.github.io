<?php

namespace Pv ;

class Misc
{
	public static function try_session_start()
	{
		if (! isset ($_COOKIE[ini_get('session.name')]))
		{
			session_start();
		}
	}
	public static function encode_html_symbols($texte)
	{
		return str_replace(
			array('`', '¡', '¢', '£', '¤', '¥', '¦', '§', '¨', '©', 'ª', '«', '¬', '­', '®', '¯', '°', '±', '²', '³', '´', 'µ', '¶', '·', '¸', '¹', 'º', '»', '¼', '½', '¾', '¿', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', '×', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'Þ', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', '÷', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'þ', 'ÿ'),
			array('&#96;', '&#161;', '&#162;', '&#163;', '&#164;', '&#165;', '&#166;', '&#167;', '&#168;', '&#169;', '&#170;', '&#171;', '&#172;', '&#173;', '&#174;', '&#175;', '&#176;', '&#177;', '&#178;', '&#179;', '&#180;', '&#181;', '&#182;', '&#183;', '&#184;', '&#185;', '&#186;', '&#187;', '&#188;', '&#189;', '&#190;', '&#191;', '&#192;', '&#193;', '&#194;', '&#195;', '&#196;', '&#197;', '&#198;', '&#199;', '&#200;', '&#201;', '&#202;', '&#203;', '&#204;', '&#205;', '&#206;', '&#207;', '&#208;', '&#209;', '&#210;', '&#211;', '&#212;', '&#213;', '&#214;', '&#215;', '&#216;', '&#217;', '&#218;', '&#219;', '&#220;', '&#221;', '&#222;', '&#223;', '&#224;', '&#225;', '&#226;', '&#227;', '&#228;', '&#229;', '&#230;', '&#231;', '&#232;', '&#233;', '&#234;', '&#235;', '&#236;', '&#237;', '&#238;', '&#239;', '&#240;', '&#241;', '&#242;', '&#243;', '&#244;', '&#245;', '&#246;', '&#247;', '&#248;', '&#249;', '&#250;', '&#251;', '&#252;', '&#253;', '&#254;', '&#255;'),
			$texte
		) ;
	}

	public static function array_join_values_of_key($sep, $data=array(), $key='')
	{
		$result = '' ;
		foreach($data as $i => $row)
		{
			$val = (isset($row[$key])) ? $row[$key] : '' ;
			if($i > 0)
				$result .= $sep ;
			$result .= $val ;
		}
		return $result ;
	}
	public static function redirect_to($url)
	{
		header('location:'.$url."") ;
		exit ;
	}
	public static function js_redirect_to($url)
	{
		echo '<script type="text/javascript">
	window.location = "'.$url.'" ;
</script>' ;
	}
	public static function extract_words($text, $min_length=1)
	{
		global $word_separator ;
		$word_sep = join('|', $word_separator) ;
		$res = split($word_sep, $text) ;
		$words = array() ;
		foreach($res as $i => $word_res)
		{
			if($word_res == '')
				continue ;
			if(strlen($word_res) < $min_length)
			{
				continue ;
			}
			$words[] = $word_res  ;
		}
		$words = array_unique($words) ;
		return $words ;
	}
	public static function extract_exprs($text, $expr_tag=array('"', '\''), $expr_separator=array(',',' '), $expr_escapes=array('\\'))
	{
		$exprs = array() ;
		$begin_expr = 0 ;
		$end_expr = 0 ;
		$begin_expr_char = '' ;
		$end_expr_char = '' ;
		$current_expr = '' ;
		for($i=0; $i<strlen($text); $i++)
		{
			if(! $begin_expr)
			{
				if(in_array($text[$i], $expr_tag))
				{
					$begin_expr = 1 ;
					$end_expr = 0 ;
					$begin_expr_char = $expr_tag[array_search($text[$i], $expr_tag)] ;
					if($current_expr != "")
					{
						$exprs[] = $current_expr ;
					}
					$current_expr = '' ;
				}
				elseif(in_array($text[$i], $expr_separator))
				{
					if($current_expr != "")
					{
						$exprs[] = $current_expr ;
						$current_expr = '' ;
					}
				}
				else
				{
					$current_expr .= $text[$i] ;
				}
			}
			else
			{
				if(in_array($text[$i], $expr_escapes))
				{
					if(isset($text[$i + 1]))
					{
						if($text[$i + 1] == $begin_expr_char)
						{
							continue ;
						}
					}
					$current_expr .= $text[$i] ;
				}
				elseif($text[$i] == $begin_expr_char)
				{
					if(isset($text[$i - 1]))
					{
						if(in_array($text[$i - 1], $expr_escapes))
						{
							$current_expr .= $text[$i] ;
							continue ;
						}
					}
					$begin_expr = 0 ;
					$end_expr = 1 ;
					$exprs[] = $current_expr ;
					$current_expr = '' ;
					// print $text[$i].'<br />' ;
				}
				else
				{
					$current_expr .= $text[$i] ;
				}
			}
		}
		if($current_expr != '')
		{
			$exprs[] = $current_expr ;
		}
		return $exprs ;
	}
	
	public static function concat_arrays()
	{
		$Arrays = func_get_args() ;
		$Results = array() ;
		foreach($Arrays as $i => $Array)
		{
			if(! is_array($Array))
				$Results[] = $Array ;
			else
			{
				foreach($Array as $Key => $Value)
				{
					$Results[] = $Value ;
				}
			}
		}
		return $Results ;
	}
	public static function array_contains_keys($array, $keys)
	{
		$ok = 1 ;
		foreach($keys as $i => $key)
		{
			if(! isset($array[$key]))
			{
				$ok = 0 ;
				break ;
			}
		}
		return $ok ;
	}
	public static function is_type_of($value, $type)
	{
		$ok = 1 ;
		if($type == '__variant')
		{
			return $ok ;
		}
		if($type == '__scalar')
		{
			return is_scalar($value) ;
		}
		if(($type_value = gettype($value)) != $type)
		{
			if($type_value == 'object')
			{
				if(get_class($array[$key]) != $type)
				{
					$ok =0 ;
				}
			}
			else
			{
				$ok = 0 ;
			}
		}
		return $ok ;
	}
	public static function array_contains_keys_of_type($array, $keys)
	{
		$ok = 1 ;
		foreach($keys as $key => $type)
		{
			if(! isset($array[$key]))
			{
				$ok =0 ;
			}
			else
			{
				$ok = is_type_of($array[$key], $type) ;
			}
			if(! $ok)
			{
				break ;
			}
		}
		return $ok ;
	}
	public static function array_contains_key($array, $key)
	{
		return \Pv\Misc::array_contains_keys($array, array($key)) ;
	}
	public static function array_contains_values($array, $values)
	{
		$ok = 1 ;
		foreach($values as $i => $value)
		{
			$found = 0 ;
			foreach($array as $j => $v)
			{
				if($value == $v)
				{
					$found = 1 ;
					break ;
				}
			}
			if(! $found)
			{
				$ok = 0 ;
				break ;
			}
		}
		return $ok ;
	}
	public static function array_contains_value($array, $value)
	{
		return \Pv\Misc::array_contains_values($array, array($value)) ;
	}
	public static function array_extract_value_for_key_str($haystack, $key_str, $key_sep=",")
	{
		$keys = explode($key_sep, $key_str) ;
		return \Pv\Misc::array_extract_value_for_keys($haystack, $keys) ;
	}
	public static function array_rename_key($haystack, $key, $new_key)
	{
		return \Pv\Misc::array_change_value($haystack, $key, $new_key, NULL) ;
	}
	public static function array_change_value($haystack, $key, $new_key="", $value = NULL)
	{
		$result = array() ;
		$keys = array_keys($haystack) ;
		foreach($keys as $i => $cur_key)
		{
			if($cur_key != $key)
			{
				$result[$cur_key] = $haystack[$cur_key] ;
			}
			else
			{
				if($new_key == "")
				{
					$new_key = $key ;
				}
				if($value == NULL)
				{
					$value = $haystack[$cur_key] ;
				}
				$result[$new_key] = $value ;
			}
		}
		return $result ;
	}
	public static function array_diff_value($src, $dest)
	{
		$result = array() ;
		foreach($src as $n => $v)
		{
			if(! isset($dest[$n]))
			{
				$result[$n] = $v ;
				continue ;
			}
			if($v != $dest[$n])
			{
				$result[$n] = $v ;
			}
		}
		foreach($dest as $n => $v)
		{
			if(! isset($src[$n]))
			{
				$result[$n] = $v ;
			}
		}
		return $result ;
	}

	public static function html_escape_attr_value($text)
	{
		$result = $text ;
		$result = str_replace('"', '&quot;', $result) ;
		return $result ;		
	}
	public static function clean_special_chars($text)
	{
		$result = $text ;
		$result = str_replace("\n", " ", $result) ;
		$result = str_replace("\t", " ", $result) ;
		$result = str_replace("&agrave;", "a", $result) ;
		$result = str_replace("&acirc;", "a", $result) ;
		$result = str_replace("à", "a", $result) ;
		$result = str_replace("â", "a", $result) ;
		$result = str_replace("ä", "a", $result) ;
		$result = str_replace("&eacute;", "e", $result) ;
		$result = str_replace("&egrave;", "e", $result) ;
		$result = str_replace("&ecirc;", "e", $result) ;
		$result = str_replace("&euml;", "e", $result) ;
		$result = str_replace("é", "e", $result) ;
		$result = str_replace("è", "e", $result) ;
		$result = str_replace("ê", "e", $result) ;
		$result = str_replace("ë", "e", $result) ;
		$result = str_replace("&igrave;", "i", $result) ;
		$result = str_replace("&icirc;", "i", $result) ;
		$result = str_replace("ì", "i", $result) ;
		$result = str_replace("î", "i", $result) ;
		$result = str_replace("ï", "i", $result) ;
		$result = str_replace("&ograve;", "o", $result) ;
		$result = str_replace("&ocirc;", "o", $result) ;
		$result = str_replace("ò", "o", $result) ;
		$result = str_replace("ô", "o", $result) ;
		$result = str_replace("ö", "o", $result) ;
		$result = str_replace("&ugrave;", "u", $result) ;
		$result = str_replace("&ucirc;", "u", $result) ;
		$result = str_replace("ù", "u", $result) ;
		$result = str_replace("û", "u", $result) ;
		$result = str_replace("ü", "u", $result) ;
		$result = str_replace("&ccedil;", "c", $result) ;
		$result = str_replace("ç", "c", $result) ;
		$result = str_replace("’", "'", $result) ;
		$result = str_replace("`", "'", $result) ;
		$result = str_replace("µ", "u", $result) ;
		$result = str_replace("£", "E", $result) ;
		$result = str_replace("$", "", $result) ;
		$result = str_replace("¤", "o", $result) ;
		$result = str_replace("°", "o", $result) ;
		$result = str_replace("@", "a", $result) ;
		$result = str_replace("^", " ", $result) ;
		$result = str_replace("¨", " ", $result) ;
		$result = str_replace("&quot;", "'", $result) ;
		$result = str_replace("&160#;", " ", $result) ;
		$result = preg_replace("/&[A-Z0-9#]+;/i", "", $result) ;
		$result = str_replace('\\\\', "", $result) ;
		$result = preg_replace("/[[:space:]]{2,}/", " ", $result) ;
		$result = preg_replace("/^[[:space:]]+/", "", $result) ;
		//$result = ereg_replace("\s\s+", "", $result) ;
		return $result ;
	}
	
	public static function date_fr($Date)
	{
		$DateAttr = explode("-", $Date) ;
		if(count($DateAttr) != 3)
		{
			return $Date ;
		}
		return $DateAttr[2]."/".$DateAttr[1].'/'.$DateAttr[0] ;
	}
	public static function date_time_fr($Date)
	{
		if(empty($Date))
		{
			return '' ;
		}
		$dateParts = explode(" ", $Date) ;
		if(count($dateParts) != 2)
			return $Date ;
		$DateAttr = explode("-", $dateParts[0]) ;
		if(count($DateAttr) != 3)
		{
			return $Date ;
		}
		return $DateAttr[2]."/".$DateAttr[1].'/'.$DateAttr[0].' '.$dateParts[1] ;
	}
	public static function hour($Time)
	{
		if(empty($Time))
		{
			return '' ;
		}
		$TimeAttr = explode(":", $Time) ;
		if(count($TimeAttr) != 3)
		{
			return $Time ;
		}
		if($TimeAttr[0] == '00' && $TimeAttr[1] == '00')
		{
			return "" ;
		}
		return $TimeAttr[0].":".$TimeAttr[1] ;
	}
	public static function get_age($date)
	{
		$birthDate = explode("-", $date) ;
		$age = (date("md", strtotime($date)) > date("md")
? ((date("Y") - $birthDate[0]) - 1)
: (date("Y") - $birthDate[0]));
		return $age ;
	}
	
	public static function send_html_mail($to, $subject, $text, $from='', $cc='', $bcc='')
	{
		if($from == "")
		{
			$from = ini_get('sendmail_from') ;
		}
		// Pour envoyer un mail HTML, l'entête Content-type doit être définie
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		// Entêtes additionnelels
		// $headers .= 'To: '.$to. "\r\n";
		$headers .= 'From: '.$from. "\r\n";
		if($cc != '')
		{
			$headers .= 'Cc: '. $cc . "\r\n";
		}
		if($bcc != '')
		{
			$headers .= 'Bcc: '.$bcc . "\r\n";			
		}
		// Envoi
		return mail($to, $subject, $text, $headers);
	}
	public static function send_plain_mail($to, $subject, $text, $from='', $cc='', $bcc='')
	{
		if($to == "")
		{
			$to = ini_get('sendmail_from') ;
		}
		if($from == "")
		{
			$from = ini_get('sendmail_from') ;
		}
		$headers = 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$from. "\r\n";
		if($cc != '')
		{
			$headers .= 'Cc: '. $cc . "\r\n";
		}
		if($bcc != '')
		{
			$headers .= 'Bcc: '.$bcc . "\r\n";			
		}
		// Envoi
		return mail($to, $subject, $text, $headers);
	}
	public static function send_mail_with_attachments($to, $subject, $text, $files=array(), $from='', $cc='', $bcc='')
	{
		if($to == "")
		{
			$to = ini_get('sendmail_from') ;
		}
		if($from == "")
		{
			$from = ini_get('sendmail_from') ;
		}
		$headers .= '';
		$headers .= 'To: '.$to. "\r\n";
		$headers .= 'From: '.$from. "\r\n";
		if($cc != '')
		{
			$headers .= 'Cc: '. $cc . "\r\n";
		}
		if($bcc != '')
		{
			$headers .= 'Bcc: '.$bcc . "\r\n";			
		}
		$mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";
		// Boundary
  $headers .= "Content-Type: multipart/mixed;\r\n" .
			" boundary=\"{$mime_boundary}\"" ;
		// Text
  $message = "...\n\n".
	 "--{$mime_boundary}\n".
	 "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
	 "Content-Transfer-Encoding: 7bit\n\n" .
	 $text . "\n\n";
		// Files
		foreach($files as $i => $file_path)
		{
			if(! file_exists($file_path))
			{
				continue ;
			}
			$file_type = mime_content_type($file_path) ;
			$file_name = basename($file_path) ;
			// open the file for a binary read
			$file = fopen($file_path,'rb');
			// read the file content into a variable
			$file_data = fread($file,filesize($file_path));
			// close the file
			fclose($file);
			// now we encode it and split it into acceptable length lines
			$file_data = chunk_split(base64_encode($file_data));
			// Attach the file
			$message .= "--{$mime_boundary}\n" .
			"Content-Type: {$file_type};\n" .
			" name=\"{$file_name}\"\n" .
			//"Content-Disposition: attachment;\n" .
			//" filename=\"{$fileatt_name}\"\n" .
			"Content-Transfer-Encoding: base64\n\n" .
			$file_data . "\n\n" .
			"--{$mime_boundary}--\n";
		}
		// Envoi
		return mail($to, $subject, $message, $headers);
	}
	
	public static function _content_of($text, $field_name='', $type='text')
	{
		$result = $text ;
		if(function_exists('format_value_of_'.$field_name))
		{
			eval('$result = format_value_of_'.$field_name.'($result) ;'."\n") ;
			return $result ;
		}
		if($type == 'text' || $type == 'char' || $type == 'varchar' || $type == 'varchar2')
		{
			$result = \Pv\Misc::format_text($result) ;
		}
		elseif($type == 'int' || $type == 'long' || $type == 'shortint' || $type == 'bigint' || $type == 'tinyint' || $type == 'mediumint' || $type == 'smallint')
		{
			$result = \Pv\Misc::format_integer($result) ;
		}
		elseif($type == 'float' || $type == 'double' || $type == 'real' || $type == 'decimal')
		{
			$result = \Pv\Misc::format_decimal($result) ;
		}
		elseif($type == 'date')
		{
			$result = \Pv\Misc::format_date($result) ;
		}
		elseif($type == 'datetime')
		{
			$result = \Pv\Misc::format_datetime($result) ;
		}
		elseif($type == 'time')
		{
			$result = ($result) ;
		}
		elseif($type == 'timestamp')
		{
			$result = \Pv\Misc::format_timestamp($result) ;
		}
		elseif($type == 'year')
		{
			$result = \Pv\Misc::format_year($result) ;
		}
		elseif($type == 'blob' || $type == 'tinyblob' || $type == 'mediumblob' || $type == 'longblob')
		{
			$result = \Pv\Misc::format_blob($result) ;
		}
		elseif($type == 'binary' || $type == 'varbinary')
		{
			$result = \Pv\Misc::format_binary($result) ;
		}
		elseif($type == 'enum')
		{
			$result = \Pv\Misc::format_enum($result) ;
		}
		elseif($type == 'set')
		{
			$result = \Pv\Misc::format_set($result) ;
		}
		return $result ;
	}
	public static function format_text($text)
	{
		$result = $text ;
		return $result ;
	}
	public static function format_blob($text)
	{
		$result = $text ;
		return $result ;
	}
	public static function format_binary($text)
	{
		$result = $text ;
		return $result ;
	}
	public static function format_enum($text)
	{
		$result = $text ;
		return $result ;
	}
	public static function format_set($text)
	{
		$result = $text ;
		return $result ;
	}
	public static function format_integer($number)
	{
		$result = \Pv\Misc::format_money($number, 0) ;
		return $result ;
	}
	public static function format_decimal($number)
	{
		$result = \Pv\Misc::format_money($number, 2) ;
		return $result ;
	}
	public static function format_money($number, $decimal_count=2, $max_length=5)
	{
		if($number == "")
			$number = 0 ;
		$result = number_format($number, $decimal_count, ',', ' ');
		if($decimal_count)
		{
			if(preg_match('/,0{'.$decimal_count.'}$/', $result))
			{
				$result = preg_replace('/,0{'.$decimal_count.'}$/', '&nbsp;&nbsp;&nbsp;', $result) ;
			}
		}
		$current_length = strlen($result) ;
		for($i=0; $i<$max_length - $current_length; $i++)
		{
			$result = '&nbsp;'.$result ;
		}
		return $result ;
	}
	public static function format_date($date)
	{
		$result = $date ;
		$attrs = explode('-', $result) ;
		$result = $attrs[2].'/'.$attrs[1].'/'.$attrs[0] ;
		return $result ;
	}
	public static function format_time($time)
	{
		$result = $time ;
		return $result ;
	}
	public static function format_datetime($datetime)
	{
		$attrs = explode(' ', $datetime) ;
		$date = $attrs[0] ;
		$time = '00:00:00' ;
		if(isset($attrs[1]))
		{
			$time = $attrs[1] ;
		}
		$result = \Pv\Misc::format_date($date).' '.\Pv\Misc::format_time($time) ;
		return $result ;
	}
	public static function format_timestamp($timestamp)
	{
		$result = $timestamp ;
		$result = \Pv\Misc::format_datetime($timestamp) ;
		return $result ;
	}
	public static function format_year($text)
	{
		$result = \Pv\Misc::format_text($text, 0) ;
		return $result ;
	}
	
	public static function build_detail_query($queryData)
	{
		if(! is_array($queryData))
			return '' ;
		$ctn = '' ;
		foreach($queryData as $name => $v)
		{
			if(! is_string($v))
			{
				continue ;
			}
			$ctn .= $name.' : '.$v."\r\n" ;
		}
		return $ctn ;
	}
	
	public static function parse_str_def($text, $def=array())
	{
		$result = $def ;
		try
		{
			parse_str($text, $data) ;
			if($data)
			{
				$result = $data ;
			}
		}
		catch(Exception $ex)
		{
		}
		return $result ;
	}
	
	public static function & _value_def($haystack, $param_name, $default='', $as='')
	{
		if(is_string($param_name) || is_numeric($param_name))
		{
			// print print_r($haystack, true).' & '.$param_name ;
			if(is_object($haystack))
			{
				$haystack = get_object_vars($haystack) ;
			}
			$value = (isset($haystack[$param_name])) ? \Pv\Misc::_cast_value($haystack[$param_name], $as) : $default ;
			return $value ;
		}
		$value = $default ;
		if(is_array($param_name))
		{
			$haystack_temp = $haystack ;
			foreach($param_name as $n => $v)
			{
				// print $v.'<br />' ;
				if(! is_string($v) && ! is_numeric($v))
				{
					$haystack_temp = null ;
					break ;
				}
				$cond = '' ;
				if(strpos($v, ':') !== false)
				{
					$attr = substr($v, 0, strpos($v, ':')) ;
					$cond = substr($v, strpos($v, ':') + 1) ;
				}
				else
				{
					$attr = $v ;
				}
				$haystack_temp = ((isset($haystack_temp[$attr])) ? $haystack_temp[$attr] : null) ;
				if($haystack_temp === null)
					break ;
				if($cond != '')
				{
					$filter = preg_replace('/\$([a-z0-9\_]+)/i', '$sub_haystack["\1"]', $cond) ;
					if(is_array($haystack_temp))
					{
						$OK = 0 ;
						foreach($haystack_temp as $i => $sub_haystack)
						{
							eval('$OK = ('.$filter.') ;') ;
							if($OK)
							{
								$haystack_temp = $sub_haystack ;
								break ;
							}
						}
						if(! $OK)
						{
							$haystack_temp = null ;
							break ;
						}
					}
				}
			}
			if($haystack_temp !== false && $haystack_temp !== null)
			{
				$value = $haystack_temp ;
			}
		}
		return $value ;
	}
	public static function _GET_def($param_name, $default='', $as='')
	{
		return \Pv\Misc::_value_def($_GET, $param_name, $default, $as) ;
	}
	public static function _SESSION_def($param_name, $default='', $as='')
	{
		return \Pv\Misc::_value_def($_SESSION, $param_name, $default, $as) ;
	}
	public static function _POST_def($param_name, $default='', $as='')
	{
		return \Pv\Misc::_value_def($_POST, $param_name, $default, $as) ;
	}
	public static function _REQUEST_def($param_name, $default='', $as='')
	{
		return \Pv\Misc::_value_def($_REQUEST, $param_name, $default, $as) ;
	}
	
	public static function array_has_empty_value($haystack, $keys=array(), $empty_value="")
	{
		$ok = 0 ;
		foreach($keys as $i => $key)
		{
			if(! isset($haystack[$key]))
			{
				$ok = 1 ;
			}
			elseif($haystack[$key] == $empty_value)
			{
				$ok = 1 ;
			}
			if($ok)
			{
				break ;
			}
		}
		return $ok ;
	}
	public static function array_assign_value($haystack, $keys=array(), $default_value="")
	{
		$result = $haystack ;
		while(list($i, $key) = each($keys))
		{
			if(! isset($result[$key]))
			{
				$result[$key] = $default_value ;
			}
		}
		return $result ;
	}
	public static function array_find_empty_values($haystack, $keys=array(), $empty_value="", $first_only=0)
	{
		$ok = 0 ;
		$empty_keys = array() ;
		foreach($keys as $i => $key)
		{
			if(! isset($haystack[$key]))
			{
				$ok = 1 ;
			}
			elseif($haystack[$key] == $empty_value)
			{
				$ok = 1 ;
			}
			if($ok)
			{
				$empty_keys[] = $key ;
				if($first_only)
				{
					break ;
				}
			}
		}
		return $empty_keys ;
	}
	public static function array_find_empty_value($haystack, $keys=array(), $empty_value="")
	{
		$key = \Pv\Misc::array_find_empty_values($haystack, $keys, $empty_value) ;
		$result = ((isset($key[0]))) ? $key[0] : '' ;
		return $result ;
	}
	public static function array_remove_empty_values($haystack=array(), $keep_keys=0, $empty_values=array(""))
	{
		$result = array() ;
		foreach($haystack as $i => $v)
		{
			if(! in_array($v, $empty_values))
			{
				if($keep_keys)
				{
					$result[$i] = $v ;
				}
				else
				{
					$result[] = $v ;
				}
			}
		}
		return $result ;
	}
	public static function array_remove_empty_value($haystack=array(), $keep_keys=0, $empty_value="")
	{
		return \Pv\Misc::array_remove_empty_values($haystack, $keep_keys, array($empty_value)) ;
	}
	
	// Cast public static function by Reference, including field formats
	public static function _cast_ref(& $var, $type='string', $default_value=null)
	{
		$var = \Pv\Misc::_cast_value($var, $type, $default_value) ;
	}
	public static function _cast_value($val, $type='string', $default_value=null)
	{
		if($type == '') { return $val ; }
		$var = $val ;
		if(is_array($type))
		{
			if(count($type))
			{
				settype($var, 'string') ;
				(in_array($var, $type)) ? '' : $var = $type[0] ;
				return $var ;
			}
			else
			{
				$type = 'string' ;
			}
		}
		$type = ($type == 'char' or $type == 'text' or $type == 'varchar') ? 'string' : strtolower($type) ;
		$type = ($type == 'timestamp') ? 'int' : $type ;
		if(in_array($type, array('int', 'integer', 'double', 'float', 'number', 'numeric')))
		{
			$var = str_replace('[[:space:]]', '', $var) ;
			$var = str_replace(',|\:|;', '.', $var) ;
		}
		$php_types = array("array", "bool", "boolean", "float", "int", "integer", "null", "object", "string") ;
		if(in_array($type, $php_types))
		{
			settype($var, $type) ;
			return $var ;
		}
		$match_pos = strpos($type, '/') ;
		$i1_pos = strpos($type, '[') ;
		$i2_pos = strpos($type, ']') ;
		if($match_pos !== false && $match_pos == 0)
		{
			$var = (preg_match($type, $var, $match)) ? $match[0] : $default_value ;
		}
		elseif(($i1_pos !== false && $i1_pos == 0) || ($i2_pos !== false && $i2_pos == 0))
		{
			$left_tag = $type[0] ;
			$right_tag = $type[strlen($type) - 1] ;
			$nb_str = substr($type, 1, strlen($type) - 2) ;
			$nbs = explode(',', $nb_str) ;
			$cond = '1' ;
			(! isset($nbs[1])) ? $nbs[1] = '..' : 1 ;
			if($nbs[0] != '..' or $nbs[0] != '')
			{
				$sign = ($left_tag == '[') ? '>=' : '>' ;
				$cond .= ' and intval($var)'.$sign.intval($nbs[0]) ;
			}
			if($nbs[1] != '..' or $nbs[1] != '')
			{
				$sign = ($right_tag == ']') ? '<=' : '<' ;
				$cond .= ' and intval($var)'.$sign.intval($nbs[1]) ;
			}
			eval('if(!('.$cond.')) { $var = $default_value ; }') ;
		}
		elseif($type == 'url' || $type == 'same_domain')
		{
			$var = match_url($var) ;
			if(! $var)
				$var = $default_value ;
			($var && $type == 'same_domain') ? ($var = (has_domain($var, \Pv\Misc::get_current_url(0))) ? $var : $default_value) : 1 ;
		}
		else
		{
			trim($var) ;
			rtrim($var) ;
			settype($var, 'string') ;
			switch($type)
			{
				case 'date' :
					$default_value = (! $default_value) ? date('Y-m-d') : $default_value ;
					$var = (preg_match('/^(\d\d\d\d-\d\d?-\d\d?)/', $var, $match)) ? $match[1] : $default_value ;
					if($var != $default_value) {
						(strtotime($var) < strtotime('1970-01-01')) ? $var = $default_value : 1 ;
					}
					break ;
				case 'time' :
					$default_value = (! $default_value) ? date('H:i:s') : $default_value ;
					$var = (preg_match('/^(\d\d?\:\d\d?\:\d\d?)/', $var, $match)) ? $match[1] : $default_value ;
					break ;
/*
				case 'datetime' :
					$default_value = (! $default_value) ? date('Y-m-d H:i:s') : $default_value ;
					$var = (preg_match('/^(\d\d\d\d\-\d\d?\-\d\d? \d\d?\:\d\d?\:\d\d?)/', $var, $match)) ? $match[1] : (preg_match('/^(\d\d\d\d\-\d\d?\-\d\d? \d\d?\:\d\d?)/', $var, $match)) ? $match[1].'\:00' : (preg_match('/^(\d\d\d\d\-\d\d?\-\d\d? \d\d?)/', $var, $match)) ? $match[1].'\:00\:00' : (preg_match('/^(\d\d\d\d\-\d\d?\-\d\d?/', $var, $match)) ? $match[1].' 00:00:00' : $default_value ;
					if($var != $default_value) {
						(strtotime($var) < strtotime('1970-01-01')) ? $var = $default_value : 1 ;
					}
					break ;
*/
				case 'person_name' :
					$var = preg_replace('/[[:space:]][[:space:]]+/', ' ', $var) ;
					$var = (preg_match('/^([a-z][a-z0-9&;éèçàêîâûïüôöü \']+)$/i', $var, $match)) ? $match[1] : $default_value ;
					break ;
				case 'user_name' :
					$var = (\Pv\Misc::validate_name_user_format($var)) ? $var : $default_value ;
					break ;
				case 'password' :
					$var = (\Pv\Misc::validate_password_format($var)) ? $var : $default_value ;
					break ;
				case 'action_name' :
					$var = (preg_match('/^([a-z0-9_\-]+)$/i', $var, $match)) ? $match[1] : $default_value ;
					break ;
				case 'relative_path' :
					$var = (preg_match('/^([^<>\*\+\?\^\:\|"]+)/i', $var, $match)) ? $match[1] : $default_value ;
					break ;
				case 'path' :
					$var = (preg_match('/^([^<>\*\^\|"\']+)/i', $var, $match)) ? $match[1] : $default_value ;
					break ;
				case 'normal_text' :
					$var = strip_tags($var) ;
					break ;
				case 'password' :
					$var = strip_tags($var) ;
					break ;
				case 'html_content' :
					$var = remove_suspicious_html($var) ;
					break ;
				case 'abs_url' :
					$var = (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $var, $match)) ? $match[0] : $var ;
					break ;
				case 'email' :
					$var = (\Pv\Misc::validate_email_format($var)) ? $var : $default_value ;
					break ;
				case 'intbool' :
					$var = (intval($var)) ? 1 : 0 ;
					break ;
			}
		}
		return $var ;
	}
	public static function _cast_array_ref(& $data, $defs=array())
	{
		$key_names = array_keys($defs) ;
		$data = \Pv\Misc::array_assign_value($data, $key_names, '') ;
		$ok = 1 ;
		while(list($i, $key_name) = each($key_names))
		{
			$data[$key_name] = \Pv\Misc::_cast_value($data[$key_name], $defs[$key_name], null) ;
			if($ok)
			{
				(! $data[$key_name]) ? $ok = 0 : 1 ;
			}
		}
		return $ok ;
	}
	public static function _declare_array(& $data, $defs=array())
	{
		return \Pv\Misc::_cast_array_ref($data, $defs) ;
	}
	
	public static function _html_debug($var, $exit_after=false)
	{
		print '<pre>'.print_r($var, true).'</pre>' ;
		if($exit_after)
			exit ;
	}
	
	public static function validate_name_user_format($name_user)
	{
		$ok = 1 ;
		if($name_user == '')
		{
			return 0 ;
		}
		if(! preg_match('/^[a-zA-Z0-9\_\.]{4,}$/', $name_user))
		{
			$ok = 0 ;
		}
		return $ok ;
	}
	public static function validate_password_format($password)
	{
		$ok = 1 ;
		if($password == '')
		{
			return 0 ;
		}
		if(! preg_match('/^[a-zA-Z0-9\_\/@:\^\\#\|\-]{4,}$/', $password))
		{
			$ok = 0 ;
		}
		return $ok ;
	}
	public static function validate_email_format($email)
	{
		$ok = 1 ;
		if($email == '')
		{
			return 0 ;
		}
		if(! preg_match('/^[a-z0-9\_\.]{4,}@[a-z0-9_\.\-]{2,}$/i', $email))
		{
			$ok = 0 ;
		}
		return $ok ;
	}
	public static function validate_url_format($url)
	{
		$ok = 0 ;
		if(preg_match('@^[a-z]+[:\|]+[\\/]+(.+)$@i', $url))
		{
			$ok = 1 ;
		}
		return $ok ;
	}
	public static function validate_action_name_format($text)
	{
		$OK = 1 ;
		if(preg_match('/[^a-z0-9_]/', $text))
		{
			$OK = 0 ;
		}
		return $OK ;
	}
	public static function validate_file_path_format($path)
	{
		$ok = 0 ;
		if(preg_match('@^[a-z]{2,}(:|\|){1}(\\|/)+(.)+$@i', $path))
		{
			$ok = 1 ;
		}
		return $ok ;
	}
	
	// Remove HTML contents
	public static function remove_html($text)
	{
		return strip_tags($text) ;
	}
	public static function remove_invisible_html($text)
	{
		$result = $text ;
		$var = preg_replace('@<\?php[^>]*?>.*?</script>@si', '', $var) ;
		$var = preg_replace('@<\!--[^>]*?>.*?-->@si', '', $var) ;
		$result = preg_replace('@<script[^>]*?>.*?</script>@si', '', $result) ;
		$result = preg_replace('@<style[^>]*?>.*?</style>@si', '', $result) ;
		$result = preg_replace('@<object[^>]*?>.*?</object>@si', '', $result) ;
		$result = preg_replace('@<embed[^>]*?>.*?</embed>@si', '', $result) ;
		$result = preg_replace('@<applet[^>]*?>.*?</applet>@si', '', $result) ;
		$result = preg_replace('@<noframes[^>]*?>.*?</noframes>@si', '', $result) ;
		$result = preg_replace('@<noscript[^>]*?>.*?</noscript>@si', '', $result) ;
		$result = preg_replace('@<noembed[^>]*?>.*?</noembed>@si', '', $result) ;
		$result = preg_replace('@<iframe[^>]*?>.*?</iframe>@si', '', $result) ;
		$result = preg_replace('@<frame[^>]*?>.*?</frame>@si', '', $result) ;
		$result = preg_replace('@<frameset[^>]*?>.*?</script>@si', '', $result) ;
		return $result ;
	}
	public static function remove_suspicious_html($text)
	{
		$result = $text ;
		$result = \Pv\Misc::remove_invisible_html($result) ;
		$result = str_ireplace('javascript:', '', $result) ;
		return $result ;
	}
	
	public static function get_current_url()
	{
		if(! isset($_SERVER["SERVER_NAME"]))
		{
			return "" ;
		}
		$protocol = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "") ? "https" : "http" ;
		$url = $protocol."://".$_SERVER['SERVER_NAME'] ;
		if($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443')
		{
			$url .= ':'.$_SERVER['SERVER_PORT'] ;
		}
		$url .= $_SERVER['REQUEST_URI'] ;
		return $url ;
		// return apply_host_alias($url) ;
	}
	public static function get_current_url_dir()
	{
		return \Pv\Misc::get_url_dir(\Pv\Misc::get_current_url()) ;
	}
	public static function get_url_dir($current_url)
	{
		$current_url_dir = '' ;
		$php_ext_pattern = '(.html)|(.php)|(.php3)|(.php4)|(.php5)|(.phtml)|(.inc)$' ;
		$current_url = preg_replace('/\?[^\?]+$/', '', $current_url) ;
		if(preg_match('@\/$@', $current_url))
		{
			$current_url_dir = preg_replace('@\/$@', '', $current_url) ;
		}
		elseif(preg_match('/'.$php_ext_pattern.'/', $current_url))
		{
			$current_url_dir = dirname($current_url) ;
		}
		else
		{
			$current_url_dir = $current_url ;
		}
		return $current_url_dir ;
	}
	public static function update_current_url_params($params=array(), $encodeValues=1, $forceDecodeParams=0)
	{
		return \Pv\Misc::update_url_params(\Pv\Misc::get_current_url(), $params, $encodeValues, $forceDecodeParams) ;
	}
	public static function update_url_params($url, $params=array(), $encodeValues=1, $forceDecodeParams=0)
	{
		$url_attrs = explode('?', $url, 2) ;
		$url_params = array() ;
		if(isset($url_attrs[1]))
		{
			if($url_attrs[1] != "")
			{
				if($forceDecodeParams)
				{
					parse_str($url_attrs[1], $url_params) ;
				}
				else
				{
					$url_params_data = explode('&', $url_attrs[1]) ;
					foreach($url_params_data as $i => $data_temp)
					{
						$attrs_temp = explode('=', $data_temp) ;
						$url_params[$attrs_temp[0]] = (isset($attrs_temp[1])) ? $attrs_temp[1] : '' ;
					}
				}
			}
		}
		$native_keys = array_keys($url_params) ;
		foreach($params as $n => $v)
		{
			if($v === null)
			{
				if(isset($url_params[$n]))
					unset($url_params[$n]) ;
			}
			else
			{
				$url_params[$n] = $v ;
			}
		}
		$url_res = $url_attrs[0] ;
		if(count($url_params))
		{
			$url_res .= '?' ;
			$i = 0 ;
			foreach($url_params as $n => $v)
			{
				if($i)
					$url_res .= '&' ;
				$paramValue = ($encodeValues && (! in_array($n, $native_keys) && ! $forceDecodeParams)) ? urlencode($v) : $v ;
				$url_res .= urlencode($n).'='.$paramValue ;
				$i++ ;
			}
		}
		return $url_res ;
	}
	public static function update_url_param($ParamName, $ParamValue, $URL)
	{
		return \Pv\Misc::update_url_params($URL, array($ParamName => $ParamValue)) ;
	}
	public static function remove_last_trailing_slash($url)
	{
		$result = $url ;
		// $result = preg_replace('@/|\\$@', '', $result) ;
		return $result ;
	}
	public static function make_abs_url($url, $base, $relative_to='.')
	{
		$result = $url ;
		$base = \Pv\Misc::remove_last_trailing_slash($base) ;
		$relative_to = \Pv\Misc::remove_last_trailing_slash($relative_to) ;
		if(\Pv\Misc::is_abs_url($result))
		{
			return $result ;
		}
		if(! file_exists($result))
		{
			return '' ;
		}
		$result = \Pv\Misc::remove_last_trailing_slash($result) ;
		if(\Pv\Misc::is_abs_url($base))
		{
			$result = $base.'/'.$result ;
		}
		else
		{
			if($relative_to == '')
			{
				$result = $base.'/'.$result ;
			}
			else
			{
				$result = $base.'/'.$relative_to.'/'.$result ;
			}
		}
		return $result ;
	}
	public static function is_abs_url($url)
	{
		$ok = 0 ;
		if(preg_match('/^[a-z]+\:/i', $url))
		{
			$ok = 1 ;
		}		
		return $ok ;
	}
	public static function is_same_url($urlLeft, $urlRight)
	{
		if($urlLeft == $urlRight)
			return true ;
		$urlPartsLeft = @parse_url($urlLeft) ;
		$urlPartsRight = @parse_url($urlRight) ;
		if($urlPartsLeft == false or $urlPartsRight == false)
		{
			return false ;
		}
		if(\Pv\Misc::_value_def($urlPartsLeft, 'scheme') != \Pv\Misc::_value_def($urlPartsRight, 'scheme'))
			return false ;
		if(\Pv\Misc::_value_def($urlPartsLeft, 'host') != \Pv\Misc::_value_def($urlPartsRight, 'host'))
			return false ;
		if(\Pv\Misc::_value_def($urlPartsLeft, 'port') != \Pv\Misc::_value_def($urlPartsRight, 'port'))
			return false ;
		if(\Pv\Misc::_value_def($urlPartsLeft, 'user') != \Pv\Misc::_value_def($urlPartsRight, 'user'))
			return false ;
		if(\Pv\Misc::_value_def($urlPartsLeft, 'pass') != \Pv\Misc::_value_def($urlPartsRight, 'pass'))
			return false ;
		if(\Pv\Misc::_value_def($urlPartsLeft, 'path') != \Pv\Misc::_value_def($urlPartsRight, 'path'))
			return false ;
		$queryLeftVal = \Pv\Misc::_value_def($urlPartsLeft, 'query') ;
		$queryRightVal = \Pv\Misc::_value_def($urlPartsRight, 'query') ;
		if($queryLeftVal == $queryRightVal)
			return true ;
		@parse_str($queryLeftVal, $queryLeft) ;
		@parse_str($queryRightVal, $queryRight) ;
		if(! is_array($queryLeft) || ! is_array($queryRight))
			return false ;
		// print_r($queryLeft) ;
		return (count(array_diff($queryLeft, $queryRight)) == 0) ;
	}
	// get remote file last modification date (returns unix timestamp)
	public static function GetRemoteLastModified( $uri )
	{
		return 0 ;
			// default
			$unixtime = 0;
			
			$fp = @fopen( $uri, "r" );
			if( !$fp ) {return 0;}
			
			$MetaData = stream_get_meta_data( $fp );
					
			foreach( $MetaData['wrapper_data'] as $response )
			{
					// case: redirection
					if( substr( strtolower($response), 0, 10 ) == 'location: ' )
					{
							$newUri = substr( $response, 10 );
							fclose( $fp );
							return GetRemoteLastModified( $newUri );
					}
					// case: last-modified
					elseif( substr( strtolower($response), 0, 15 ) == 'last-modified: ' )
					{
							$unixtime = strtotime( substr($response, 15) );
							break;
					}
			}
			fclose( $fp );
			return $unixtime;
	}
	public static function force_array(& $data)
	{
		if(! is_array($data))
		{
			$temp = $data ;
			if(is_object($temp))
			{
				$data = get_object_vars($temp) ;
			}
			elseif($temp === null)
			{
				$data = array() ;
			}
			else
			{
				$data = array($temp) ;
			}
		}
	}
	public static function force_array_rec(& $data)
	{
		force_array($data) ;
		foreach($data as $n => &$val)
		{
			if(is_object($val) || is_array($val))
			{
				force_array_rec($val) ;
			}
		}
	}
	public static function force_object(& $data)
	{
		$result = new StdClass ;
		if(is_array($result))
		{
			$result = conv_array_to_object($data) ;
		}
		return $result ;
	}
	public static function & conv_array_to_object(& $data)
	{
		$result = new StdClass ;
		if(! is_array($data))
		{
			return $result ;
		}
		foreach($data as $n => $v)
		{
			$result->$n = $v ;
		}
		return $result ;
	}
	public static function check_url($url)
	{
		$url = parse_url($url);
		if ( ! $url) {
			return false;
		}
		$url = array_map('trim', $url);
		$url['port'] = (!isset($url['port'])) ? 80 : (int)$url['port'];
		$path = (isset($url['path'])) ? $url['path'] : '';
		if ($path == '')
		{
			$path = '/';
		}
		$path .= ( isset ( $url['query'] ) ) ? "?$url[query]" : '';
		if ( isset ( $url['host'] ) )
		{
			// _d_step($url, __LINE__, __FILE__) ;
			if ( PHP_VERSION >= 5 )
			{
				$headers = get_headers("$url[scheme]://$url[host]:$url[port]$path");
			}
			else
			{
				$fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 30);
				if ( ! $fp )
				{
					return false;
				}
				fputs($fp, "HEAD $path HTTP/1.1\r\nHost: $url[host]\r\n\r\n");
				$headers = fread ( $fp, 128 );
				fclose ( $fp );
			}
			$headers = ( is_array ( $headers ) ) ? implode ( "\n", $headers ) : $headers;
			return ( bool ) preg_match ( '#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers );
		}
		return true;
	}
	
	public static function _html_value($text)
	{
		$result = \Pv\Misc::protect_quote($text) ;
		$result = str_replace("'", "&#39;", $result) ;
		$result = str_replace("é", "&eacute;", $result) ;
		return $result ;
	}
	public static function protect_quote($text)
	{
		return str_replace("\"", "&quot;", $text) ;
	}
	
	public static function _parse_pattern($pattern, $data=array(), $prefix="")
	{
		if(! is_string($pattern))
		{
			$pattern = "$pattern" ;
		}
		//_d_info("rrrr") ;
		if(! is_array($data))
		{
			$data = array($data) ;
		}
		$result = $pattern ;
		//_d_info("sss") ;
		foreach($data as $n => $v)
		{
			if(is_array($v) || is_object($v))
			{
				$result = _parse_pattern($result, $prefix.".".$n, $v) ;
			}
			else
			{
				$v = "$v" ;
				$pattern_prefix = "" ;
				if($prefix != "")
				{
					$pattern_prefix = $prefix."." ;
				}
				$result = str_ireplace("\${".$pattern_prefix.$n."}", $v, $result) ;
			}
		}
		// _d_step($result) ;
		//_d_info("mmmm") ;
		return $result ;
	}
	public static function extract_array_without_prefix($haystack, $prefix='')
	{
		return \Pv\Misc::extract_array_without_vertices($haystack, $prefix, '') ;
	}
	public static function extract_array_without_suffix($haystack, $suffix='')
	{
		return \Pv\Misc::extract_array_without_vertices($haystack, '', $suffix) ;
	}
	public static function extract_array_without_vertices($haystack, $prefix='', $suffix='')
	{
		$result = array() ;
		foreach($haystack as $n => $v)
		{
			$key = $n ;
			$ok = 1 ;
			if($prefix != '')
			{
				if(preg_match('/^'.$prefix.'/', $n))
				{
					$ok = 0 ;
				}
			}
			if($suffix != '')
			{
				if(preg_match('/'.$suffix.'$/', $n))
				{
					$ok = 0 ;
				}
			}
			if($ok)
			{
				$result[$key] = $v ;
			}
		}
		return $result ;
	}
	public static function extract_array_without_keys($haystack, $keys)
	{
		$result = array() ;
		foreach($haystack as $key => $val)
		{
			if(! in_array($key, $keys))
			{
				$result[$key] = $haystack[$key] ;
			}
		}
		return $result ;
	}
	public static function array_extract_value_for_keys($haystack, $keys)
	{
		$result = array() ;
		if(count($haystack) == 0 || count($keys) == 0)
		{
			return $result ;
		}
		foreach($keys as $i => $key)
		{
			if(isset($haystack[$key]))
			{
				$result[$key] = $haystack[$key] ;
			}
		}
		return $result ;
	}
	public static function remove_url_params($url)
	{
		$attrs = explode("?", $url, 2) ;
		return $attrs[0] ;
	}
	public static function http_build_query_string($data, $prefix='', $sep='', $key='', $raw=false) {
		if(! $data)
		{
			return '' ;
		}
		$ret = array();
		foreach ((array)$data as $k => $v) {
			if (is_int($k) && $prefix != null) {
				$k = (($raw) ? rawurlencode($prefix . $k) : urlencode($prefix . $k));
			}
			if ((!empty($key)) || ($key === 0))  $k = $key.'['.(($raw) ? rawurlencode($k) : urlencode($k)).']';
			if (is_array($v) || is_object($v)) {
				array_push($ret, http_build_query($v, '', $sep, $k));
			} else {
				array_push($ret, $k.'='.(($raw) ? rawurlencode(($v !== null) ? $v : '') : urlencode(($v !== null) ? $v : "")));
			}
		}
		if (empty($sep)) $sep = ini_get('arg_separator.output') ;
		return implode($sep, $ret);
	}// http_build_query//if
	public static function array_apply_vertices($haystack, $prefix='', $suffix='')
	{
		$result = array() ;
		foreach($haystack as $n => $v)
		{
			$result[$prefix.$n.$suffix] = $v ;
		}
		return $result ;
	}
	public static function array_apply_prefix($haystack, $prefix='')
	{
		return \Pv\Misc::array_apply_vertices($haystack, $prefix, '') ;
	}
	public static function array_apply_suffix($haystack, $suffix='')
	{
		return \Pv\Misc::array_apply_vertices($haystack, '', $suffix) ;
	}
	public static function extract_array_with_prefix($haystack, $prefix='')
	{
		return \Pv\Misc::extract_array_with_vertices($haystack, $prefix, '') ;
	}
	public static function extract_array_with_suffix($haystack, $suffix='')
	{
		return \Pv\Misc::extract_array_with_vertices($haystack, '', $suffix) ;
	}
	public static function extract_array_with_vertices($haystack, $prefix='', $suffix='')
	{
		$result = array() ;
		foreach($haystack as $n => $v)
		{
			$key = $n ;
			$ok = 1 ;
			if($prefix != '')
			{
				if(! preg_match('/^'.$prefix.'/', $n))
				{
					$ok = 0 ;
				}
				else
				{
					$key = preg_replace('/^'.$prefix.'/', '', $key) ;
				}
			}
			if($suffix != '')
			{
				if(! preg_match('/'.$suffix.'$/', $n))
				{
					$ok = 0 ;
				}
				else
				{
					$key = preg_replace('/'.$suffix.'$/', '', $key) ;
				}
			}
			if($ok)
			{
				$result[$key] = $v ;
			}
		}
		return $result ;
	}
	public static function slugify($text,$strict = false, $sep=' ')
	{
		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d.]+~u', '-', $text);

		// trim
		$text = trim($text, '-');
		setlocale(LC_CTYPE, 'en_GB.utf8');
		// transliterate
		if (function_exists('iconv')) {
		   $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		}

		// lowercase
		$text = strtolower($text);
		// remove unwanted characters
		$text = preg_replace('~[^-\w.]+~', '', $text);
		if (empty($text)) {
		   return '';
		}
		if ($strict) {
			$text = str_replace(".", "_", $text);
		}
		$text = str_replace("-", $sep, $text) ;
		return $text;
	}
	public static function intro($Text, $NbWords = 255, $More = "...")
	{
		$Text = strip_tags($Text) ;
		$RetIntro = substr($Text, 0, $NbWords) ;
		if (strlen($Text) < $NbWords)
		{
			return $Text ;
		}
		if ($Text[$NbWords - 1] != ' ' and strlen($Text) > $NbWords)
		{
			for ($i=$NbWords; $i<strlen($Text) and ($Text[$i] != " "); $i++)
			{
				$RetIntro .= $Text[$i] ;
			}
		}
		$RetIntro .= $More ;
		return $RetIntro ;
	}
	public static function popularKeywords($text, $maxKeywords=8, $minKeywordLength=4)
	{
		// Replace all non-word chars with comma
		$pattern = '/[0-9\W]/';
		$text = preg_replace($pattern, ',', $text);

		// Create an array from $text
		$text_array = explode(",",$text);
		$keywords = array();
		$keyCounts = array();

		// remove whitespace and lowercase words in $text
		$text_array = array_map("Pv\\Misc\\popularKeywords_clearWord", $text_array);

		foreach ($text_array as $term) {
			if(strlen($term) < $minKeywordLength)
				continue ;
			if(! isset($keyCounts[$term]))
				$keyCounts[$term] = 0 ;
			$keyCounts[$term]++ ;
		};
		if(count($keyCounts) <= $maxKeywords)
		{
			return array_keys($keyCounts) ;
		}
		arsort($keyCounts) ;
		
		$keywords = array_slice(array_keys($keyCounts), 0, $maxKeywords) ;
		return $keywords ;
	}
	public static function popularKeywords_clearWord($x)
	{
		return trim(strtolower($x));
	}
}