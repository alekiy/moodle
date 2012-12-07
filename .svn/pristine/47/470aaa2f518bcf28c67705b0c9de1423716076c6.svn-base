<?php
/**
 * Save the settings into the settings.xml file
 * Author:
 * 	Adrien Jamot  (adrien_jamot [at] symetrix [dt] fr)
 * 
 * @package   mod_richmedia
 * @copyright 2011 Symetrix
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
require_once("../../config.php");

if (isset($_POST['movie']) && isset($_POST['presentername']) && isset($_POST['steps']) && isset($_POST['title']) && isset($_POST['contextid'])){

	$video 			= $_POST['movie'];
	$presentertitle	= $_POST['presentertitle'];
	$presentername 	= $_POST['presentername'];
	$tabsteps 		= $_POST['steps'];
	$title 			= $_POST['title'];
	$contextid 		= $_POST['contextid'];
	$update 		= $_POST['update'];
	$color 			= $_POST['fontcolor'];
	$font 			= $_POST['font'];
	$defaultview 	= $_POST['defaultview'];
	$autoplay 		= $_POST['autoplay'];
    
	$module = $DB->get_record('course_modules',array('id'=>$update));
	$courserichmedia = $DB->get_record('richmedia',array('id'=>$module->instance));
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><settings></settings>');
	$movie = $xml->addChild('movie');
	$movie->addAttribute('src','video/'.$video);
	
	$design = $xml->addChild('design');
	$design->addAttribute('logo','logo.jpg');
	$design->addAttribute('font',$font);
	$design->addAttribute('background','background.jpg');
	$design->addAttribute('fontcolor','0x'.$color);
	
	$options = $xml->addChild('options');
	$options->addAttribute('presenter','1');
	$options->addAttribute('comment','0');
	$options->addAttribute('defaultview',$defaultview);
	$options->addAttribute('btnfullscreen','true');
	$options->addAttribute('btninverse','false');
	if (!$autoplay || $autoplay == 0){
		$autoplayxml = "false";
	}
	else {
		$autoplayxml = "true";
	}
	$options->addAttribute('autoplay',$autoplayxml);
	
	$presenter = $xml->addChild('presenter');
	$presenter->addAttribute('name',html_entity_decode($presentername));
	$presenter->addAttribute('biography',strip_tags(html_entity_decode($courserichmedia->intro)));
	$presenter->addAttribute('title',html_entity_decode($presentertitle));
	
	$titles = $xml->addChild('titles');
	$title1 = $titles->addChild('title');
	$title1->addAttribute('target','fdPresentationTitle');
	$title1->addAttribute('label',html_entity_decode($title));
	$title2 = $titles->addChild('title');
	$title2->addAttribute('target','fdMovieTitle');
	$title2->addAttribute('label','');
	$title3 = $titles->addChild('title');
	$title3->addAttribute('target','fdSlideTitle');
	$title3->addAttribute('label','');
	
	$steps = $xml->addChild('steps');
	//traitement des steps
	
	$tabsteps = substr($tabsteps,1); // on enleve le 1er caractere
	$tabsteps = substr($tabsteps,0,-1); // on enleve le dernier caractere
	$tabsteps = str_replace('\"','',$tabsteps);
	$tabsteps = stripslashes($tabsteps);
	$tabsteps = explode("]",$tabsteps);
	for($i = 0;$i< count($tabsteps) -1;$i++){
		if ($i == 0) {
			$element = substr($tabsteps[$i],1);
		}	
		else {
			$element = substr($tabsteps[$i],2);
		}
		$step = $steps->addChild('step');
		$id = substr($element,0,strpos($element,','));
		$step->addAttribute('id',$id);
		$element = substr($element,strpos($element,',')+2);	
		
		$label = substr($element,0,strpos($element,'"'));
		$step->addAttribute('label',html_entity_decode($label));
		$element = substr($element,strpos($element,'"')+3);	
		
		$comment = substr($element,0,strpos($element,'"'));
		$step->addAttribute('comment',html_entity_decode($comment));
		$element = substr($element,strpos($element,'"')+3);	
		
		$framein = substr($element,0,strpos($element,'"'));
		$tabframein = explode(':',$framein);
		$framein = 60 * $tabframein[0] + $tabframein[1];
		$step->addAttribute('framein',$framein);
		$element = substr($element,strpos($element,',')+2);		
		
		$slide = substr($element,0,strpos($element,'"'));
		$step->addAttribute('slide',$slide);
		$end = substr($element, -2,1);
		if($end != '"'){
			if ($end == 1 || $end == 2 || $end == 3){
				$step->addAttribute('view',$end);
			}	
		}
		else {
			$step->addAttribute('view','');
		}
	}
	
	$fs = get_file_storage();
		 
	// Prepare file record object
	$fileinfo = new stdClass();
	$fileinfo->component = 'mod_richmedia';
	$fileinfo->filearea  = 'content';
	$fileinfo->contextid = $contextid;
	$fileinfo->filepath = '/';
	$fileinfo->itemid = 0;
	$fileinfo->filename = 'settings.xml';
	// Get file
	$file = $fs->get_file($fileinfo->contextid, $fileinfo->component, $fileinfo->filearea, 
			$fileinfo->itemid, $fileinfo->filepath, $fileinfo->filename);
	if($file){
		$file->delete();
	}
	$fs->create_file_from_string($fileinfo, $xml->asXML());
	
	if (!strpos($courserichmedia->referencesxml,'.xml')){
		$courserichmedia->referencesxml = 'settings.xml';
	}
	$DB->update_record('richmedia',$courserichmedia);
}
else {
	echo 1;
}
?>