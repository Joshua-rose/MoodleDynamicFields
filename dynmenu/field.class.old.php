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
 * dynmenu profile field.
 *
 * @package    profilefield_dynmenu
 * @copyright  2007 onwards Shane Elliot {@link http://pukunui.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class profile_field_dynmenu
 *
 * @copyright  2007 onwards Shane Elliot {@link http://pukunui.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class profile_field_dynmenu extends profile_field_base {

    /** @var array $options */
    public $options;

    /** @var int $datakey */
    public $datakey;
	
	/** @var array $attributes */
	public $attributes;
	
	/** @var array $compareArray */
	public $compareArray;
	/** @var array $valueArray */
	public $valueArray;
    /**
     * Constructor method.
     *
     * Pulls out the options for the dynmenu from the database and sets the the corresponding key for the data if it exists.
     *
     * @param int $fieldid
     * @param int $userid
     */
    public function profile_field_dynmenu($fieldid = 0, $userid = 0) {
		global $CFG;
	   // First call parent constructor.
        $this->profile_field_base($fieldid, $userid);
		$this->attributes=array('class'=>'dynmenu');
		// Param 1 for dynmenu type is the options.
        if (isset($this->field->param1)) {
            $options = explode("\n", $this->field->param1);
        } else {
            $options = array();
        }
        $this->options = array();
        if (!empty($this->field->required)) {
            $this->options[''] = get_string('choose').'...';
        }
        foreach ($options as $key => $option) {
            $this->options[$option] = format_string($option); // Multilang formatting with filters.
			
        }

        // Set the data key.
        if ($this->data !== null) {
            $key = $this->data;
            if (isset($this->options[$key]) || ($key = array_search($key, $this->options)) !== false) {
                $this->data = $key;
                $this->datakey = $key;
            }
        }
		//clear out the values from previous fields
		if (!empty($this->dynJS)){$this->dynJS = '';} 
		if (isset($dynAddtnlJS)){$dynAddtnlJS = '';} 
			else { $dynAddtnlJS = ''; }
		
		
		//Check Dyn type
		if (isset($this->field->param2)) {
			$this->dynType = $this->field->param2;
			switch ($this->dynType) {
				case 0:
					//code to be executed if dynType is Parent;
					$this->attributes['class'] .= ' dyn_parent';
					$dynAddtnlJS .= $this->dynParent($options);
					//Determin the type of children
					break;
				case 1:
					//code to be executed if dynType is a hide or show me child (could also be a disable/endable child);
					$this->attributes['class'] .= ' dyn_ShowOrNoChild';
					$this->attributes['disabled'] = 'disabled';
					$dynAddtnlJS = $this->dynParent($options);
					break;
				case 2:
					//code to be executed if dynType is an updating child;
					$this->attributes['class'] .= ' dyn_UpdateChild';
					$this->field->param1 = str_replace("\r",'',$this->field->param1);
					$this->field->param5 = str_replace("\r",'',$this->field->param5);
					$trig = explode("\n", $this->field->param5);
					foreach ($trig as $key => $child) {
						$triggers[] = format_string($child); // Multilang formatting with filters. gets rid of unwanted characters at the end of the words
					}
					$dynV = explode("\n", $this->field->param1);
					foreach ($dynV as $key => $child) {
						$dynValues[] = format_string($child); // Multilang formatting with filters. gets rid of unwanted characters at the end of the words
					}
					
					//create the javascript function
					$dynAddtnlJS .= "function update".$this->field->shortname."(parentName) {var triggerArray = []; updateValuesArray = [];\n";
					/*echo 'triggers:'.count($triggers).'  dynValues:'.count($dynValues);
					if (count($triggers) == count($dynValues))
						{
							for ($j=0;$j<count($triggers);$j++)
							{
								$dynAddtnlJS .=	"triggerArray.push('".$triggers[$j]."'); updateValuesArray.push('".$dynValues[$j]."');\n";
							}
						}
					*/					
					//find new values
					$dynAddtnlJS .= "var newValues = [];\n";
					$dynAddtnlJS .= "for (var x=0;x<triggerArray.length;x++) {\n";
					$dynAddtnlJS .= "if ($(parentName).val() == triggerArray[x]) {\n";
					$dynAddtnlJS .= "newValues.push(updateValuesArray[x]);}}\n";
					
					//update field
					$dynAddtnlJS .= "if (newValues.length > 0){"; //make sure there are matching values before clearing out the selection
					$dynAddtnlJS .= "var thisField = $('#id_profile_field_".$this->field->shortname."');\n";
					$dynAddtnlJS .= "$(thisField).find('option').remove();\n"; //clears existing choices
					$dynAddtnlJS .= "$(thisField).end();\n"; // resets the field -- may be redundnat
					$dynAddtnlJS .= "for (var i=0;i<newValues.length;i++){\n"; 
					$dynAddtnlJS .= "$(thisField).append('<option value=\"'+newValues[i]+'\">'+newValues[i]+'</option>');}}\n"; // add in the new values
					$dynAddtnlJS .= "$(thisField).val(newValues[0]); }"; //set the selection to the first option
									
					
					/*
							Remove Options 1 by 1
							http://stackoverflow.com/questions/1518216/jquery-remove-options-from-select
							
							Remove all options
							http://stackoverflow.com/questions/47824/how-do-you-remove-all-the-options-of-a-select-box-and-then-add-one-option-and-se
							
							Add Options
							http://stackoverflow.com/questions/1518216/jquery-remove-options-from-select
							*/
					$dynAddtnlJS .= $this->dynParent($options);
					break;
				case 3:
					//code to be executed if dynType is both a hide and show and an update;
					$this->attributes['class'] .= ' dyn_MultiChild';
					$this->attributes['disabled'] = 'disabled';
					
					$this->field->param1 = str_replace("\r",'',$this->field->param1);
					$this->field->param5 = str_replace("\r",'',$this->field->param5);
					$trig = explode("\n", $this->field->param5);
					foreach ($trig as $key => $child) {
						$triggers[] = format_string($child); // Multilang formatting with filters. gets rid of unwanted characters at the end of the words
					}
					$dynV = explode("\n", $this->field->param1);
					foreach ($dynV as $key => $child) {
						$dynValues[] = format_string($child); // Multilang formatting with filters. gets rid of unwanted characters at the end of the words
					}
					
					//create the javascript function
					$dynAddtnlJS .= "function update".$this->field->shortname."(parentName) {var triggerArray = []; updateValuesArray = [];\n";
					//echo 'triggers:'.count($triggers).'  dynValues:'.count($dynValues);
					if (count($triggers) == count($dynValues))
						{
							for ($j=0;$j<count($triggers);$j++)
							{
								$dynAddtnlJS .=	"triggerArray.push('".$triggers[$j]."'); updateValuesArray.push('".$dynValues[$j]."');\n";
							}
						}
										
					//find new values
					$dynAddtnlJS .= "var newValues = [];\n";
					$dynAddtnlJS .= "for (var x=0;x<triggerArray.length;x++) {\n";
					$dynAddtnlJS .= "if ($(parentName).val() == triggerArray[x]) {\n";
					$dynAddtnlJS .= "newValues.push(updateValuesArray[x]);}}\n";
					
					//update field
					$dynAddtnlJS .= "if (newValues.length > 0){"; //make sure there are matching values before clearing out the selection
					$dynAddtnlJS .= "var thisField = $('#id_profile_field_".$this->field->shortname."');\n";
					$dynAddtnlJS .= "$(thisField).find('option').remove();\n"; //clears existing choices
					$dynAddtnlJS .= "$(thisField).end();\n"; // resets the field -- may be redundnat
					$dynAddtnlJS .= "for (var i=0;i<newValues.length;i++){\n"; 
					$dynAddtnlJS .= "$(thisField).append('<option value=\"'+newValues[i]+'\">'+newValues[i]+'</option>');}}\n"; // add in the new values
					$dynAddtnlJS .= "$(thisField).val(newValues[0]); }"; //set the selection to the first option
					
					$dynAddtnlJS .= $this->dynParent($options);
					break;
				default:
					//code to be executed if n is different from all labels;
			} 
		}
		$this->dynJS = '<script type="text/javascript">';
		$this->dynJS .= $dynAddtnlJS;
		//function ShowChildren(array compareTo)
			//search options array for selected value
				//show all children with that correspond to that value
		$dynAddtnlJS = '';
		$this->dynJS .= '</script>';
		 if (!isset($CFG->additionalhtmlfooter)) {
			$CFG->additionalhtmlfooter = '';
		}
		$CFG->additionalhtmlfooter .= $this->dynJS;
		/* //check if child
		if (!empty($this->field->param2) && !empty($this->field->param3)) {
			$this->attributes['parentID'] = $this->field->param2;
			$this->attributes['triggers'] = $this->field->param3;
		} */
		$this->dynJS = '';
		
    }
	
/*******************************************************************************************************************************
	The section below is a place where the javascript can be added
*******************************************************************************************************************************/
    /**
     * Create the code snippet for this field instance
     * Overwrites the base class method
     * @param moodleform $mform Moodle form instance
     */
    public function edit_field_add($mform) {
		
        $mform->addElement('select', $this->inputname, format_string($this->field->name), $this->options,$this->attributes);
		
		//Add the additional head here******************************************************************************************
		
    }

    /**
     * Set the default value for this field instance
     * Overwrites the base class method.
     * @param moodleform $mform Moodle form instance
     */
    public function edit_field_set_default($mform) {
        $key = $this->field->defaultdata;
        if (isset($this->options[$key]) || ($key = array_search($key, $this->options)) !== false){
            $defaultkey = $key;
        } else {
            $defaultkey = '';
        }
        $mform->setDefault($this->inputname, $defaultkey);
    }

    /**
     * The data from the form returns the key.
     *
     * This should be converted to the respective option string to be saved in database
     * Overwrites base class accessor method.
     *
     * @param mixed $data The key returned from the select input in the form
     * @param stdClass $datarecord The object that will be used to save the record
     * @return mixed Data or null
     */
    public function edit_save_data_preprocess($data, $datarecord) {
        return isset($this->options[$data]) ? $data : null;
    }

    /**
     * When passing the user object to the form class for the edit profile page
     * we should load the key for the saved data
     *
     * Overwrites the base class method.
     *
     * @param stdClass $user User object.
     */
    public function edit_load_user_data($user) {
        $user->{$this->inputname} = $this->datakey;
    }

    /**
     * HardFreeze the field if locked.
     * @param moodleform $mform instance of the moodleform class
     */
    public function edit_field_set_locked($mform) {
        if (!$mform->elementExists($this->inputname)) {
            return;
        }
        if ($this->is_locked() and !has_capability('moodle/user:update', context_system::instance())) {
            $mform->hardFreeze($this->inputname);
            $mform->setConstant($this->inputname, format_string($this->datakey));
        }
    }
    /**
     * Convert external data (csv file) from value to key for processing later by edit_save_data_preprocess
     *
     * @param string $value one of the values in dynmenu options.
     * @return int options key for the dynmenu
     */
    public function convert_external_data($value) {
        if (isset($this->options[$value])) {
            $retval = $value;
        } else {
            $retval = array_search($value, $this->options);
        }

        // If value is not found in options then return null, so that it can be handled
        // later by edit_save_data_preprocess.
        if ($retval === false) {
            $retval = null;
        }
        return $retval;
    }
	public function dynParent($options){
		$this->attributes['class'] .= ' dyn_parent';
		$js = '';
		

		//Determin the type of children
		switch ($this->field->param3) {
			case 1:
				$js = 'show'.$this->field->shortname.'Children();';
				$js .= "$('#id_profile_field_".$this->field->shortname."').attr('onChange','show".$this->field->shortname."Children()');\n";
				// code to be executed if Child Type is HideShow
				
				//create children array fromChildToShow
				$childs = explode("\n", $this->field->param4);
				foreach ($childs as $key => $child) {
					$children[] = format_string($child); // Multilang formatting with filters.

				}			
				//populate JavaScript arrays
				$js .= 'function show'.$this->field->shortname.'Children() {var compareArray = [];var childrenArray = [];'."\n";
				$js .= '/*There are '.count($options).' options and '.count($children).' children'."\n";
				$js .= 'children:';
				foreach ($children as $child)
				{
					$js .=' ,'.$child.'';
				}
				$js .= '*/'."\n";
				if (count($options) == count($children))
				{
					for ($i=0;$i<count($options);$i++){
						$js .= 'compareArray.push("'.$options[$i].'");childrenArray.push("'.$children[$i].'");'."\n";
					}
				}
				$js .= 'var compareTo = $("#id_profile_field_'.$this->field->shortname.'").val();'."\n";
				//$js .= 'var child = "#id_profile_field_" + childrenArray[compareArray.indexOf(compareTo)];'."\n";
				$js .=  'for (var j=0; j<childrenArray.length; j++){'."\n";
				$js .=  'if (childrenArray[j] == childrenArray[compareArray.indexOf(compareTo)]) {'."\n";
				$js .= '$("#id_profile_field_" +childrenArray[j]).prop("disabled", false);}'."\n";
				$js .= 'else {$("#id_profile_field_" +childrenArray[j]).prop("disabled", true); }}'."\n";
				
				/*
				foreach ($childrenArray as $posschild)
				{
					if $posschild == childrenArray[compareArray.indexOf(compareTo)];
					{
						$js .= 'var child = "#id_profile_field_" + '.$posschild."\n";
						$js .= '$(child).prop("disabled", false);'."\n";
					}
					else
					{$js .= '$(child).prop("disabled", true);'."\n";}
				}*/
				/****************************************************
				code to re-disable if the selection changes
				****************************************************/
				$js .='}';
				//Add call to $this->attributes;
				//$this->attributes['onChange'] = 'showDynChildren()';
					
				break;
			case 2:
				// code to be executed if Child Type is UpdateValue
				$js = "$('#id_profile_field_".$this->field->shortname."').attr('onChange','update".$this->field->param4."(\"#id_profile_field_".$this->field->shortname."\")');\n";
				// change is handled by child
				
				
				break;
			case 3:
				// code to be executed if Child Type is Both
				$js .= "$('#id_profile_field_".$this->field->shortname."').attr('onChange','show".$this->field->shortname."Children()');\n";
				$js .= 'show'.$this->field->shortname.'Children();';
				$js .= 'var runUpdate="";';
				$js .= 'var params="";';
				$js .= '$(this).runOnce = false;';
				// code to be executed if Child Type is HideShow
				
				//create children array fromChildToShow
				$childs = explode("\n", $this->field->param4);
				foreach ($childs as $key => $child) {
					$children[] = format_string($child); // Multilang formatting with filters.

				}			
				//populate JavaScript arrays
				$js .= 'function show'.$this->field->shortname.'Children() {var compareArray = [];var childrenArray = [];'."\n";
				$js .= '/*There are '.count($options).' options and '.count($children).' children'."\n";
				$js .= 'children:';
				foreach ($children as $child)
				{
					$js .=' ,'.$child.'';
				}
				$js .= '*/'."\n";
				if (count($options) == count($children))
				{
					for ($i=0;$i<count($options);$i++){
						$js .= 'compareArray.push("'.$options[$i].'");childrenArray.push("'.$children[$i].'");'."\n";
					}
				}
				$js .= "var isDisabled = $('#id_profile_field_".$this->field->shortname."').attr('disabled')\n";
				$js .= "if(isDisabled === false || typeof isDisabled === typeof undefined){\n";
					
					$js .= 'var compareTo = $("#id_profile_field_'.$this->field->shortname.'").val();'."\n";
					//$js .= 'var child = "#id_profile_field_" + childrenArray[compareArray.indexOf(compareTo)];'."\n";
					$js .=  'for (var j=0; j<childrenArray.length; j++){'."\n";
						$js .=  'if (childrenArray[j] == childrenArray[compareArray.indexOf(compareTo)]) {'."\n";
							$js .= '$("#id_profile_field_" +childrenArray[j]).prop("disabled", false);'."\n";
							$js .=  "var updateA = 'update'; updateA=updateA.concat(childrenArray[j]);";
							$js .= "params = '#id_profile_field_".$this->field->shortname."';";
							$js .= "updateAction = updateA.concat(\"(params)\");";
							$js .= "var temphold = $('#id_profile_field_".$this->field->shortname."').attr('onChange');";
							$js .= "if (temphold.indexOf(updateAction) < 0){\n";
								$js .= "$('#id_profile_field_".$this->field->shortname."').attr('onChange',temphold + ';'+updateAction);\n";
								$js .= "with(window){window.eval(updateAction);}";
						$js .= "}}\n";
						
						
						$js .= 'else {$("#id_profile_field_" +childrenArray[j]).prop("disabled", true); }}'."\n";

				$js .= "}}";
				break;
			Default:
				//Should not be reached. No further action is needed.
				break;
		}
		return $js;
	}
}

