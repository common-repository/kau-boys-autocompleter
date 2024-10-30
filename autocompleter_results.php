<?php
	
	if(WP_DEBUG){
		error_reporting(E_ALL);
	} else {
		error_reporting(0);		
	}

	header('Content-Type: text/html; charset=utf-8');
	
	// remove the filter functions from relevanssi
	if(function_exists('relevanssi_kill')) remove_filter('posts_where', 'relevanssi_kill');
	if(function_exists('relevanssi_query')) remove_filter('the_posts', 'relevanssi_query');
	if(function_exists('relevanssi_kill')) remove_filter('post_limits', 'relevanssi_getLimit');
	
	$choices = get_option('kau-boys_autocompleter_choices');
	$framework = get_option('kau-boys_autocompleter_framework');
	$encoding = get_option('kau-boys_autocompleter_encoding');
	$searchfields = get_option('kau-boys_autocompleter_searchfields');
	$resultfields = get_option('kau-boys_autocompleter_resultfields');
	$titlelength = get_option('kau-boys_autocompleter_titlelength');
	$contentlength = get_option('kau-boys_autocompleter_contentlength');
	if(empty($choices)) $choices = 10;
	if(empty($framework)) $framework = 'jQuery';
	if(empty($encoding)) $encoding = 'UTF-8';
	if(empty($searchfields)) $searchfields = 'both';
	if(empty($resultfields)) $resultfields = 'both';
	if(empty($titlelength)) $titlelength = 50;
	if(empty($contentlength)) $contentlength = 120;
	
	mb_internal_encoding($encoding);
	mb_regex_encoding($encoding);
	
	$words = '%'.$_REQUEST['q'].'%';
	
	switch($searchfields){
		case 'post_title' : 
			$where = 'post_title LIKE "'.$words.'"';
			break;
		case 'post_content' : 
			$where = 'post_content LIKE "'.$words.'"';
		default :
			$where = 'post_title LIKE "'.$words.'" OR post_content LIKE "'.$words.'"';
	}
	
	$wp_query = new WP_Query();
	$wp_query->query(array(
		's' => $_REQUEST['q'],
		'showposts' => $choices,
		'post_status' => 'publish'
	));
	$posts = $wp_query->posts;

	$results = array();
	foreach ($posts as $key => $post){
		setup_postdata($post);
		$title = strip_tags(html_entity_decode(get_the_title($post->ID), ENT_NOQUOTES, 'UTF-8'));
		$content = strip_tags(strip_shortcodes(html_entity_decode(get_the_content($post->ID), ENT_NOQUOTES, 'UTF-8')));
		if(mb_strpos(mb_strtolower(($searchfields == 'post_title')? $title : (($searchfields == 'post_content')? $content : $title.$content)), mb_strtolower($_REQUEST['q'])) !== false){
			$results[] = array(
				'url' => get_permalink($post->ID),
				'title' => highlightSearchString(strtruncate($title, $titlelength, true), $_REQUEST['q']),
				'content' => (($resultfields == 'both')? highlightSearchString(strtruncate($content, $contentlength, false, '[...]', $_REQUEST['q']), $_REQUEST['q']) : '')
			);
		}
	}
	printResults($results, $framework);
			
	
	function highlightSearchString($value, $searchString){
		
		if((version_compare(phpversion(), '5.0') < 0) && (strtolower(mb_internal_encoding()) == 'utf-8')){
			$value = utf8_encode(html_entity_decode(utf8_decode($value)));
		}
		
		$regex_chars = '\.+?(){}[]^$';
		for ($i=0; $i<mb_strlen($regex_chars); $i++) {
			$char = mb_substr($regex_chars, $i, 1);
			$searchString = str_replace($char, '\\'.$char, $searchString);
		}
		$searchString = '(.*)('.$searchString.')(.*)';
		return mb_eregi_replace($searchString, '\1<span class="ac_match">\2</span>\3', $value);
	}
	
	function strtruncate($str, $length = 50, $cutWord = false, $suffix = '...', $needle = ''){
		
		$str = trim($str);
		if((version_compare(phpversion(), '5.0') < 0) && (strtolower(mb_internal_encoding()) == 'utf-8')){
			$str = utf8_encode(html_entity_decode(utf8_decode($str)));
		}else{
			$str = html_entity_decode($str, ENT_NOQUOTES, mb_internal_encoding());
		}
		
		
		if(mb_strlen($str)>$length){
			if(!empty($needle) && mb_strpos(mb_strtolower($str), mb_strtolower($needle)) > 0){
				$pos = mb_strpos(mb_strtolower($str), mb_strtolower($needle)) + (mb_strlen($needle) / 2);
				$startToShort = ($pos - ($length / 2)) < 0;
				$endToShort = ($pos + ($length / 2)) > mb_strlen($str);
				
				// build the prefix and suffix
				$prefix = $suffix;
				if($startToShort){
					$prefix = '';
				}
				if($endToShort){
					$suffix = '';
				}
				
				// set maximum length
				$length = $length - mb_strlen($prefix) - mb_strlen($suffix);
				
				// get the start
				if($startToShort){
					$start = 0;
				} elseif($endToShort){
					$start = mb_strlen($str) - $length;
				} else {
					$start = $pos - ($length / 2);
				}
				
				
				// shorten the string
				$string = mb_substr($str, $start, $length);
				
				if($cutWord){
					return $prefix.$string.$suffix;
				} else {
					$firstWhitespace = ($startToShort)? 0 : mb_strpos($string, ' ');
					$lastWhitespace =($endToShort)? mb_strlen($string) :  mb_strrpos($string, ' ');
					return $prefix.' '.(!empty($lastWhitespace)? mb_substr($string, $firstWhitespace, ($lastWhitespace - $firstWhitespace)) : $string).' '.$suffix;
				}
			} else {
				$string = mb_substr($str, 0, $length - mb_strlen($suffix));
				return (($cutWord) ? $string : mb_substr($string, 0, mb_strrpos($string, ' ')).' ').$suffix;
			}
		} else {
			return $str;	
		}
	}
	
	function printResults($results, $framework){
		if($framework == 'scriptaculous'){			
			echo '<ul>';
			foreach($results as $result){
				echo '	<li>
							<a href="'.$result['url'].'">
								<span class="title">'.$result['title'].'</span>
								<span style="display: block;">'.$result['content'].'</span>
							</a>
						</li>';
			}
			echo '</ul>';
		} else {	
			foreach($results as $result){
				echo str_replace(array("\n", "\r", '|'), array(' ',' ', '&#124;'), '<span class="title">'.$result['title'].'</span><p>'.$result['content'].'</p>')
					.'|'
					.str_replace(array("\n", "\r", '|'), array(' ',' ', '&#124;'), $result['url'])
					."\n";
			}
		}
	}
	
?>