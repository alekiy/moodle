<?php

class block_loginup extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_loginup');
    }

    function applicable_formats() {
        return array('site' => true);
    }

    function get_content () {
        global $USER, $CFG, $SESSION;
        $wwwroot = '';
        $signup = '';

        if ($this->content !== NULL) {
            return $this->content;
        }

        if (empty($CFG->loginhttps)) {
            $wwwroot = $CFG->wwwroot;
        } else {
            // This actually is not so secure ;-), 'cause we're
            // in unencrypted connection...
            $wwwroot = str_replace("http://", "https://", $CFG->wwwroot);
        }

        if (!empty($CFG->registerauth)) {
            $authplugin = get_auth_plugin($CFG->registerauth);
            if ($authplugin->can_signup()) {
                $signup = $wwwroot . '/login/signup.php';
            }
        }
        // TODO: now that we have multiauth it is hard to find out if there is a way to change password
        $forgot = $wwwroot . '/login/forgot_password.php';

        if (empty($CFG->xmlstrictheaders) and !empty($CFG->loginpasswordautocomplete)) {
            $autocomplete = 'autocomplete="off"';
        } else {
            $autocomplete = '';
        }

        $username = get_moodle_cookie();

        $this->content = new stdClass();
        $this->content->footer = '';
        $this->content->text = '';

        if (!isloggedin() or isguestuser()) {   // Show the block

            $this->content->text .= "\n".'<form class="loginform" id="login" method="post" action="'.get_login_url().'" '.$autocomplete.'>';

           	$this->content->text .= '<div class="c1 fld username"><input type="text" name="username" id="login_username" value="'.s($username).'" /></div>';

            $this->content->text .= '<div class="c1 fld password"><input type="password" name="password" id="login_password" value="" '.$autocomplete.' /></div>';
            
            if (isset($CFG->rememberusername) and $CFG->rememberusername == 2) {
            	$checked = $username ? 'checked="checked"' : '';
            	$this->content->text .= '<div class="c1 rememberusername"><input type="checkbox" name="rememberusername" id="rememberusername" value="1" '.$checked.'/>';
            	$this->content->text .= ' <label for="rememberusername">'.get_string('rememberusername', 'admin').'</label></div>';
            }

            $this->content->text .= '<div class="c1 btn">';
            
            if (!empty($signup)) {
            	$this->content->text .= '<a href="'.$signup.'">'.get_string('help').'</a>';
            }
            if (!empty($forgot)) {
            	$this->content->text .= '<a href="'.$forgot.'">'.get_string('help').'</a>';
            }
            
            $this->content->text .= '<input type="submit" value="'.get_string('login').'" /></div>';
                       
            $this->content->text .= "</form>\n";
                  
        }

        return $this->content;
    }
}


