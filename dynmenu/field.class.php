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
 $field_wrapper ='#fitem_id_profile_field_';
 $field_prefix = '#id_profile_field_';
 $sStart = '<script type="text/javascript">';
$sEnd = '</script>';
$CFG->additionalhtmlfooter .= $sStart.$this->dynmenu_add_javascript($fieldType).$sEnd;
$jsMin = $sStart;
 $jsMin .= "var _createClass=function(){function a(b,c){for(var e,d=0;d<c.length;d++)e=c[d],e.enumerable=e.enumerable||!1,e.configurable=!0,'value'in e&&(e.writable=!0),Object.defineProperty(b,e.key,e)}return function(b,c,d){return c&&a(b.prototype,c),d&&a(b,d),b}}();function _classCallCheck(a,b){if(!(a instanceof b))throw new TypeError('Cannot call a class as a function')}var ".$field_prefix."='#id_profile_field_',".$field_wrapper."='#fitem_id_profile_field_',hideSho=function(){function";
 $jsMin .= "a(b,c,d){_classCallCheck(this,a),this.parent=".$field_prefix."+b,this.parentValues=c,this.childrenToShow=d}return _createClass(a,[{key:'exec',value:function exec(){var d=this,b=document.querySelector(this.parent).value,c=getAllIndexes(this.parentValues,b);this.childrenToShow.forEach(function(e){document.querySelector(".$field_wrapper."+e).setAttribute('aria-hidden','true'),document.querySelector(".$field_prefix."+e).value='-1';var f=new";
 $jsMin .= "Event('change');document.querySelector(".$field_prefix."+e).dispatchEvent(f)}),c.forEach(function(e){document.querySelector(".$field_wrapper."+d.childrenToShow[e]).setAttribute('aria-hidden','false')})}}]),a}(),update=function(){function a(b,c,d,e,f){_classCallCheck(this,a),this.parent=".$field_prefix."+b,this.currentField=".$field_prefix."+c,this.parentValues=d,this.childrenValues=e,this.selectPhrase=f||'Select'}return _createClass(a,[{key:'exec',value:function exec(){var";
 $jsMin .= "f=this,b=document.querySelector(this.parent).value;if('-1'===b)return!1;var c=getAllIndexes(this.parentValues,b),d=c.map(function(g){return f.childrenValues[g]}),e=document.querySelector(this.currentField);e.innerHTML='<option value=\"-1\">'+this.selectPhrase+'</option>',d.forEach(function(g){var h=document.createElement('option');h.text=g,h.value=g,e.add(h)})}}]),a}();function getAllIndexes(a,b){var d,c=[];for(d=0;d<a.length;d++)a[d]===b&&c.push(d);return c}";
$jsMin .= $sEnd;

 else if(strpos($CFG->additionalhtmlfooter, $jsMin) === false){
   $CFG->additionalhtmlfooter .= $jsMin;
 }
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
