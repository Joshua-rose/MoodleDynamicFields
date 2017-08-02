const FIELD_PREFIX = '#id_profile_field_';
const FIELD_WRAPPER = '#fitem_id_profile_field_';
/** Class add the hide or Show ability to the children */
class hideShow {
  /**
   * create the vairbles
   * @param {sting} par - Name of the Parent Field
   * @param {array} pv - Values from the parent field
   * @param {array} ct - Children to show
   */
  constructor(par, pv, ct) {
    this.parent = FIELD_PREFIX + par;
    this.parentValues = pv;
    this.childrenToShow = ct;
  }
  /**
   *  @method  exec
   * @desc Preforms the actions required to hide or show the children fields
   */
  exec() {
    var currentValue = document.querySelector(this.parent).value;
    var indexes = getAllIndexes(this.parentValues, currentValue);
    this.childrenToShow.forEach(child => {
      const thisChild = document.querySelector(FIELD_WRAPPER + child);
      if (thisChild.length != null) {
        thisChild.setAttribute('aria-hidden', 'true');
        thisChild.value = "-1";
        var event = new Event('change');
        thisChild.dispatchEvent(event);
      }
    })
    indexes.forEach(i => {
      document.querySelector(FIELD_WRAPPER + this.childrenToShow[i]).setAttribute('aria-hidden', 'false');
    });
  }
}
/** class updates the values in the child field */
class update {
  /**
   *  create the varibles
   * @param {string} pa - Name of Parent field
   * @param {string} cu - Name of current Field
   * @param {array} pv - Parent Values (a.k.a trigger values)
   * @param {array} cv - children Values (a.k.a update values)
   * @param {string=} sv - Phrase to show as the first option with value of -1
   */
  constructor(pa, cu, pv, cv, sv) {
    this.parent = FIELD_PREFIX + pa;
    this.currentField = FIELD_PREFIX + cu
    this.parentValues = pv;
    this.childrenValues = cv;
    this.selectPhrase = sv || 'Select';
  }
  /**
   *  @method  exec
   * @desc Preforms the actions required to hide or show the children fields
   */
  exec() {
    var currentValue = document.querySelector(this.parent).value;
    if (currentValue === "-1") {
      return false;
    }
    var indexes = getAllIndexes(this.parentValues, currentValue);
    var valuesToShow = indexes.map(i => this.childrenValues[i]);
    var thisSelect = document.querySelector(this.currentField);
    thisSelect.innerHTML = `<option value="-1">${this.selectPhrase}</option>`;
    valuesToShow.forEach(v => {
      var o = document.createElement('option');
      o.text = v;
      o.value = v;
      thisSelect.add(o);
    })

  }
}

/**
* get all of the indexes for the values within an array
* @param {array} arr - array to check
* @param {*} value - to be found in array
* @see @link{http://stackoverflow.com/questions/20798477}
*/
function getAllIndexes(arr, val) {
  var indexes = [],
    i;
  for (i = 0; i < arr.length; i++)
    if (arr[i] === val)
      indexes.push(i);
return indexes;
}
