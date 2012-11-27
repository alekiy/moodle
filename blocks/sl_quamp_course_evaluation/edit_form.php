<?php

class block_sl_quamp_course_evaluation_edit_form extends block_edit_form {

  /**
   * @param MoodleQuickForm $mform
   */
  protected function specific_definition($mform) {

    $mform->addElement('header', 'configheader', get_string('block_title', 'block_sl_quamp_course_evaluation'));

    # invitation text
    $mform->addElement('text', 'config_invitation_text', get_string('config_invitation_text', 'block_sl_quamp_course_evaluation'));
    $mform->setDefault('config_invitation_text', $this->config->invitation_text);
    $mform->setType('config_invitation_text', PARAM_MULTILANG);

    # question link text
    $mform->addElement('text', 'config_quest_link_text', get_string('config_quest_link_text', 'block_sl_quamp_course_evaluation'));
    $mform->setDefault('config_quest_link_text', $this->config->quest_link_text);
    $mform->setType('config_quest_link_text', PARAM_MULTILANG);

    ###
    # course key
    ###

    # course key shall be entered manually be course admin
    if (get_config('sl_quamp_course_evaluation', 'block_quamp_server_db_field') == 'manual') {
      $mform->addElement('text', 'config_course_key', get_string('config_course_key', 'block_sl_quamp_course_evaluation'));
      $mform->setDefault('config_course_key', $this->config->course_key);
      $mform->setType('config_course_key', PARAM_MULTILANG);
    }

    # course key is predefinied and can not be changed
    elseif (get_config('sl_quamp_course_evaluation', 'block_quamp_course_key_allow_overwrite') == 2) {
      global $COURSE;
      if (get_config('sl_quamp_course_evaluation', 'block_quamp_server_db_field') == 'shortname') {
        //echo $COURSE->shortname;
      }
      elseif (get_config('sl_quamp_course_evaluation', 'block_quamp_server_db_field') == 'idnumber') {
        //echo $COURSE->idnumber;
      }
    }

  }
}