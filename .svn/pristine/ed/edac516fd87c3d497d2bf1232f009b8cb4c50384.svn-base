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
	require_once("$CFG->dirroot/mod/richmedia/lib.php");
	require_once("$CFG->libdir/filelib.php");
	require_once("$CFG->libdir/filestorage/zip_archive.php");
		
	/**
	** Extract files
	**/	
	function richmedia_parse($richmedia) {
		global $CFG, $DB;
		$cfg_richmedia = get_config('richmedia');
		if (!isset($richmedia->cmid)) {
			$cm = get_coursemodule_from_instance('richmedia', $richmedia->id);
			$richmedia->cmid = $cm->id;
		}
		$context = get_context_instance(CONTEXT_MODULE, $richmedia->cmid);
		$newhash = $richmedia->sha1hash;

		$fs = get_file_storage();	
				
		//SLIDES (rep data)
		$referenceslides = false;
		if ($referenceslides = $fs->get_file($context->id, 'mod_richmedia', 'package', 0, '/', $richmedia->referenceslides)) {
			$newhash = $referenceslides->get_contenthash();
		} else {
			$newhash = null;
		}
		if ($referenceslides) {
			// now extract files
			$packer = get_file_packer('application/zip');
			$referenceslides->extract_to_storage($packer, $context->id, 'mod_richmedia', 'content', 0, '/');
		}		
				
		//VIDEO
		$referencesvideo = $fs->get_file(13, 'user', 'draft', 0, '/', $richmedia->referencesvideo);
		if ($referencesvideo) {
			$referencesvideo->copy_to_storage($context->id, 'mod_richmedia', 'content', 0, '/video/',$referencesvideo->get_filename());
		}
		
		//PICTURE
		$referencesfond = $fs->get_file(13, 'user', 'draft', 0, '/', $richmedia->referencesfond);
		if ($referencesfond) {
			$referencesfond->copy_to_storage($context->id, 'mod_richmedia', 'content', 0, '/picture/',$referencesfond->get_filename());
		}
		
		//XML (fichier HTM)
		$referencesxml = $fs->get_file(13, 'user', 'draft', 0, '/', $richmedia->referencesxml);
		if ($referencesxml) {
			$referencesxml->copy_to_storage($context->id, 'mod_richmedia', 'content', 0, '/',$referencesxml->get_filename());
		}
		//	
		$richmedia->revision++;
		$richmedia->sha1hash = $newhash;
		$DB->update_record('richmedia', $richmedia);
	}
	
	class richmedia_package_file_info extends file_info_stored {
		public function get_parent() {
			if ($this->lf->get_filepath() === '/' and $this->lf->get_filename() === '.') {
				return $this->browser->get_file_info($this->context);
			}
			return parent::get_parent();
		}
		public function get_visible_name() {
			if ($this->lf->get_filepath() === '/' and $this->lf->get_filename() === '.') {
				return $this->topvisiblename;
			}
			return parent::get_visible_name();
		}
	}
	
	/**
	** Add a user track for a richmedia
	**/
	function richmedia_add_track($user,$richmedia){
		global $DB;
		if ($track = $DB->get_record('richmedia_track',array('userid'=>$user->id, 'richmediaid'=>$richmedia->id))) {
			$track->attempt = $track->attempt + 1;
			$track->last = time();
			$DB->update_record('richmedia_track',$track);
			$id = $track->id;
			
		} else {
			$track = new stdClass();
			$track->userid = $user->id;
			$track->richmediaid = $richmedia->id;
			$track->attempt = 1;
			$track->start = time();
			$id = $DB->insert_record('richmedia_track',$track);
		}
	}
	
	/**
	**Affichage de la page du RICH MEDIA
	**/
	function richmedia_view_display ($user, $richmedia, $action, $cm) {
		global $CFG, $DB, $PAGE, $OUTPUT;
		if (!isset($richmedia->cmid)) {
			$cm = get_coursemodule_from_instance('richmedia', $richmedia->id);
			$richmedia->cmid = $cm->id;
		}
		$context = get_context_instance(CONTEXT_MODULE, $richmedia->cmid);
		richmedia_print($context,$richmedia);
	}
	
	/**
	* Export the rich media at scorm format
	**/
	function richmedia_export_scorm ($courserichmedia,$filename,$context,$scorm){
		require_capability('moodle/course:manageactivities', $context);
		
		$zipper = get_file_packer('application/zip');

		$fs = get_file_storage();
		
		//VIDEO
		// Prepare video record object
		$fileinfovideo = new stdClass();
		$fileinfovideo->component = 'mod_richmedia';
		$fileinfovideo->filearea  = 'content';
		$fileinfovideo->contextid = $context->id;
		$fileinfovideo->filepath = '/video/';
		$fileinfovideo->itemid = 0;
		$fileinfovideo->filename = $courserichmedia->referencesvideo;
		// Get file
		$filevideo = $fs->get_file($fileinfovideo->contextid, $fileinfovideo->component, $fileinfovideo->filearea, 
				$fileinfovideo->itemid, $fileinfovideo->filepath, $fileinfovideo->filename);
		if ($filevideo){		
			$filevideoname = $filevideo->get_filename();
		}
		$files['richmedia/contents/content/video/'.$filevideoname] = $filevideo;

		//XML
		// Prepare video record object
		$fileinfoxml = new stdClass();
		$fileinfoxml->component = 'mod_richmedia';
		$fileinfoxml->filearea  = 'content';
		$fileinfoxml->contextid = $context->id;
		$fileinfoxml->filepath = '/';
		$fileinfoxml->itemid = 0;
		$fileinfoxml->filename = $courserichmedia->referencesxml;
		// Get file
		$filexml = $fs->get_file($fileinfoxml->contextid, $fileinfoxml->component, $fileinfoxml->filearea, 
				$fileinfoxml->itemid, $fileinfoxml->filepath, $fileinfoxml->filename);
		if ($filexml){		
			$filexmlname = $filexml->get_filename();
		}
		$files['richmedia/contents/content/'.$filexmlname] = $filexml;
		
		// SLIDES
		$slides = $fs->get_directory_files($context->id, 'mod_richmedia', 'content', 0, '/slides/');
		foreach ($slides as $slide) {
			$files['richmedia/contents/content/slides/'.$slide->get_filename()] = $slide;
		}
		
		//theme
		$files['richmedia/themes/'.$courserichmedia->theme.'/logo.png'] 		= 'themes/' . $courserichmedia->theme . '/logo.png';
		$files['richmedia/themes/'.$courserichmedia->theme.'/background.png']   = 'themes/' . $courserichmedia->theme . '/background.png';
		$files['richmedia/themes/'.$courserichmedia->theme.'/logo.jpg']		    = 'themes/' . $courserichmedia->theme . '/logo.jpg';
		$files['richmedia/themes/'.$courserichmedia->theme.'/background.jpg']   = 'themes/' . $courserichmedia->theme . '/background.jpg';
		
		//swf file
		$files['richmedia/richmedia.swf'] = 'playerflash/richmedia.swf';
		
		if ($scorm){
			//js files
			$files['richmedia/js/communicationAPI.js'] = 'export/include/communicationAPI.js';
			$files['richmedia/js/scorm12.js']	       = 'export/include/scorm12.js';
		}	
		
		//html file
		$filehtml = richmedia_create_index_html($context,$courserichmedia,$scorm);
		
		$files['richmedia/index.html'] = $filehtml;
		
		if ($scorm){
			// SCORM FILES
			$files['adlcp_rootv1p2.xsd']	= 'export/include/adlcp_rootv1p2.xsd';
			$files['ims_xml.xsd'] 			= 'export/include/ims_xml.xsd';
			$files['imscp_rootv1p1p2.xsd'] 	= 'export/include/imscp_rootv1p1p2.xsd';
			$files['imsmanifest.xml'] 		= 'export/include/imsmanifest.xml';
			$files['imsmd_rootv1p2p1.xsd']	= 'export/include/imsmd_rootv1p2p1.xsd';
		}	
		
		//create the zip
		if ($newfile = $zipper->archive_to_storage($files, $fileinfovideo->contextid, 'mod_richmedia', 'zip', '0', '/', $filename.'.zip')) {
			$lifetime = isset($CFG->filelifetime) ? $CFG->filelifetime : 86400;
			send_stored_file($newfile, $lifetime, 0, false);
		}
		else {
			echo 'Une erreur s\'est produite'; // TODO : translate
		}
	}
?>