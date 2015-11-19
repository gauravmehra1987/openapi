<?php

function static_url($display=false){
    if($display){
        echo base_url() . 'static';
    }else{
        return base_url() . 'static';
    }
}


function stylesheet(){
    echo '<link href="' .base_url() . 'css/style.css" rel="stylesheet" />';
}

function add_stylesheet($styles){
    if($styles){
        foreach($styles as $style){
          echo '<link href="' .base_url() . 'static/'.$style.'.css" rel="stylesheet" />'."\n";
        }
    }
}

function add_scripts($scripts){
    if($scripts){
      foreach($scripts as $script){
        echo '<script src="' .base_url() .$script.'.js" type="text/javascript" /> </script>'."\n";
      }
    }
}

function enque_script($script){
        echo '<script src="' .site_url($script) .'" type="text/javascript" /> </script>'."\n";
}

function img_src($file,$theme=true){
    if($theme){
      echo base_url() . 'static/image/'.$file;
    }else{
      echo base_url().$file;
    }
}


function parse_args( $args ,$defaults){
	if ( is_object( $args ) )
		$r = get_object_vars( $args );
	elseif ( is_array( $args ) )
		$r =& $args;
       
	if ( is_array( $defaults ) )
            	return array_merge( $defaults, $r );
	return $r;
}

function xml2array($fname){
  $sxi = new SimpleXmlIterator($fname, null, true);
  return sxiToArray($sxi);
}

function sxiToArray($sxi){
  $a = array();
  for( $sxi->rewind(); $sxi->valid(); $sxi->next() ) {
    if(!array_key_exists($sxi->key(), $a)){
      $a[$sxi->key()] = array();
    }
    if($sxi->hasChildren()){
      $a[$sxi->key()][] = sxiToArray($sxi->current());
    }
    else{
      $a[$sxi->key()][] = strval($sxi->current());
    }
  }
  return $a;
}


function seoUrl($string) {

        $string = strtolower($string);

        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);

        $string = preg_replace("/[\s-]+/", " ", $string);

        $string = preg_replace("/[\s_]/", "-", $string);

        return $string;
}

function flash_message(){
  
	$ci =& get_instance();
	$flashmsg = $ci->session->flashdata('message');

	$html = '';
	if (is_array($flashmsg)){
		$html = '<div id="flashmessage" class="'.$flashmsg['type'].'">
			<img style="float: right; cursor: pointer" id="closemessage" src="'.base_url().'images/cross.png" />
			<strong>'.$flashmsg['title'].'</strong>
			<p>'.$flashmsg['content'].'</p>
			</div>';
	}
	return $html;
}

function p($var,$exit=true){
   print_r($var);
   if($exit) exit;
}

function date_in_range($start_date, $end_date, $needle){
  $start_ts = strtotime($start_date);
  $end_ts = strtotime($end_date);
  $needle = strtotime($needle);
  return (($needle >= $start_ts) && ($needle <= $end_ts));
}
