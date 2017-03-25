const FIELD_PREFIX ='#id_profile_field_';
const FIELD_WRAPPER = '#fitem_id_profile_field_';
class hideShow{
  constructor(par, pv, ct){
    this.parent = FIELD_PREFIX + par;
    this.parentValues = pv;
    this.childrenToShow = ct;
  }
  exec(){
    var currentValue = document.querySelector(this.parent).value;
    var indexes = getAllIndexes(this.parentValues, currentValue);
    this.childrenToShow.forEach(child => {document.querySelector(FIELD_WRAPPER + child).setAttribute('aria-hidden', 'true');
                                         document.querySelector(FIELD_PREFIX + child).value = "-1";
                                         var event = new Event('change');
                                          document.querySelector(FIELD_PREFIX + child).dispatchEvent(event);
                                         })
    indexes.forEach(i => {
      document.querySelector(FIELD_WRAPPER + this.childrenToShow[i]).setAttribute('aria-hidden','false');
    });
  }
}

/**
* get all of the indexes for the values within an array
* @param {array} arr - array to check
* @param {*} value - to be found in array
* @see @link{http://stackoverflow.com/questions/20798477}
*/
function getAllIndexes(arr, val) {
    var indexes = [], i;
    for(i = 0; i < arr.length; i++)
        if (arr[i] === val)
            indexes.push(i);
    return indexes;
}
