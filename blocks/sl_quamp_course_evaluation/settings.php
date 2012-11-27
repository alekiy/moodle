<?php

$widgetQuampServer = new admin_setting_configtext(
  'sl_quamp_course_evaluation/block_quamp_server',
  get_string('config_quampserver', 'block_sl_quamp_course_evaluation'),
  get_string('config_quampserver', 'block_sl_quamp_course_evaluation'),
  isset($CFG->block_quamp_server) ? $qs = $CFG->block_quamp_server : 'http://'
);

$widgetApiKey = new admin_setting_configtext(
  'sl_quamp_course_evaluation/block_quamp_server_api_key',
  get_string('config_api_key', 'block_sl_quamp_course_evaluation'),
  get_string('config_api_key', 'block_sl_quamp_course_evaluation'),
  isset($CFG->block_quamp_server_api_key) ? $key = $CFG->block_quamp_server_api_key : ''
);

$widgetTimeOut = new admin_setting_configselect(
  'sl_quamp_course_evaluation/block_quamp_server_timeout',
  get_string('config_quampserver_timeout', 'block_sl_quamp_course_evaluation'),
  get_string('config_quampserver_timeout', 'block_sl_quamp_course_evaluation'),
  isset($CFG->block_quamp_server_timeout) ? $to = $CFG->block_quamp_server_timeout : 2,
  array(1=>1, 2=>2, 3=>3, 4=>4)
);

$widgetDbField = new admin_setting_configselect(
  'sl_quamp_course_evaluation/block_quamp_server_db_field',
  get_string('config_db_field', 'block_sl_quamp_course_evaluation'),
  get_string('config_db_field', 'block_sl_quamp_course_evaluation'),
  isset($CFG->block_quamp_server_db_field) ? $field = $CFG->block_quamp_server_db_field : 'manual',
  array(
    'manual' => get_string('config_manual_course_key', 'block_sl_quamp_course_evaluation'),
    'shortname' => 'shortname',
    'idnumber' => 'idnumber',
  )
);

$widgetAllowOverwrite = new admin_setting_configselect(
  'sl_quamp_course_evaluation/block_quamp_course_key_allow_overwrite',
  get_string('config_course_key_allow_overwrite', 'block_sl_quamp_course_evaluation'),
  get_string('config_course_key_allow_overwrite', 'block_sl_quamp_course_evaluation'),
  isset($CFG->block_quamp_course_key_allow_overwrite) ? $overwrite = $CFG->block_quamp_course_key_allow_overwrite : 1,
  array(
    1 => get_string('config_course_key_allow_overwrite_yes', 'block_sl_quamp_course_evaluation'),
    2 => get_string('config_course_key_allow_overwrite_no', 'block_sl_quamp_course_evaluation'),
  )
);

$widgetBlockTitle = new admin_setting_configtext(
  'sl_quamp_course_evaluation/block_quamp_block_title',
  get_string('config_block_title', 'block_sl_quamp_course_evaluation'),
  get_string('config_block_title', 'block_sl_quamp_course_evaluation'),
  isset($CFG->block_quamp_block_title) ? $title = $CFG->block_quamp_block_title : get_string('block_title', 'block_sl_quamp_course_evaluation')
);

$widgetInvitationText = new admin_setting_configtext(
  'sl_quamp_course_evaluation/block_quamp_invitation_text',
  get_string('config_invitation_text', 'block_sl_quamp_course_evaluation'),
  get_string('config_invitation_text', 'block_sl_quamp_course_evaluation'),
  isset($CFG->block_quamp_invitation_text) ? $text = $CFG->block_quamp_invitation_text : get_string('text', 'block_sl_quamp_course_evaluation')
);

$widgetLinkText = new admin_setting_configtext(
  'sl_quamp_course_evaluation/block_quamp_quest_link_text',
  get_string('config_quest_link_text', 'block_sl_quamp_course_evaluation'),
  get_string('config_quest_link_text', 'block_sl_quamp_course_evaluation'),
  isset($CFG->block_quamp_quest_link_text) ? $text = $CFG->block_quamp_quest_link_text : get_string('questionnaire', 'block_sl_quamp_course_evaluation')
);

$widgetHelpUrl = new admin_setting_configtext(
  'sl_quamp_course_evaluation/block_quamp_help_url',
  get_string('config_help_url', 'block_sl_quamp_course_evaluation'),
  get_string('config_help_url', 'block_sl_quamp_course_evaluation'),
  isset($CFG->block_quamp_help_url) ? $text = $CFG->block_quamp_help_url : '/evaluation/evaluations.html'
);

$settings->add($widgetQuampServer);
$settings->add($widgetApiKey);
$settings->add($widgetTimeOut);
$settings->add($widgetDbField);
$settings->add($widgetAllowOverwrite);
$settings->add($widgetBlockTitle);
$settings->add($widgetInvitationText);
$settings->add($widgetLinkText);
$settings->add($widgetHelpUrl);

return;
?>

<table align="center">
  <tr>
    <td style="vertical-align:top;">
      <fieldset style="padding:1em;">
        <legend><?php print_string('config_server', 'block_sl_quamp_course_evaluation') ?></legend>
        <div>
          <p><?php print_string('config_quampserver', 'block_sl_quamp_course_evaluation')?>:</p>

          <p>
            <input id="block_quamp_server" type="text" name="block_quamp_server" style="width:400px" value="<?php echo ($qs = $CFG->block_quamp_server) ? $qs : 'http://' ?>"/>
          </p>
        </div>
        <div>
          <p><?php print_string('config_quampserver_timeout', 'block_sl_quamp_course_evaluation')?>:</p>

          <p>
            <select id="block_quamp_server_timeout" name="block_quamp_server_timeout">
              <?php $to = $CFG->block_quamp_server_timeout ? $CFG->block_quamp_server_timeout : 2; for ($i = 1; $i <= 5; $i++) { ?>
              <option value="<?php echo $i;?>" <?php echo $to == $i ? 'selected="selected"' : ''?>><?php echo $i;?></option>
              <?php }?>
            </select> s
          </p>
        </div>
        <div>
          <p><?php print_string('config_api_key', 'block_sl_quamp_course_evaluation')?>:</p>

          <p>
            <input id="block_quamp_server_api_key" type="text" name="block_quamp_server_api_key" style="width:400px" value="<?php echo ($key = $CFG->block_quamp_server_api_key) ? $key : '' ?>"/>
          </p>
        </div>
        <div>
          <p><?php print_string('config_db_field', 'block_sl_quamp_course_evaluation')?>:</p>

          <p>
            <select id="block_quamp_server_db_field" name="block_quamp_server_db_field">
              <option value="manual" <?php if ($CFG->block_quamp_server_db_field == 'manual') echo 'selected="selected"';?>><?php print_string('config_manual_course_key', 'block_sl_quamp_course_evaluation')?></option>
              <option value="shortname" <?php if ($CFG->block_quamp_server_db_field == 'shortname') echo 'selected="selected"';?>>shortname</option>
              <option value="idnumber" <?php if ($CFG->block_quamp_server_db_field == 'idnumber') echo 'selected="selected"'; ?>>idnumber</option>
            </select>
          </p>
        </div>
        <div>
          <p><?php print_string('config_course_key_allow_overwrite', 'block_sl_quamp_course_evaluation')?></p>

          <p>
            <select id="block_quamp_course_key_allow_overwrite" name="block_quamp_course_key_allow_overwrite">
              <option value="1" <?php if ($CFG->block_quamp_course_key_allow_overwrite == 1) echo 'selected="selected"';?>><?php print_string('config_course_key_allow_overwrite_yes', 'block_sl_quamp_course_evaluation')?></option>
              <option value="2" <?php if ($CFG->block_quamp_course_key_allow_overwrite == 2) echo 'selected="selected"';?>><?php print_string('config_course_key_allow_overwrite_no', 'block_sl_quamp_course_evaluation')?></option>
            </select>
          </p>
        </div>
      </fieldset>
    </td>
    <td style="vertical-align:top;">
      <fieldset style="padding:1em;">
        <legend><?php print_string('config_texts', 'block_sl_quamp_course_evaluation') ?></legend>
        <div>
          <p><?php print_string('config_block_title', 'block_sl_quamp_course_evaluation')?>:</p>

          <p>
            <input id="block_quamp_block_title" type="text" name="block_quamp_block_title" style="width:400px" value="<?php echo ($qbt = $CFG->block_quamp_block_title) ? $qbt
                : get_string('block_title', 'block_sl_quamp_course_evaluation') ?>"/>
          </p>
        </div>
        <div>
          <p><?php print_string('config_invitation_text', 'block_sl_quamp_course_evaluation')?>:</p>

          <p>
            <textarea id="block_quamp_invitation_text" style="width:400px" rows="5" cols="20" name="block_quamp_invitation_text"><?php echo ($inv = $CFG->
            block_quamp_invitation_text) ? $inv : get_string('text', 'block_sl_quamp_course_evaluation') ?></textarea>
          </p>
        </div>
        <div>
          <p><?php print_string('config_quest_link_text', 'block_sl_quamp_course_evaluation')?>:</p>

          <p>
            <input id="block_quamp_quest_link_text" type="text" name="block_quamp_quest_link_text" style="width:400px" value="<?php echo ($qlt = $CFG->block_quamp_quest_link_text) ? $qlt
                : get_string('questionnaire', 'block_sl_quamp_course_evaluation') ?>"/>
          </p>
        </div>
        <div>
          <p><?php print_string('config_help_url', 'block_sl_quamp_course_evaluation')?>:</p>

          <p>
            <input id="block_quamp_help_url" type="text" name="block_quamp_help_url" style="width:400px" value="<?php echo ($hurl = $CFG->block_quamp_help_url) ? $hurl : $CFG->block_quamp_server . '/evaluation/evaluations.html' ?>"/>
          </p>
        </div>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">
      <div style="margin:1em 0;">
        <input type="submit" value="<?php print_string('savechanges'); ?>"/>
      </div>
    </td>
  </tr>
</table>

