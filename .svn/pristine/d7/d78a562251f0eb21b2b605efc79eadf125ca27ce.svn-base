<?php
/**
 *
 * Author:
 * 	Adrien Jamot  (adrien_jamot [at] symetrix [dt] fr)
 * 
 * @package   mod_richmedia
 * @copyright 2011 Symetrix
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
 
	define('RICHMEDIA_TYPE_LOCAL', 'local');
	
	/**
	** Ajoute une instance de RICH MEDIA
	**/
	function richmedia_add_instance($richmedia,$mform=null){
		global $CFG,$DB;
		require_once($CFG->dirroot.'/mod/richmedia/locallib.php');
		$cmid       = $richmedia->coursemodule;
		$cmidnumber = $richmedia->cmidnumber;
		$courseid   = $richmedia->course;
        
        if (is_int($richmedia->referenceslides)){
            $richmedia->referenceslides = '';
        }
        if (is_int($richmedia->referencesxml)){
            $richmedia->referencesxml = '';
        }
        if (is_int($richmedia->referencesvideo)){
            $richmedia->referencesvideo = '';
        }
        if (is_int($richmedia->referencesfond)){
            $richmedia->referencesfond = '';
        }
        
		$context = get_context_instance(CONTEXT_MODULE, $cmid);
		$id = $DB->insert_record('richmedia', $richmedia);
		
		$DB->set_field('course_modules', 'instance', $id, array('id'=>$cmid));
		$record = $DB->get_record('richmedia', array('id'=>$id));
		
		$fs = get_file_storage();
		if ($mform) {
			$filenameslides = $mform->get_new_filename('referenceslides');
			if ($filenameslides !== false) {
				$fs->delete_area_files($context->id, 'mod_richmedia', 'package');
				$mform->save_stored_file('referenceslides', $context->id, 'mod_richmedia', 'package', 0, '/', $filenameslides);
				$record->referenceslides = $filenameslides;
			}
			
			$filenamexml = $mform->get_new_filename('referencesxml');
			if ($filenamexml !== false) {
				$mform->save_stored_file('referencesxml', $context->id, 'mod_richmedia', 'content', 0, '/', $filenamexml);
				$record->referencesxml = $filenamexml;
			}
            else {
                $record->referencesxml = "settings.xml";
            }
			
			$filenamevideo = $mform->get_new_filename('referencesvideo');
			if ($filenamevideo !== false) {
				$mform->save_stored_file('referencesvideo', $context->id, 'mod_richmedia', 'content', 0, '/video/', $filenamevideo);
				$record->referencesvideo = $filenamevideo;
			}

			$filenamefond = $mform->get_new_filename('referencesfond');
			if ($filenamefond !== false) {
				$mform->save_stored_file('referencesfond', $context->id, 'mod_richmedia', 'picture', 0, '/', $filenamefond);
				$record->referencesfond = $filenamefond;
			}
		}	
	
		// Enregistrement
		$DB->update_record('richmedia', $record);
		
		$record->course     = $courseid;
		$record->cmidnumber = $cmidnumber;
		$record->cmid       = $cmid;
		
		// Traitement du zip
		richmedia_parse($record);
		
		richmedia_generate_xml($record);

		$fs->delete_area_files(13, 'user', 'draft');
		return $record->id;
	}
	
	/**
	**Mise a jour d'une instance
	**/
	function richmedia_update_instance($richmedia, $mform=null){

		global $CFG, $DB;

		require_once($CFG->dirroot.'/mod/richmedia/locallib.php');
		$cmid       = $richmedia->coursemodule;
		$cmidnumber = $richmedia->cmidnumber;
		$courseid   = $richmedia->course;
        
        if (is_int($richmedia->referenceslides)){
            $richmedia->referenceslides = '';
        }
        if (is_int($richmedia->referencesxml)){
            $richmedia->referencesxml = '';
        }
        if (is_int($richmedia->referencesvideo)){
            $richmedia->referencesvideo = '';
        }
        if (is_int($richmedia->referencesfond)){
            $richmedia->referencesfond = '';
        }

		$richmedia->id = $richmedia->instance;

		$context = get_context_instance(CONTEXT_MODULE, $cmid);
		$fs = get_file_storage();
		if ($mform) {
			$filenameslides = $mform->get_new_filename('referenceslides');
			
			if ($filenameslides !== false) {
				$richmedia->referenceslides = $filenameslides;
				$fs->delete_area_files($context->id, 'mod_richmedia', 'package');
				$mform->save_stored_file('referenceslides', $context->id, 'mod_richmedia', 'package', 0, '/', $filenameslides);
			}
			
			$filenamexml = $mform->get_new_filename('referencesxml');
			if ($filenamexml !== false) {
				$richmedia->referencesxml = $filenamexml;
				$fs->delete_area_files($context->id, 'mod_richmedia', 'content');
				$mform->save_stored_file('referencesxml', $context->id, 'mod_richmedia', 'content', 0, '/', $filenamexml);
			}
			
			$filenamevideo = $mform->get_new_filename('referencesvideo');
			if ($filenamevideo !== false) {
				$richmedia->referencesvideo = $filenamevideo;
				$fs->delete_area_files($context->id, 'mod_richmedia', 'video');
				$mform->save_stored_file('referencesvideo', $context->id, 'mod_richmedia', 'content', 0, '/video/', $filenamevideo);
			}
			
			$filenamepicture = $mform->get_new_filename('referencesfond');
			if ($filenamepicture !== false) {
				$richmedia->referencesfond = $filenamepicture;
				$fs->delete_area_files($context->id, 'mod_richmedia', 'picture');
				$mform->save_stored_file('referencesfond', $context->id, 'mod_richmedia', 'picture', 0, '/', $filenamepicture);
			}
		}		
	
		$DB->update_record('richmedia', $richmedia);

		$richmedia = $DB->get_record('richmedia', array('id'=>$richmedia->id));

	/// extra fields required in grade related functions
		$richmedia->course   = $courseid;
		$richmedia->idnumber = $cmidnumber;
		$richmedia->cmid     = $cmid;

		richmedia_parse($richmedia);
		
		richmedia_generate_xml($richmedia);
		
		return true;
	}
	
	/**
	**Supprime une instance de RICH MEDIA
	**/
	function richmedia_delete_instance($id){
		global $CFG, $DB;

		if (! $richmedia = $DB->get_record('richmedia', array('id'=>$id))) {
			return false;
		}

		$result = true;
		if (! $DB->delete_records('richmedia', array('id'=>$richmedia->id))) {
			$result = false;
		}
		return $result;
	}
	
	/**
	* get the infos of a file
	**/
	function richmedia_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
		global $CFG;

		if (!has_capability('moodle/course:managefiles', $context)) {
			return null;
		}

		$fs = get_file_storage();

		if ($filearea === 'video' || $filearea === 'picture') {

			$filepath = is_null($filepath) ? '/' : $filepath;
			$filename = is_null($filename) ? '.' : $filename;

			$urlbase = $CFG->wwwroot.'/pluginfile.php';
			if (!$storedfile = $fs->get_file($context->id, 'mod_richmedia', $filearea, 0, $filepath, $filename)) {
				if ($filepath === '/' and $filename === '.') {
					$storedfile = new virtual_root_file($context->id, 'mod_richmedia', $filearea, 0);
				} else {
					// not found
					return null;
				}
			}
			require_once("$CFG->dirroot/mod/richmedia/locallib.php");
			return new richmedia_package_file_info($browser, $context, $storedfile, $urlbase, $areas[$filearea], true, true, false, false);

		} else if ($filearea === 'package') {
			$filepath = is_null($filepath) ? '/' : $filepath;
			$filename = is_null($filename) ? '.' : $filename;

			$urlbase = $CFG->wwwroot.'/pluginfile.php';
			if (!$storedfile = $fs->get_file($context->id, 'mod_richmedia', 'package', 0, $filepath, $filename)) {
				if ($filepath === '/' and $filename === '.') {
					$storedfile = new virtual_root_file($context->id, 'mod_richmedia', 'package', 0);
				} else {
					// not found
					return null;
				}
			}
			return new file_info_stored($browser, $context, $storedfile, $urlbase, $areas[$filearea], false, true, false, false);
		}
		return false;
	}
	
	function richmedia_user_outline(){
		//not implemented yet
	}

	function richmedia_get_view_actions(){
		//not implemented yet
	}
	function richmedia_get_post_actions(){
		//not implemented yet
	}
	
	/**
	** concatene les elements d'un tableau dans une chaine de caractere
	**/
	function richmedia_concatenate($tab,$sep) {
		$result = '';
		for($i = 0;$i < count($tab);$i++){
			$result .= $tab[$i] . $sep;
		}
		$result = substr($result,0,strlen($result)-1);
		return $result;
	}
	
	/**
	**Renvoi un fichier
	**/
	function richmedia_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload) {
		global $CFG;
		if ($context->contextlevel != CONTEXT_MODULE) {
			return false;
		}
		require_login($course, true, $cm);
		$lifetime = isset($CFG->filelifetime) ? $CFG->filelifetime : 86400;
		if ($filearea === 'content') {
			$relativepath = richmedia_concatenate($args,'/');
			$fullpath = "/$context->id/mod_richmedia/content/0/$relativepath";

		}
		else if ($filearea === 'video') {
			$relativepath = richmedia_concatenate($args,'/');
			$fullpath = "/$context->id/mod_richmedia/content/video/0/$relativepath";
		}
		else if ($filearea === 'picture') {
			$relativepath = richmedia_concatenate($args,'/');
			$fullpath = "/$context->id/mod_richmedia/picture/0/$relativepath";
		}
		else if ($filearea === 'package') {
			if (!has_capability('moodle/course:manageactivities', $context)) {
				return false;
			}
			$relativepath = richmedia_concatenate($args,'/');
			$fullpath = "/$context->id/mod_richmedia/package/0/$relativepath";
			$lifetime = 0;
		} 
		else if ($filearea === 'zip') {
			$relativepath = richmedia_concatenate($args,'/');
			$fullpath = "/$context->id/mod_richmedia/zip/0/$relativepath";
		}
		else {
			return false;
		}
		$fs = get_file_storage();
		if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
			return false;
		}
		// finally send the file
		send_stored_file($file, $lifetime, 0, false);
	}
	
	/**
	* Delete a richmedia track
	**/
	function richmedia_delete_track($userid,$richmediaid){
		global $CFG,$DB;
		$DB->delete_records('richmedia_track',array('userid'=>$userid,'richmediaid'=>$richmediaid));
	}
	
	/**
	* Create the good index.html to export the richmedia
	**/
	function richmedia_create_index_html($context,$richmedia,$scorm){
		$fs = get_file_storage();
		 
		// Prepare file record object
		$fileinfo = array(
			'contextid' => $context->id, // ID of context
			'component' => 'mod_richmedia',     // usually = table name
			'filearea' => 'html',     // usually = table name
			'itemid' => 0,               // usually = ID of row in table
			'filepath' => '/',           // any path beginning and ending in /
			'filename' => 'index.html'); // any filename
		 
		 
		$filehtml = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'], 
			$fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);
			
		if($filehtml){
			$filehtml->delete();
		}		
		
		if ($scorm){
			$scorm = 1;
			$scripts = '
				<script type="text/javascript" src="js/communicationAPI.js"></script>
				<script type="text/javascript" src="js/scorm12.js"></script>';
			$unload = ' onUnload = "QuitWindow()"';
		}
		else {
			$scorm = 0;
			$scripts = '';
			$unload = '';
		}
		
		$filecontent = 
		'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
			<head>
				<title>richmedia</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<style type="text/css" media="screen">
				html, body { height:100%; background-color: #999999;}
				body { margin:0; padding:0; overflow:hidden; }
				#flashContent { width:100%; height:100%; }
				</style>'.$scripts.'
			</head>
			<body'.$unload.'>
				<div id="flashContent">
					<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="100%" height="100%" id="richmedia" align="middle">
						<param name="movie" value="richmedia.swf" />
						<param name="quality" value="high" />
						<param name="bgcolor" value="#999999" />
						<param name="play" value="true" />
						<param name="loop" value="true" />
						<param name="wmode" value="window" />
						<param name="scale" value="showall" />
						<param name="menu" value="true" />
						<param name="devicefont" value="false" />
						<param name="salign" value="" />
						<param name="allowScriptAccess" value="sameDomain" />
						<param name="allowFullScreen" value="true" />
						<param name="flashVars" value="urlContent=contents/content/&urlTheme=themes/'.$richmedia->theme.'/&cb_view_label='.get_string('display','richmedia').'&cb_view1='.get_string('tile','richmedia').'&cb_view2='.get_string('slide','richmedia').'&cb_view3='.get_string('video','richmedia').'&scorm='.$scorm.'" />
						<!--[if !IE]>-->
						<object type="application/x-shockwave-flash" data="richmedia.swf" width="100%" height="100%">
							<param name="movie" value="richmedia.swf" />
							<param name="quality" value="high" />
							<param name="bgcolor" value="#999999" />
							<param name="play" value="true" />
							<param name="loop" value="true" />
							<param name="wmode" value="window" />
							<param name="scale" value="showall" />
							<param name="menu" value="true" />
							<param name="devicefont" value="false" />
							<param name="salign" value="" />
							<param name="allowScriptAccess" value="sameDomain" />
							<param name="allowFullScreen" value="true" />
							<param name="flashVars" value="urlContent=contents/content/&urlTheme=themes/'.$richmedia->theme.'/&cb_view_label='.get_string('display','richmedia').'&cb_view1='.get_string('tile','richmedia').'&cb_view2='.get_string('slide','richmedia').'&cb_view3='.get_string('video','richmedia').'&scorm='.$scorm.'" />
						<!--<![endif]-->
							<a href="http://www.adobe.com/go/getflash">
								<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtenir Adobe Flash Player" />
							</a>
						<!--[if !IE]>-->
						</object>
						<!--<![endif]-->
					</object>
				</div>
			</body>
		</html>';
		$fs->create_file_from_string($fileinfo, $filecontent);
		
		$filehtml = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'], 
			$fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);
			
		return $filehtml;	
	}
	
	/**
	* Generate the settings.xml file
	**/
	function richmedia_generate_xml($richmedia){
		$context = get_context_instance(CONTEXT_MODULE, $richmedia->cmid);
		$referencesxml = $richmedia->referencesxml;
		$extension = explode('.',$referencesxml);
		if (!$referencesxml
			|| end($extension) != 'xml'){
			$richmedia->referencesxml = "settings.xml";
		}
		$fs = get_file_storage();
		
		// Prepare file record object
		$fileinfo = new stdClass();
		$fileinfo->component = 'mod_richmedia';
		$fileinfo->filearea  = 'content';
		$fileinfo->contextid = $context->id;
		$fileinfo->filepath = '/';
		$fileinfo->itemid = 0;
		$fileinfo->filename = $referencesxml;
		// Get file
		$file = $fs->get_file($fileinfo->contextid, $fileinfo->component, $fileinfo->filearea, $fileinfo->itemid, $fileinfo->filepath, $fileinfo->filename);
			
		// Read contents
		if ($file) {
			$contenuxml = $file->get_content();
			$contenuxml = str_replace('&','&amp;',$contenuxml);
			
			if ($oldxml = simplexml_load_string($contenuxml)){
                $oldsteps = $oldxml->steps[0]->children();
            }
		}
		//
		
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><settings></settings>');
		$movie = $xml->addChild('movie');
		$movie->addAttribute('src','video/'.$richmedia->referencesvideo);
		
		$design = $xml->addChild('design');
		$design->addAttribute('logo','logo.jpg');
		$design->addAttribute('font',$richmedia->font);
		$design->addAttribute('background','background.jpg');
		if ($richmedia->fontcolor[0] == '#'){
			$richmedia->fontcolor = substr($richmedia->fontcolor, 1);
		}
		$design->addAttribute('fontcolor','0x'.$richmedia->fontcolor);
		if ($richmedia->autoplay == 0){
			$richmedia->autoplay = 'false';
		}
		else {
			$richmedia->autoplay = 'true';
		}
		
		$options = $xml->addChild('options');
		$options->addAttribute('presenter','1');
		$options->addAttribute('comment','0');
		$options->addAttribute('defaultview',$richmedia->defaultview);
		$options->addAttribute('btnfullscreen','true');
		$options->addAttribute('btninverse','false');
		$options->addAttribute('autoplay',$richmedia->autoplay);
		
		$presenter = $xml->addChild('presenter');
		$presenter->addAttribute('name',html_entity_decode($richmedia->presentor));
		$presenter->addAttribute('biography',strip_tags(html_entity_decode($richmedia->intro)));
		
		$titles = $xml->addChild('titles');
		$title1 = $titles->addChild('title');
		$title1->addAttribute('target','fdPresentationTitle');
		$title1->addAttribute('label',html_entity_decode($richmedia->name));
		$title2 = $titles->addChild('title');
		$title2->addAttribute('target','fdMovieTitle');
		$title2->addAttribute('label','');
		$title3 = $titles->addChild('title');
		$title3->addAttribute('target','fdSlideTitle');
		$title3->addAttribute('label','');
		
		//traitement des steps
		$steps = $xml->addChild('steps');
		if ($file){
            if ($oldxml){
                foreach ($oldxml->steps[0]->children() as $childname => $childnode){
                    $step = $steps->addChild('step');
                    foreach($childnode->attributes() as $attribute => $value) {
                        $step->addAttribute($attribute,$value);
                    }
                }
            }
		}
		
		if($file){
			$file->delete();
		}
		$fs->create_file_from_string($fileinfo, $xml->asXML());
	}
	
	/**
	* Display the Rich Media
	**/
	function richmedia_print($context,$richmedia){
		global $CFG;
		if (is_dir('themes/'.$richmedia->theme)){
			$width = 700;
			$height = 451;
			if ($richmedia->width && $richmedia->width != '' && $richmedia->width != 0){
				$width = $richmedia->width;
			}
			if ($richmedia->height && $richmedia->height != '' && $richmedia->height != 0){
				$height = $richmedia->height;
			}
			$fs = get_file_storage();
			$file = $fs->get_file($context->id, 'mod_richmedia', 'content',0, '/',$richmedia->referencesxml);
			if($file){
				$url = "{$CFG->wwwroot}/pluginfile.php/{$file->get_contextid()}/mod_richmedia/content";
				$filename = $file->get_filename();
				$filepath = $file->get_filepath();
				$fileurl = $url.$filepath.$filename;
				if ($filename == $richmedia->referencesxml){
					//show the flash player
					if (!$richmedia->html5){
					echo '
					<div id="flashContent" style="text-align:center;">
						<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="'.$width.'" height="'.$height.'" id="richmedia" align="middle">
							<param name="movie" value="playerflash/richmedia.swf" />
							<param name="quality" value="high" />
							<param name="bgcolor" value="#999999" />
							<param name="play" value="true" />
							<param name="loop" value="true" />
							<param name="wmode" value="transparent" />
							<param name="scale" value="showall" />
							<param name="menu" value="true" />
							<param name="devicefont" value="false" />
							<param name="salign" value="" />
							<param name="allowScriptAccess" value="sameDomain" />
							<param name="allowFullScreen" value="true" />
							<param name="flashVars" value="urlContent='.$url.'/&urlTheme=themes/'.$richmedia->theme.'/&cb_view_label='.get_string('display','richmedia').'&cb_view1='.get_string('tile','richmedia').'&cb_view2='.get_string('slide','richmedia').'&cb_view3='.get_string('video','richmedia').'&scorm=0" />
							<!--[if !IE]>-->
							<object type="application/x-shockwave-flash" data="playerflash/richmedia.swf" width="'.$width.'" height="'.$height.'">
								<param name="movie" value="playerflash/richmedia.swf" />
								<param name="quality" value="high" />
								<param name="bgcolor" value="#999999" />
								<param name="play" value="true" />
								<param name="loop" value="true" />
								<param name="wmode" value="transparent" />
								<param name="scale" value="showall" />
								<param name="menu" value="true" />
								<param name="devicefont" value="false" />
								<param name="salign" value="" />
								<param name="allowScriptAccess" value="sameDomain" />
								<param name="allowFullScreen" value="true" />
								<param name="flashVars" value="urlContent='.$url.'/&urlTheme=themes/'.$richmedia->theme.'/&cb_view_label='.get_string('display','richmedia').'&cb_view1='.get_string('tile','richmedia').'&cb_view2='.get_string('slide','richmedia').'&cb_view3='.get_string('video','richmedia').'&scorm=0" />
							<!--<![endif]-->
								<a href="http://www.adobe.com/go/getflash">
									<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtenir Adobe Flash Player" />
								</a>
							<!--[if !IE]>-->
							</object>
							<!--<![endif]-->
						</object>
					</div>';
					}
					else {
					    //LINK to display the HTML5 player
						echo '<div style="text-align:center;"><a target="_blank "href="'.$CFG->wwwroot .'/mod/richmedia/playerhtml5/index.php?richmedia='.$richmedia->id.'">'.get_string('newtab','richmedia').'</a></div>';
					}
				}	
			}
			else{
				echo get_string('xmlnotfound','richmedia');
			}			
		}
		else {
			echo 'Le theme '.$richmedia->theme.' n\'existe plus'; //TODO Translate
		}
	}
	
	function richmedia_supports($feature) {
		switch($feature) {
			case FEATURE_GROUPS:                  return false;
			case FEATURE_GROUPINGS:               return false;
			case FEATURE_GROUPMEMBERSONLY:        return false;
			case FEATURE_MOD_INTRO:               return true;
			case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
			case FEATURE_GRADE_HAS_GRADE:         return false;
			case FEATURE_GRADE_OUTCOMES:          return false;
			case FEATURE_BACKUP_MOODLE2:          return true;
			case FEATURE_SHOW_DESCRIPTION:        return false;

			default: return null;
		}
	}
	
	/**
	 * Adds module specific settings to the settings block
	 *
	 * @param settings_navigation $settings The settings navigation object
	 * @param navigation_node $richmedianode The node to add module settings to
	 */
	function richmedia_extend_settings_navigation(settings_navigation $settings, navigation_node $richmedianode) {
		global $PAGE;

		$richmedianode->add('Import/Export', new moodle_url('/mod/richmedia/importexport.php', array('id'=>$PAGE->cm->id)));
	}
	
	function richmedia_webtv_exists(){
		global $DB;
		return $DB->record_exists('block',array('name'=>'webtv'));
	}
	
	function richmedia_print_keywords($richmedia){
		global $CFG;
		if (isset($richmedia->keywords)){
			$keywords = explode(',',$richmedia->keywords);
			if ($keywords){
				if (richmedia_webtv_exists()){
					foreach ($keywords as $keyword){
						echo '<a href="'.$CFG->wwwroot.'/blocks/webtv/search.php?search='.$keyword.'&course='.$richmedia->course.'">'.$keyword.'</a>&nbsp;';
					}
				}
				else {
					foreach ($keywords as $keyword){
						echo $keyword . ' ';
					}
				}
			}
		}
		else {
			echo 'aucun';
		}
	}
	
	function richmedia_user_complete($course, $user, $mod, $richmedia) {
        global $CFG, $DB;

        if ($logs = $DB->get_records('log', array('userid'=>$user->id, 'module'=>'richmedia',
                                                'action'=>'view', 'info'=>$richmedia->id), 'time ASC')) {
            $numviews = count($logs);
            $lastlog = array_pop($logs);

            $strmostrecently = get_string('mostrecently');
            $strnumviews = get_string('numviews', '', $numviews);

            echo "$strnumviews - $strmostrecently ".userdate($lastlog->time);

        } else {
            print_string('neverseen', 'richmedia');
        }
    }
	
?>