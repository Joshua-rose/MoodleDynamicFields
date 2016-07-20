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
		echo 'Error is after define.class.php.loc 1 (line ~39)';
        $form->addElement('textarea', 'param1', get_string('profilemenuoptions', 'admin'), array('rows' => 6, 'cols' => 40));
        $form->setType('param1', PARAM_TEXT);

        // Default data.
        $form->addElement('text', 'defaultdata', get_string('profiledefaultdata', 'admin'), 'size="50"');
        $form->setType('defaultdata', PARAM_TEXT);
		
		//Get Dynmenu Type
		$form->addElement('select', 'param2', 'Dynamic Field Type', array("Parent","Enable-Disable","Update","Enable-Update"));
		//PARAM_TEXT equivalent is not needed. According to docs.moodle.org select, radio box and checkboxes do not need the cleanup function provided by settype
		
		//If Parent
		$form->addElement('select', 'fielddynchildtype', 'Child Field Type'.get_string('blankchild','profilefield_dynmenu'), array("Not-Parent(i.e. Child without grandchildren)","Enable-Disable","Update","Enable-Update"));
		//if child is update
		$form->addElement('textarea', 'dynchildnames', 'Child Fields'.get_string('blankparent','profilefield_dynmenu').' (must match the options to work correctly)', array('rows' => 6, 'cols' => 40));
        $form->setType('dynchildnames', PARAM_TEXT);
		
		//If Child
		$form->addElement('text', 'dynparentshortname', 'Parent Field'.get_string('blankchild','profilefield_dynmenu'), 'size=50');
		$form->setType('dynparentshortname',PARAM_TEXT);
		
		
		////////If Enable/diable
		$form->addElement('textarea', 'dyngrandchildnames', 'Grandchildren fields'.get_string('blankgrandchild','profilefield_dynmenu').' (must match the options to work correctly)'."\nEnable Field with Grandchildren", array('rows' => 6, 'cols' => 40));
        $form->setType('dyngrandchildnames', PARAM_TEXT);
		
		
		////////if update
		$form->addElement('textarea', 'dynparentvalues', 'Enter the options of the parent field that cause update in this field'.get_string('blankchild','profilefield_dynmenu')."\nUpdateField", array('rows' => 6, 'cols' => 40));
        $form->setType('dynparentvalues', PARAM_TEXT);
		
		$form->addElement('textarea', 'dynupdatedvalues', 'Enter the values that should correspond to the options of the parent field'.get_string('blankchild','profilefield_dynmenu')."\nUpdateField", array('rows' => 6, 'cols' => 40));
        $form->setType('dynupdatedvalues', PARAM_TEXT);
		
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
		if ($data->param2 == 0 && $data->fielddynchildtype == 1)
		{
			$data->dynchildnames = str_replace("\r", '', $data->dynchildnames);
			$parentOptions = explode("\n", $data->param1);
			if (($options = explode("\n", $data->dynchildnames)) === false) {
				$err['dynchildnames'] = get_string('nooptions', 'profilefield_dynmenu');
			} else if (count($options) != count($parentOptions)) {
				$err['dynchildnames'] = get_string('wrongnumberoptions', 'profilefield_dynmenu');
			}
			
		}
		
		//Verify Update-Field and MuiltiField do not have children
		/*if ($data->param2 == 2 && $data->fielddynchildtype != 0)
		{
			$err['fielddynchildtype'] = get_string('updatedoesnotsupportchildren','profilefield_dynmenu');
		}
		else if  ($data->param2 == 3 && $data->fielddynchildtype != 0)
		{
			$err['fielddynchildtype'] = get_string('multidoesnotsupportchildren','profilefield_dynmenu');
		}*/
		
		
		var_dump($data);
        return $err;
    }

    /**
     * Processes data before it is saved.
     * @param array|stdClass $data
     * @return array|stdClass
     */
    public function define_save_preprocess($data) {
		echo 'Error is after define.class.php.loc 3 (line ~105)';
        $data->param1 = str_replace("\r", '', $data->param1);
		$data->dynchildnames = str_replace("\r", '', $data->dynchildnames);
		$data->dynparentvalues = str_replace("\r", '', $data->dynparentvalues);
		//seperate data from the remaining fields
		switch ($data->param2) {
			case 0: //is Parent
				$data->param3 = $data->fielddynchildtype;	
				$data->param4 = $data->dynchildnames;
				break;
			case 1: //is Enable/Disable
				$data->param3 = $data->fielddynchildtype;
				$data->param4 = $data->dynchildnames;
				
				break;
			case 2: //is Update
				$data->param3 = $data->dynparentshortname;
				$data->param4 = $data->dynchildnames;
				$data->param5 = $data->dynparentvalues;
				break;
			case 3: //is Multi
				$data->param3 = $data->dynparentshortname;
				$data->param4 = $data->dynchildnames;
				$data->param5 = $data->dynparentvalues;
				break;
		
		}
		
		
        return $data;
    }

}


