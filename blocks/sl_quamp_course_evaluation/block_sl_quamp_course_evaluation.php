<?php

/**
 * @author: Jan-Peter Hagenm�ller
 * @date: 2011-11-07
 * @see: http://docs.moodle.org/dev/Blocks
 */
class block_sl_quamp_course_evaluation extends block_base {

  /**
   * initial block settings
   * @return void
   */
  function init() {

    # set header
    $this->title = get_string('block_title', 'block_sl_quamp_course_evaluation');
  }

  /**
   * enable/disable global block config
   * @return bool
   */
  function has_config() {
    return true;
  }

  /**
   * enable/disable local config (overwrites globals)
   * @return bool
   */
  function instance_allow_config() {
    return true;
  }

  /**
   * saves config data
   * @param $data
   * @return bool
   */
  public function instance_config_save($data, $nolongerused = false) {
    return parent::instance_config_save($data, $nolongerused);
  }

  /**
   * where to display block
   * @return array
   */
  function applicable_formats() {
    return array(
      'site-index' => false,
      'course-view' => true,
      'course-view-social' => true,
      'mod' => false,
      'mod-quiz' => false
    );
  }

  /**
   * returns whether or not to hide header
   * @return bool
   */
  function hide_header() {
    return false;
  }

  /**
   * returns block content
   * @return stdObject
   */
  function get_content() {

    global $CFG, $COURSE, $USER;

    # content has already been set
    if ($this->content !== NULL) {
      return $this->content;
    }

    ###
    # create content
    ###

    $this->content = new stdClass;

    # internal problems
    if (!$CFG || !$COURSE || !$USER) {
      $this->content->text = get_string('config_error_moodle_intern', 'block_sl_quamp_course_evaluation');
      return $this->content;
    }

    # check on QUAMP server URL
    if (!$block_quamp_server = get_config('sl_quamp_course_evaluation', 'block_quamp_server')) {
      $this->content->text = get_string('config_error_server', 'block_sl_quamp_course_evaluation');
      return $this->content;
    }

    ###
    # get user and course key
    ###

    # we build an anonymous key for current user
    $user_key = md5($USER->username);

    # get course key
    $course_key = '';

    # use a manual key set by lecturer
    if (get_config('sl_quamp_course_evaluation', 'block_quamp_server_db_field') == 'manual') {
      # lecturer must set a course key first
      if (isset($this->config->course_key) && !$course_key = $this->config->course_key) {
        $this->content->text = get_string('config_error_no_course', 'block_sl_quamp_course_evaluation');
        return $this->content;
      }
    }

      # overwriting course key is not allowed
    elseif (get_config('sl_quamp_course_evaluation', 'block_quamp_course_key_allow_overwrite') == 2) {
      # use shortname field for key
      if (get_config('sl_quamp_course_evaluation', 'block_quamp_server_db_field') == 'shortname') {
        $course_key = $COURSE->shortname;
      }
        # use idnumber field for course key
      elseif (get_config('sl_quamp_course_evaluation', 'block_quamp_server_db_field') == 'idnumber') {
        $course_key = $COURSE->idnumber;
      }
    }

      # overwriting course key is allowed
    else {
      if (get_config('sl_quamp_course_evaluation', 'block_quamp_server_db_field') == 'shortname') {
        $course_key = $this->config->course_key ? $this->config->course_key : $COURSE->shortname;
      }
      elseif (get_config('sl_quamp_course_evaluation', 'block_quamp_server_db_field') == 'idnumber') {
        $course_key = $this->config->course_key ? $this->config->course_key : $COURSE->idnumber;
      }
    }

    # check on course key
    if (!$course_key) {
      $this->content->text = get_string('config_error_no_course', 'block_sl_quamp_course_evaluation');
      return $this->content;
    }

    # get API key
    $api_key = get_config('sl_quamp_course_evaluation', 'block_quamp_server_api_key');

    try {

      $dir = dirname(__FILE__) . '/lib/sfWebBrowserPlugin/lib';
      include_once $dir . '/sfWebBrowser.class.php';
      include_once $dir . '/sfCurlAdapter.class.php';

      /**
       * use CURL adapter
       * - to be not as slow as fopen
       * - to make possible timeouts
       * - !!! CURL must be enabled in php.ini !!!
       */
      $timeout = get_config('sl_quamp_course_evaluation', 'block_quamp_server_timeout') ? get_config('sl_quamp_course_evaluation', 'block_quamp_server_timeout') : 2;
      $b = new sfWebBrowser(array(), 'sfCurlAdapter', array('Timeout' => $timeout, 'ssl_verify_host' => 0, 'ssl_verify' => 0));

      # ask QUAMP server for questionnaire link
      # we send data with parameters to get rid of URL changing course-key-strings
      $b->get($block_quamp_server . "/slEvaluationMoodle/apiCourseLink.html", array(
                                                                                   'api-key' => $api_key,
                                                                                   'key' => $course_key,
                                                                                   'user-key' => $user_key
                                                                              ));

      # something has been gone wrong
      if ($b->getResponseCode() != 200) {
        $this->content->text = get_string('config_error_connect', 'block_sl_quamp_course_evaluation');
        $this->content->text .= " Response code: " . $b->getResponseCode();
        return $this->content;
      }

        # ok show link
      else {
        $quest_link = $b->getResponseText();
      }

    }

      # asking failed
    catch (Exception $e) {
      $this->content->text = get_string('config_error_connect', 'block_sl_quamp_course_evaluation');
      $this->content->text .= ' error: ' . $e->getMessage();
      return $this->content;
    }

    ###
    # check response
    ###

    # wrong API key
    if ($b->getResponseText() == 'access denied') {
      $this->content->text = get_string('config_error_no_access', 'block_sl_quamp_course_evaluation');
      return $this->content;
    }

    # course not found
    if ($b->getResponseText() == 'no course') {
      $this->content->text = get_string('config_error_no_course', 'block_sl_quamp_course_evaluation');
      return $this->content;
    }

    # out of time period
    if ($b->getResponseText() == 'out of time period') {
      $this->content->text = get_string('config_error_out_of_time_period', 'block_sl_quamp_course_evaluation');
      return $this->content;
    }

    # no user or user has already been participated
    if ($b->getResponseText() == 'no user') {
      //# we rather hide whole block
      //return $this->content;
      $this->content->text = get_string('config_error_no_user', 'block_sl_quamp_course_evaluation');
      return $this->content;
    }

    # course not active
    if ($b->getResponseText() == 'not active') {
      $this->content->text = get_string('config_error_not_active', 'block_sl_quamp_course_evaluation');
      return $this->content;
    }

    ###
    # build content
    ###

    # get invitation text
    if (!$text = $this->config->invitation_text) {
      if (!$text = get_config('sl_quamp_course_evaluation', 'block_quamp_invitation_text')) {
        $text = get_string('text', 'block_sl_quamp_course_evaluation');
      }
    }

    # get questionnaire link text
    if (!$quest_link_text = $this->config->quest_link_text) {
      if (!$quest_link_text = get_config('sl_quamp_course_evaluation', 'block_quamp_quest_link')) {
        $quest_link_text = get_string('questionnaire', 'block_sl_quamp_course_evaluation');
      }
    }

    # build block content
    $this->content->text = $text;

    $footer = array();
    $footer[] = '<div class="quest-link">';
    $footer[] = '<a';
    $footer[] = 'href="' . $quest_link . '"';
    $footer[] = 'target="_blank"';
    $footer[] = 'title="' . $quest_link_text . '"';
    $footer[] = '>';
    $footer[] = $quest_link_text;
    $footer[] = '</a>';
    $footer[] = '</div>';
    $this->content->footer = implode(' ', $footer);

    return $this->content;
  }

  /**
   * cron job
   * @return void
   */
  public function cron() {
    //mtrace( "Hey, my cron script is running" );
    return true;
  }

}