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

class profile_field_dynmenu extends profile_field_base {

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
    public $field_wrapper;
    /** @var string $field_prefix */
    public $field_prefix;
    /**
     * Constructor method.
     *
     * Pulls out the options for the dynmenu from the database and sets the the corresponding key for the data if it exists.
     *
     * @param int $fieldid
     * @param int $userid
     */
    public function __construct($fieldid = 0, $userid = 0) {
      global $field_prefix, $field_wrapper;
        // First call parent constructor.
        parent::__construct($fieldid, $userid);

        $field_wrapper ='#fitem_id_profile_field_';
        $field_prefix = '#id_profile_field_';
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
        $this->addMainScriptToPage();

    }
/**
 * add the Javascript to the page where needed
 */
 private function addMainScriptToPage()
 {
   global $field_prefix, $field_wrapper, $CFG;
   //add javascript to the file
   $sStart = '<script type="text/javascript">';
$sEnd = '</script>';
if (!isset($CFG->additionalhtmlfooter)) {
  $CFG->additionalhtmlfooter = "{$sStart}console.log('begin DynMenuLogs');{$sEnd}";
}
$jsMin = $sStart;
 $jsMin .= <<<minjs
 'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var FIELD_PREFIX = '#id_profile_field_';
var FIELD_WRAPPER = '#fitem_id_profile_field_';
/** Class add the hide or Show ability to the children */

var hideShow = function () {
  /**
   * create the vairbles
   * @param {sting} par - Name of the Parent Field
   * @param {array} pv - Values from the parent field
   * @param {array} ct - Children to show
   */
  function hideShow(par, pv, ct) {
    _classCallCheck(this, hideShow);

    this.parent = FIELD_PREFIX + par;
    this.parentValues = pv;
    this.childrenToShow = ct;
  }
  /**
   *  @method  exec
   * @desc Preforms the actions required to hide or show the children fields
   */


  _createClass(hideShow, [{
    key: 'exec',
    value: function exec() {
      var _this = this;

      var currentValue = document.querySelector(this.parent).value;
      var indexes = getAllIndexes(this.parentValues, currentValue);
      this.childrenToShow.forEach(function (child) {
        var thisChild = document.querySelector(FIELD_WRAPPER + child);
        if (thisChild != null) {
          thisChild.setAttribute('aria-hidden', 'true');
          thisChild.value = "-1";
          var event = new Event('change');
          thisChild.dispatchEvent(event);
        }
      });
      indexes.forEach(function (i) {
       document.querySelector(FIELD_WRAPPER + _this.childrenToShow[i]) && document.querySelector(FIELD_WRAPPER + _this.childrenToShow[i]).setAttribute('aria-hidden', 'false');
      });
    }
  }]);

  return hideShow;
}();
/** class updates the values in the child field */


var update = function () {
  /**
   *  create the varibles
   * @param {string} pa - Name of Parent field
   * @param {string} cu - Name of current Field
   * @param {array} pv - Parent Values (a.k.a trigger values)
   * @param {array} cv - children Values (a.k.a update values)
   * @param {string=} sv - Phrase to show as the first option with value of -1
   */
  function update(pa, cu, pv, cv, sv) {
    _classCallCheck(this, update);

    this.parent = FIELD_PREFIX + pa;
    this.currentField = FIELD_PREFIX + cu;
    this.parentValues = pv;
    this.childrenValues = cv;
    this.selectPhrase = sv || 'Select';
  }
  /**
   *  @method  exec
   * @desc Preforms the actions required to hide or show the children fields
   */


  _createClass(update, [{
    key: 'exec',
    value: function exec() {
      var _this2 = this;

      var currentValue = document.querySelector(this.parent).value;
      if (currentValue === "-1") {
        return false;
      }
      var indexes = getAllIndexes(this.parentValues, currentValue);
      var valuesToShow = indexes.map(function (i) {
        return _this2.childrenValues[i];
      });
      var thisSelect = document.querySelector(this.currentField);
      thisSelect.innerHTML = '<option value="-1">' + this.selectPhrase + '</option>';
      valuesToShow.forEach(function (v) {
        var o = document.createElement('option');
        o.text = v;
        o.value = v;
        thisSelect.add(o);
      });
    }
  }]);

  return update;
}();

/**
* get all of the indexes for the values within an array
* @param {array} arr - array to check
* @param {*} value - to be found in array
* @see @link{http://stackoverflow.com/questions/20798477}
*/


function getAllIndexes(arr, val) {
  var indexes = [],
      i;
  for (i = 0; i < arr.length; i++) {
    if (arr[i] === val) indexes.push(i);
  }return indexes;
}
minjs;
$jsMin .= $sEnd;

 if(strpos($CFG->additionalhtmlfooter, $jsMin) === false){
   $CFG->additionalhtmlfooter .= $jsMin;
 }
 $CFG->additionalhtmlfooter .= $sStart.$this->add_JS_arrays_and_exec_call_to_class().$sEnd;
 }
private function add_JS_arrays_and_exec_call_to_class()
{
  $js = '';
  switch ($this->fieldType) {
    case 0:
    //this is a parent field
    //fall through is intended
    case 1:
    //this is a showhide field
     break;
    case 2:
    //this is a update field
    //fall through is intended
    case 3:
    //this is a hideShow and update field
    $js .= $this->updateValueJS();

      break;
    default:
    //This should never be reached
    // TODO: throw error
      # code...
      break;
  }
  $this->add_children_to_parentchildfields();
  return $this->getChildTypeJS().$js;
}
private function updateValueJS()
{
  global $field_prefix, $field_wrapper, $CFG;
  $isFirst = true;
  $js .= "var tvUP_{$this->field->shortname} = [";
   foreach ($this->triggerValues as $key => $tv) {
     if(!$isFirst){
     $js .= ',"'.$tv.'"';
     }
     else {
     $js .= '"'.$tv.'"';
     $isFirst = false;
     }
   }
   $js .= "];\n";
   $isFirst = true;
   $js .= "var caUP_{$this->field->shortname} = [";
    foreach ($this->options as $key => $op) {
      if(!$isFirst){
      $js .= ',"'.$op.'"';
      }
      else {
      $js .= '"'.$op.'"';
      $isFirst = false;
      }
    }
    $js .= "];\n";
    $parentField;
    foreach ($CFG->parentChildFields as $key => $child) {
      if (array_search($this->field->shortname,$child)){
        $parentField = $key;
      }
    }
    if (isset($parentField)){
      $js .= "var update{$this->field->shortname}_{$parentField} = new update('{$parentField}', '{$this->field->shortname}',tvUP_{$this->field->shortname}, caUP_{$this->field->shortname})\n";
      $js .= "document.querySelector('{$field_prefix}' + {$parentField}).addEventListener('change', function () {update{$this->field->shortname}_{$parentField}.exec();});";
    }
    return $js;
}
/**
 * checks the child field types to add the proper JavaScript
 */
 private function getChildTypeJS()
{
   global $field_prefix, $field_wrapper;
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
       $js .= "var show{$this->field->shortname}Children = new hideShow('{$this->field->shortname}',paHS_{$this->field->shortname}, caHS_{$this->field->shortname})\n";
       $js .= "show{$this->field->shortname}Children.exec()\n";
       $js .= "document.querySelector('{$field_prefix}' + '{$this->field->shortname}').addEventListener('change', function () {show{$this->field->shortname}Children.exec();});";
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
 private function add_children_to_parentchildfields()
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
