<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * dynmenu profile field definition.
 *
 * @package    profilefield_dynmenu
 * @copyright  2015 onwards VISN 11 VERC, VA-CASE
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class profile_define_dynmenu
 *
 * @copyright  2015 onwards VISN 11 VERC, VA-CASE
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class profile_define_dynmenu extends profile_define_base {

    /**
     * Adds elements to the form for creating/editing this type of profile field.
     * @param moodleform $form
     */
    public function define_form_specific($form) {
        // Param 1 for dynmenu type contains the options.
	      $form->addElement('textarea', 'param1', get_string('profilemenuoptions', 'admin'), array('rows' => 6, 'cols' => 40));
        $form->setType('param1', PARAM_TEXT);

        // Default data.
        $form->addElement('text', 'defaultdata', get_string('profiledefaultdata', 'admin'), 'size="50"');
        $form->setType('defaultdata', PARAM_TEXT);

		   //Get Dynmenu Type
		    $form->addElement('select', 'param2', 'Dynamic Field Type', array("Parent","Enable-Disable","Update","Enable-Update"));
		  //PARAM_TEXT equivalent is not needed. According to docs.moodle.org select, radio box and checkboxes do not need the cleanup function provided by settype

		  //If Parent
		   $form->addElement('select', 'param3', 'Child Field Type', array("Not-Parent(i.e. Child without grandchildren)","Enable-Disable","Update","Enable-Update"));
		   //if child is update
		   $form->addElement('textarea', 'param4', 'Child Fields\n'.get_string('blankparent','profilefield_dynmenu').'\n (The options in this field MUST correspond to the'.get_string('profilemenuoptions', 'admin').' )', array('rows' => 6, 'cols' => 40));
       $form->setType('param4', PARAM_TEXT);

		////////if update
		  $form->addElement('textarea', 'param5', get_string('updatefieldinfo','profilefield_dynmenu'), array('rows' => 6, 'cols' => 40));
      $form->setType('param5', PARAM_TEXT);

    }
	/**
	* Alters the form after it is defined with existing data
	* @param mform &$form
	*/


    /**
     * Validates data for the profile field.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function define_validate_specific($data, $files) {
        $err = array();
		echo 'Error is after define.class.php.loc 2 (line ~85)';
        $data->param1 = str_replace("\r", '', $data->param1);

        // Check that we have at least 2 options.
        if (($options = explode("\n", $data->param1)) === false) {
            $err['param1'] = get_string('profiledynmenunooptions', 'admin');
        } else if (count($options) < 2) {
            $err['param1'] = get_string('profiledynmenutoofewoptions', 'admin');
        } else if (!empty($data->defaultdata) and !in_array($data->defaultdata, $options)) {
            // Check the default data exists in the options.
            $err['defaultdata'] = get_string('profiledynmenudefaultnotinoptions', 'admin');
        }

		//Validation of Custom Fields
		if ($data->param2 == 0 && $data->param3 == 1)
		{
			$data->param4 = str_replace("\r", '', $data->param4);
			$parentOptions = explode("\n", $data->param1);
			if (($options = explode("\n", $data->param4)) === false) {
				$err['param4'] = get_string('nooptions', 'profilefield_dynmenu');
			} else if (count($options) != count($parentOptions)) {
				$err['param4'] = get_string('wrongnumberoptions', 'profilefield_dynmenu');
			}

		}

    /**
     * Processes data before it is saved.
     * @param array|stdClass $data
     * @return array|stdClass
     */
    public function define_save_preprocess($data) {
		echo 'Error is after define.class.php.loc 3 (line ~105)';
        $data->param1 = str_replace("\r", '', $data->param1);
		$data->param4 = str_replace("\r", '', $data->param4);
		$data->param5 = str_replace("\r", '', $data->param5);	
        return $data;
    }

}
