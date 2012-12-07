<?php
/**
 * Display the Rich Media
 * Author:
 * 	Adrien Jamot  (adrien_jamot [at] symetrix [dt] fr)
 * 
 * @package   mod_richmedia
 * @copyright 2011 Symetrix
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
    require_once("../../config.php");
    require_once($CFG->dirroot.'/mod/richmedia/locallib.php');

    $id = optional_param('id', '', PARAM_INT);       // Course Module ID

    if (!empty($id)) {
        if (! $cm = get_coursemodule_from_id('richmedia', $id)) {
            print_error('invalidcoursemodule');
        }
        if (! $course = $DB->get_record("course", array("id"=>$cm->course))) {
            print_error('coursemisconf');
        }
        if (! $richmedia = $DB->get_record("richmedia", array("id"=>$cm->instance))) {
            print_error('invalidcoursemodule');
        }
    } else {
        print_error('missingparameter');
    }

    $url = new moodle_url('/mod/richmedia/view.php', array('id'=>$cm->id));

    $PAGE->set_url($url);
	$PAGE->requires->js_init_call('M.mod_richmedia.onBeforeUnload', array($USER->id,$richmedia->id));
    require_login($course->id, false, $cm);

    $context = get_context_instance(CONTEXT_COURSE, $course->id);
    $contextmodule = get_context_instance(CONTEXT_MODULE,$cm->id);

	$pagetitle = strip_tags($course->shortname.': '.format_string($richmedia->name));
	
	add_to_log($course->id, 'richmedia', 'view', 'view.php?id='.$cm->id, $richmedia->id, $cm->id);
	require_once($CFG->libdir . '/completionlib.php');
	$completion = new completion_info($course);
	$completion->set_module_viewed($cm);
	
    //
    // Print the page header
    //	
    $PAGE->set_title($pagetitle);
    $PAGE->set_heading($course->fullname);
    echo $OUTPUT->header();

	if (has_capability('mod/richmedia:viewreport', $context)) {
		echo '<div style="float:right;margin-top:10px;">';
		echo '<a href="report.php?id='.$id.'">'.get_string('showresults','richmedia').'</a>';
		echo '</div>';
	}
	
    // Print the main part of the page
    echo $OUTPUT->heading(format_string($richmedia->name));

	echo '<div style="width : '.$richmedia->width.'px;padding :5px;margin-left:auto;margin-right:auto;margin-bottom : 20px;margin-top : 10px;">';
	echo $richmedia->intro;
	echo get_string('keywords','richmedia') .' : ';
	richmedia_print_keywords($richmedia,null);
	echo '<br /><br />';
	echo '<a href="'.$CFG->wwwroot . '/course/view.php?id='. $course->id.'" style="color : #FFF;background-color:#333;padding:2px;"><span>'.get_string('return','richmedia').'</span></a>';
	echo '</div>';

	richmedia_add_track($USER, $richmedia);
	richmedia_view_display($USER, $richmedia, 'view.php?id='.$cm->id, $cm);
    echo $OUTPUT->footer();


