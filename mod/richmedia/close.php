<?php

    require_once("../../config.php");
	
	$userid = $_POST['userid'];
	$richmediaid = $_POST['richmediaid'];
	if ($track = $DB->get_record('richmedia_track',array('userid'=>$userid, 'richmediaid'=>$richmediaid))){
		$track->last = time();
		$DB->update_record('richmedia_track',$track);
		echo 1;
	}
?>