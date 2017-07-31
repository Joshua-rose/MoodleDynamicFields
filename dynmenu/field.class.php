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
 * @copyright  2016 onwards Joshua Rose {@link https://github.com/jrgiant/MoodleDynamicFields/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class profile_field_dynmenu
 *
 * @copyright  2016 onwards Joshua Rose {@link https://github.com/jrgiant/MoodleDynamicFields/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

    /** @var array $options */
    public $options;

    /** @var int $datakey */
    public $datakey;

    /** @var int $fieldType */
    public $fieldType;

    /** @var int $childType */
    public $childType;

    /** @var array $childName */
    public $childName;

    /** @var array $triggerValues */
    public $triggerValues;

    /** @var string $field_wrapper */
    $field_wrapper ='#fitem_id_profile_field_';
    /** @var string $field_prefix */
    $field_prefix = '#id_profile_field_';
    /**
     * Constructor method.
     *
     * Pulls out the options for the dynmenu from the database and sets the the corresponding key for the data if it exists.
     *
     * @param int $fieldid
     * @param int $userid
     */
    public function __construct($fieldid = 0, $userid = 0) {
        // First call parent constructor.
        parent::__construct($fieldid, $userid);
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
            // Multilang formatting with filters.
            $this->options[$option] = format_string($option, true, ['context' => context_system::instance()]);
        }
        // Param 2 for dynmenu is the dynamic Menu field type {integer}
        if (isset($this->field->param2)) {
          $fieldType = $this->field->param2;
        } else {
          $fieldType = 0;
        }
        $this->fieldType = $fieldType;
        // Param 3 for dynmenu is the child dynamic Menu field type (if applicable) {integer|null}
        if (isset($this->field->param3)) {
          $childType = $this->field->param3;
        }
        else {
          $childType = null;
        }
        $this->childType = $childType;
        // Param 4 for dynmenu is the Name(s) of child(ren) field(s) to show {string|Array}
        if (isset($this->field->param4)) {
            $childrenNames = explode("\n", $this->field->param4);
        } else {
            $childrenNames = array();
        }
        $this->childrenNames = array();
        foreach ($childrenNames as $key => $child) {
          // Multilang formatting with filters.
          $this->childrenNames[$child] = format_string($child, true, ['context' => context_system::instance()]);
        }
        // Param 5 for dynmenu is the values that trigger changes in the Update Fields {array}
        if (isset($this->field->param5)) {
            $triggerValues = explode("\n", $this->field->param5);
        } else {
            $triggerValues = array();
        }
        $this->triggerValues = array();
        foreach ($triggerValues as $key => $trigger) {
          // Multilang formatting with filters.
          $this->triggerValues[$trigger] = format_string($trigger, true, ['context' => context_system::instance()]);
        }
        // Set the data key.
        if ($this->data !== null) {
            $key = $this->data;
            if (isset($this->options[$key]) || ($key = array_search($key, $this->options)) !== false) {
                $this->data = $key;
                $this->datakey = $key;
            }
        }
        addMainScriptToPage();

    }
/**
 * add the Javascript to the page where needed
 */
 public function addMainScriptToPage()
 {
   //add javascript to the file
   if (!isset($CFG->additionalhtmlfooter)) {
     $CFG->additionalhtmlfooter = '';
   }
   $sStart = '<script type="text/javascript">';
$sEnd = '</script>';
$CFG->additionalhtmlfooter .= $sStart.$this->dynmenu_add_javascript($fieldType).$sEnd;
$jsMin = $sStart;
 $jsMin .= "var _createClass=function(){function a(b,c){for(var e,d=0;d<c.length;d++)e=c[d],e.enumerable=e.enumerable||!1,e.configurable=!0,'value'in e&&(e.writable=!0),Object.defineProperty(b,e.key,e)}return function(b,c,d){return c&&a(b.prototype,c),d&&a(b,d),b}}();function _classCallCheck(a,b){if(!(a instanceof b))throw new TypeError('Cannot call a class as a function')}var ".$GLOBALS[field_prefix]."='#id_profile_field_',".$GLOBALS[field_wrapper]."='#fitem_id_profile_field_',hideSho=function(){function";
 $jsMin .= "a(b,c,d){_classCallCheck(this,a),this.parent=".$GLOBALS[field_prefix]."+b,this.parentValues=c,this.childrenToShow=d}return _createClass(a,[{key:'exec',value:function exec(){var d=this,b=document.querySelector(this.parent).value,c=getAllIndexes(this.parentValues,b);this.childrenToShow.forEach(function(e){document.querySelector(".$GLOBALS[field_wrapper]."+e).setAttribute('aria-hidden','true'),document.querySelector(".$GLOBALS[field_prefix]."+e).value='-1';var f=new";
 $jsMin .= "Event('change');document.querySelector(".$GLOBALS[field_prefix]."+e).dispatchEvent(f)}),c.forEach(function(e){document.querySelector(".$GLOBALS[field_wrapper]."+d.childrenToShow[e]).setAttribute('aria-hidden','false')})}}]),a}(),update=function(){function a(b,c,d,e,f){_classCallCheck(this,a),this.parent=".$GLOBALS[field_prefix]."+b,this.currentField=".$GLOBALS[field_prefix]."+c,this.parentValues=d,this.childrenValues=e,this.selectPhrase=f||'Select'}return _createClass(a,[{key:'exec',value:function exec(){var";
 $jsMin .= "f=this,b=document.querySelector(this.parent).value;if('-1'===b)return!1;var c=getAllIndexes(this.parentValues,b),d=c.map(function(g){return f.childrenValues[g]}),e=document.querySelector(this.currentField);e.innerHTML='<option value=\"-1\">'+this.selectPhrase+'</option>',d.forEach(function(g){var h=document.createElement('option');h.text=g,h.value=g,e.add(h)})}}]),a}();function getAllIndexes(a,b){var d,c=[];for(d=0;d<a.length;d++)a[d]===b&&c.push(d);return c}";
$jsMin .= $sEnd;

 else if(strpos($CFG->additionalhtmlfooter, $jsMin) === false){
   $CFG->additionalhtmlfooter .= $jsMin;
 }
 }
public function add_JS_arrays_and_exec_call_to_class()
{
  $js = '';
  switch ($this->fieldType) {
    case 0:
    //this is a parent field
    // TODO: Show Hide/Show Children
      # code...
      break;
    case 1:
    //this is a showhide field
    //code should be handeled by parent
      # code...
      break;
    case 2:
    //this is a update field

      # code...
      break;
    case 3:
    //this is a showhide and update field
    // TODO:
      # code...
      break;
    default:
    //This should never be reached
    // TODO: throw error
      # code...
      break;
  }
  add_children_to_parentchildfields();
  return getChildTypeJS()+$js;
}
/**
 * checks the child field types to add the proper JavaScript
 */
 public function getChildTypeJS()
 {
   $js = '';
   switch ($this->childType) {
     case 1:
     case 3:
     $isFirst = true;
     $js .= "var paHS_{$this->field->shortname} = [";
      foreach ($this->options as $key => $option) {
        if(!$isFirst){
        $js .= ',"'.$option.'"';
        }
        else {
        $js .= '"'.$option.'"';
        $isFirst = false;
        }
      }
      $js .= "];\n";
      $isFirst = true;
      $js .= "var caHS_{$this->field->shortname} = [";
       foreach ($this->childrenNames as $key => $child) {
         if(!$isFirst){
         $js .= ',"'.$child.'"';
         }
         else {
         $js .= '"'.$child.'"';
         $isFirst = false;
         }
       }
       $js .= "];\n";
       $js .= "var show{$this->field->shortname}Children = new hideShow('{$this->field->shortname}',psHS_{$this->field->shortname}, csHS_{$this->field->shortname})\n";
       $js .= "show{$this->field->shortname}Children.exec()\n";
       $js .= "document.querySelector({$GLOBALS[field_prefix]} + '{$this->field->shortname}').addEventListener('change', function () {show{$this->field->shortname}Children.exec();});";
       break;
     case 2:

       break;
     default:
       # code...
       break;
   }
   return $js;
 }
/**
 * Add the children elements to the $CFG->parentChildFields array
 */
 public function add_children_to_parentchildfields()
 {
   global $CFG;
   if (!isset($CFG->parentChildFields)) {
     $CFG->parentChildFields = array();
   }
   $CFG->parentChildFields[$this->field->shortname] = $this->childrenNames;
 }
    /**
     * Old syntax of class constructor. Deprecated in PHP7.
     *
     * @deprecated since Moodle 3.1
     */
    public function profile_field_dynmenu($fieldid=0, $userid=0) {
        self::__construct($fieldid, $userid);
    }

    /**
     * Create the code snippet for this field instance
     * Overwrites the base class method
     * @param moodleform $mform Moodle form instance
     */
    public function edit_field_add($mform) {
        $mform->addElement('select', $this->inputname, format_string($this->field->name), $this->options);
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

    /**
     * Return the field type and null properties.
     * This will be used for validating the data submitted by a user.
     *
     * @return array the param type and null property
     * @since Moodle 3.2
     */
    public function get_field_properties() {
        return array(PARAM_TEXT, NULL_NOT_ALLOWED);
    }
}
