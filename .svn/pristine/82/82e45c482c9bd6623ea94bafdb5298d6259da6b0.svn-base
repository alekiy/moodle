<?php
/**
 * Print the Rich Media player in HTML5
 * Author:
 * 	Adrien Jamot  (adrien_jamot [at] symetrix [dt] fr)
 * 
 * @package   mod_richmedia
 * @copyright 2011 Symetrix
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
	require_once("../../../config.php");
	$PAGE->set_url('/mod/richmedia/playerhtml5/index.php');
	$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));
	//Fix accents problems on Safari
	function convertToHTML($value){
		$value = str_replace("é","&eacute;",$value);	
		$value = str_replace("è","&egrave;",$value);
		$value = str_replace("ê","&ecirc;",$value);
		$value = str_replace("à","&agrave;",$value);
		$value = str_replace("ç","&ccedil;",$value);
		$value = str_replace("û","&ucirc;",$value);
		//INSERT YOUR HTML VALUES HERE 
		return $value;
	}
	$richmediaid  = required_param('richmedia', PARAM_INT);
	$cm = get_coursemodule_from_instance('richmedia', $richmediaid);
	$context = get_context_instance(CONTEXT_MODULE, $cm->id);
	require_login();
	$richmedia = $DB->get_record('richmedia',array('id'=>$richmediaid));
	$filexml   = "{$CFG->wwwroot}/pluginfile.php/{$context->id}/mod_richmedia/content/".$richmedia->referencesxml;
	$repslides = "{$CFG->wwwroot}/pluginfile.php/{$context->id}/mod_richmedia/content/slides/";
	$filevideo = "{$CFG->wwwroot}/pluginfile.php/{$context->id}/mod_richmedia/content/video/".$richmedia->referencesvideo;
	
	$fs = get_file_storage();
	$fileinfo = new stdClass();
	$fileinfo->component = 'mod_richmedia';
	$fileinfo->filearea  = 'content';
	$fileinfo->contextid = $context->id;
	$fileinfo->filepath  = '/';
	$fileinfo->itemid    = 0;
	$fileinfo->filename  = $richmedia->referencesxml;
	// Get file
	$file = $fs->get_file($fileinfo->contextid, $fileinfo->component, $fileinfo->filearea, 
			$fileinfo->itemid, $fileinfo->filepath, $fileinfo->filename);
		
	// Read contents
	
	$slides ='';
	if ($file) {
		$contenuxml = $file->get_content();
		$contenuxml = str_replace('&','&amp;',$contenuxml);
		
		$xml = simplexml_load_string($contenuxml);

		foreach($xml->titles[0]->title[0]->attributes() as $attribute => $value) {
			if ($attribute == 'label'){
				$value = str_replace ("&rsquo;", iconv ("CP1252", "UTF-8", "’"), $value);
				$value = str_replace ("â€™", "’", $value);
				$value = str_replace("’","'",$value);
				$value = convertToHTML($value);
				$title = $value;
				break;
			}	
		}
		foreach($xml->presenter[0]->attributes() as $attribute => $value) {
			if ($attribute == 'name'){
				$value = str_replace ("&rsquo;", iconv ("CP1252", "UTF-8", "’"), $value);
				$value = str_replace ("â€™", "’", $value);
				$value = str_replace("’","'",$value);
				$presentername = convertToHTML($value);
			}
			else if ($attribute == 'biography'){
				$value = str_replace ("&rsquo;", iconv ("CP1252", "UTF-8", "’"), $value);
				$value = str_replace ("â€™", "’", $value);
				$value = str_replace("’","'",$value);
				$presenterbio = convertToHTML($value);
			}
			else if ($attribute == 'title'){
				$value = str_replace ("&rsquo;", iconv ("CP1252", "UTF-8", "’"), $value);
				$value = str_replace ("â€™", "’", $value);
				$value = str_replace("’","'",$value);
				$presentertitle = convertToHTML($value);
			}
		}
		foreach($xml->design[0]->attributes() as $attribute => $value) {
			if ($attribute == 'fontcolor'){
				$fontcolor = substr($value,2);
				break;
			}
		}
		$font = $richmedia->font;
		$tabstep = array();
		$i = 0;
		$tabslides = array();
		foreach ($xml->steps[0]->children() as $childname => $childnode){
			foreach($childnode->attributes() as $attribute => $value) {
				$tabstep[$i][$attribute] = (String)$value;		
			}
			$time = $tabstep[$i]['framein'];

			$f = $fs->get_file($context->id, 'mod_richmedia', 'content',0, '/slides/', $tabstep[$i]['slide']);
			
			$tabslides[$i]['slide'] = $tabstep[$i]['label'];
			$tabslides[$i]['framein'] = $time;
			$tabslides[$i]['src'] = $repslides.$tabstep[$i]['slide'];
			if (!array_key_exists('view',$tabstep[$i]) || $tabstep[$i]['view'] == ''){
				$tabstep[$i]['view'] = $richmedia->defaultview;
			}
			
			$slides .= $time.': \'<img src="'.$repslides.$tabstep[$i]['slide'].'" width="100%" view="'.$tabstep[$i]['view'].'"/><br/>'.$tabstep[$i]['comment'].'\',';
			$i++;
		}
		
		$slides = substr($slides,0,-1);
		
		if (file_exists('../themes/'.$richmedia->theme.'/logo.jpg')){
			$logo = $richmedia->theme.'/logo.jpg';
		}
		else {
			$logo = $richmedia->theme.'/logo.png';
		}
		
		if (file_exists('../themes/'.$richmedia->theme.'/background.jpg')){
			$background = $richmedia->theme.'/background.jpg';
		}
		else {
			$background = $richmedia->theme.'/background.png';
		}
		$defaultview = $richmedia->defaultview;
		$autoplay = $richmedia->autoplay;
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<title><?php echo $title ?></title>
		<link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
		<meta name="description" content="<?php p(strip_tags(format_text($SITE->summary, FORMAT_HTML))) ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot . '/mod/richmedia/lib/resources/css/ext-all.css'?>"/>
		<style type="text/css">
			.content{
				overflow : hidden;
				position:absolute;
				top : 0px;
			}
			
			#gridSlides .x-grid3-header {
				display:none;
			}
			
			.x-window-plain .x-window-body {
				background-color : #FFF !important;
			}
					
			video {
				background : black;
			}
		</style>
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo $CFG->wwwroot . '/mod/richmedia/lib/adapter/ext/ext-base.js'?>"></script>
		<script type="text/javascript" src="<?php echo $CFG->wwwroot . '/mod/richmedia/lib/ext-all.js'?>"></script>
		<script src="js/player.js"></script>
		<script src="js/cuepoint.js"></script>
		<script>
			Ext.util.CSS.swapStyleSheet('theme', "<?php echo $CFG->wwwroot . '/mod/richmedia/lib/resources/css/xtheme-gray.css' ?>");
			new Ext.state.CookieProvider().set('theme', 'xtheme-gray.css');
			var slides = {<?php echo $slides ?>};
			var tabSlides = <?php echo json_encode($tabslides) ?>;
			var locked = true;
			var defaultview = <?php echo $defaultview ?>;
			var autoplay = <?php echo $autoplay ?>;
			var titleSummary = '<?php echo get_string('summary','richmedia') ?>';
			
			$(document).ready(function(){
				Player.init(document.querySelector('#video'),document.querySelector('#playbutton'),slides,tabSlides);
				if ($.isEmptyObject(slides)){
					Player.videoFullScreen();
					$('#list').hide();
					$('#selectview').hide();
					$('#next').hide();
					$('#closed').hide();
				}
			});
		</script>
	</head>
	<body>
		<div id="total" style="box-shadow: 6px 0 4px  -4px #C6C7C8 , -6px 0 4px  -4px #C6C7C8;width:980px;height:643px;;margin:auto;display:block;">
			<div id="divprinc" class="divprinc" style="display:inline-block;height:600px;width:980px;font-family : <?php echo $font ?>;">
				<img id="background" class="content" src="../themes/<?php echo $background ?>" style="width:980px;height:600px;margin:auto;" alt="">
				<div style="overflow : hidden;	position:absolute;top : 8px;margin-left:3px;width : 980px;line-height:3;">
					<img src="../themes/<?php echo $logo ?>"/>
					<span style="font-size:18px;float:right;margin-right:85px;color: #<?php echo $fontcolor ?>"><?php echo $title ?></span>
				</div>	
				<div id="content" class ="content" style="padding-top : 122px;height:485px;width:980px;">
					<div>
						<div id="left" style="float:left;padding-left : 10px;padding-right : 4px;">
							<section id="cuePlayer">
								<video id="video" width="300" height="167" preload="auto" onpause="Player.pauseVideo()" onplay="Player.playVideo()" controls>
									<source src="<?php echo $filevideo ?>" type="video/mp4" />
									<source src="<?php echo $filevideo ?>" type="video/ogg" />
									<source src="<?php echo $filevideo ?>" type="video/webm" />
								</video>
							</section>
							
							<div id="text" style="overflow : auto;width : 300px;max-height : 245px;height:100%;">
								<p style="font-size : 14px;"><?php echo $presentername ?></p>
								<br />
								<p style="font-size : 11px;text-align:justify;"><?php echo $presenterbio ?></p>
							</div>	
						</div>	
						<div id="subtitles" style="width : 600px;height : 450px; float:right;margin-right : 38px;text-align:center;"></div>
					</div>
				</div>
			</div>	
			<!-- barre de controle -->
			<div id="controles" style="width : auto;margin:auto;height : 35px; background : url(pix/footer.png)repeat-x;position:relative;">
				<input id="list" type="button" style="background-image : url('pix/list.png');width :28px;height :27px;float:left;border:none;margin-left:7px;margin-top:3px;margin-right : 14px;display:block;" onclick="Player.displaySlides(titleSummary)" />
				<input type="button" id="prev"       style="background-image : url('pix/prev.png');width :28px;height :27px;margin-right : 5px;border:none;margin-top:3px;" />
				<input type="button" id="playbutton" style="background-image : url('pix/play.png');margin-right : 5px;width:28px;height:27px;border:none;margin-top:3px;"/>
				<input type="button" id="next"       style="background-image : url('pix/next.png');width :28px;height :27px;margin-right : 14px;border:none;margin-top:3px;" />
				
				<div style="float : right;">
					<input type="button" id="credit" onclick="Player.showCredits()" style="width:17px;height:18px;background-image : url('pix/credit.png');border:none;;margin-right : 7px;margin-top:7px;"/>
				</div>
				
				<div style="float : right;display : inline-block;">
					<input type="button" id="closed" style="background-image : url('pix/closed_normal.png');width :28px;height :27px;margin-right : 4px;border:none;margin-top:3px;" />
					<select id="selectview" onchange="Player.changeDisplay(this.options[this.selectedIndex].value);" disabled="disabled" style="margin-right : 14px;">
						<option value="#" selected="selected" disabled="disabled"><?php echo get_string('display','richmedia') ?></option>
						<option value="1"><?php echo get_string('tile','richmedia') ?></option>
						<option value="2"><?php echo get_string('slide','richmedia') ?></option>
						<option value="3"><?php echo get_string('video','richmedia') ?></option>
					</select>  
					<!--<input type="button" id="fullscreen" style="background-image : url('pix/next.png');width :28px;height :27px;margin-right : 7px;border:none;margin-top:3px;" />-->
				</div>
			</div>
		</div>
	</body>
</html>