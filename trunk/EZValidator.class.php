<?php
/* **************************************************************************************
  * FILE            : EZValidator.class.php
  * CODE            : class EZValidator
  * AUTHOR          : barkley@mitre.org
  * LAST UPDATED    : 29 May 2008
  *
  * DESCRIPTION     : This piece of code is used to validate data against
  *		      various conditions.  For example, a floating point with only
  *		      two decimal places and a preceeding '$' sign, which has a
  *		      value between 0.00 and 45.50 could be validated by setting up
  *		      a minimum condition, a maximum condition, a regular expression
  *		      condition, and then calling validate().
  *		      defaults are included using define.  This class was developed
  *		      specifically for use with the Lime Survey 2.0 (limesurvey.org)
  *		      open source project.
  *
  * USED BY         : EZValidator.class.php
  *
  * LICENSE         :
  *
  *
  * DEPENDS ON      :
  *			RegExpValidator.class.php
  *			CompExpValidator.class.php
  *
  *
  ***************************************************************************************/
require_once('RegExpValidator.class.php');
require_once('CompExpValidator.class.php');
require_once('DateExpValidator.class.php');
require_once('TimeExpValidator.class.php');
require_once('CurrencyExpValidator.class.php');

/******************************************
EXAMPLES:

ALL OF THE FOLLOWING ARE VALID:

  //sample integer validation
  $validator = new RegExpValidator();
  $validator->validate("15", __EZV_INTEGER_REGEXP); //returns true
  $validator->validate("15i", __EZV_INTEGER_REGEXP); //returns false, throws Exception

  $validator->validate("15",  "/^(\+|\-){0,1}[[:digit:]]*$/"); //returns true
  $validator->validate("15i", "/^(\+|\-){0,1}[[:digit:]]*$/"); //returns false, throws Exception


  //sample boolean validation
  $validator = new RegExpValidator();
  $validator->validate("15", __EZV_BOOLEAN_REGEXP); //returns true
  $validator->validate("15i", __EZV_BOOLEAN_REGEXP); //returns false, throws Exception


  //sample unique regexp expression


  //sample numeric validation
  $validator = new CompExpValidator();
  $validator->validate(" < 30    ");
  $validator->validate("< 30", __EZV_BOOLEAN_REGEXP); //returns true
  $validator->validate("<30", __EZV_BOOLEAN_REGEXP); //returns true
  $validator->validate("!15", __EZV_BOOLEAN_REGEXP); //returns true
  $validator->validate(" > = 100", __EZV_BOOLEAN_REGEXP); //returns true


  //sample composite validation
  $validator = new EZValidator();
  $validator->addCriteria(__EZV_INTEGER);
  $validator->addCriteria("<30");
  $validator->addCriteria(">8");


  //sample composite validation date

  //sample composite validation time

  //sample composite validation currency

  /sample composite validation age



  ******************************************/

/******************************************************
 * __EZV_XXXXX will be defined as internal
 * static consts - a registered part of the EZValidator
 * system.
 * All variables of this type defined
 * and used here will be enumerating a set of choices
 *
 * __EZV_XXXXX is used to decrease the possibility of
 * these variables getting mixed up with variables
 * defined elsewhere in the same namespace, such as
 * from downloaded code or php builtins.
 ******************************************************/


/******************************************************
 * define the EZValidator primitives
 ******************************************************/
define ("__EZV_UNKNOWN", -1);
define ("__EZV_REGEXP",   1);
define ("__EZV_COMPEXP",   2);
define ("__EZV_DATE",     3);
define ("__EZV_CURRENCY", 4);
define ("__EZV_STRMAX",   5);
define ("__EZV_LESS_THAN",   6);
define ("__EZV_GREATER_THAN", 7);
define ("__EZV_EMAIL", 8);
define ("__EZV_FLOAT", 9);
define ("__EZV_BOOLEAN", 10);
define ("__EZV_INTEGER", 11);
define ("__EZV_APHABETIC", 12);
define ("__EZV_ALPHA_NUMERIC", 13);
define ("__EZV_EQUAL_TO", 14);
define ("__EZV_CURRENCYEXP", 15);
define ("__EZV_DATEEXP", 16);
define ("__EZV_TIMEEXP", 17);






final class EZValidator {

  private $criteria_count = 0;
  private $type_count = 0;
  //parallell arrays say what validation and what type
  private $criteria_arr = Array();
  private $type_arr = Array();

  //throw error and cause a fuss ?
  //private $die_silently = true;
  private $dieSilently = false;

  public function setDieSilently($dieSilently) { 
    $this->dieSilently = $dieSilently;
  }

  public function getDieSilently() { 
    return $this->dieSilently;
  }

  public $dataCanBeNull = false;

  public $dataCanBeEmpty = false;

  //setter methods for allowing empty or null data
  public function setDataCanBeEmpty($dataCanBeEmpty=false) {
    $this->dataCanBeEmpty = $dataCanBeEmpty;
  }

  public function getDataCanBeEmpty() { 
    return $this->dataCanBeEmpty;
  }

  public function allowEmptyData() {
    $this->setDataCanBeEmpty(true);
  }

  public function disallowEmptyData() {
    $this->setDataCanBeEmpty(false);
  }

  public function setDataCanBeNull($dataCanBeNull=false) {
    $this->dataCanBeNull = $dataCanBeNull;
  }

  public function getDataCanBeNull() {
    return $this->dataCanBeNull;
  }

  public function allowNullData() {
    $this->setDataCanBeNull(true);
  }

  public function disallowNullData() {
    $this->setDataCanBeNull(false);
  }

  public function allowEmptyOrNullData() {
    $this->setDataCanBeEmpty(true);
    $this->setDataCanBeNull(true);
  }

  public function disallowEmptyOrNullData() {
    $this->setDataCanBeEmpty(false);
    $this->setDataCanBeNull(false);
  }



/***************************************************************************************
  * FUNCTION          : validate()
  * AUTHOR            : barkley@mitre.org
  * LAST UPDATED      : 29 May 2008
  * PARAMS            :
  *   $data           : string
  *   $criteria       : string, expected to be a valid expression as defined by the
  *			        specific Validator child class.  Default value is null
  *
  * DESCRIPTION       : This function checks a piece of data against all previously
  *			specified criteria.  An additional criteria which is not normally
  *			a test condition for the instantiated class can be passed
  *			as an optional parameter which will get checked.
  *
  * RETURNS           : Returns true if it matches, false if it does not.
  *
  * EXCEPTIONS        : Error()
  *
  ***************************************************************************************/
  public function validate($data, $criteria=null) {

    $regExpValidator = new RegExpValidator();
    $regExpValidator->setDataCanBeNull($this->getDataCanBeNull());
    $regExpValidator->setDataCanBeEmpty($this->getDataCanBeEmpty());

    $compExpValidator = new CompExpValidator();
    $compExpValidator->setDataCanBeNull($this->getDataCanBeNull());
    $compExpValidator->setDataCanBeEmpty($this->getDataCanBeEmpty());

    $dateExpValidator = new DateExpValidator();
    $dateExpValidator->setDataCanBeNull($this->getDataCanBeNull());
    $dateExpValidator->setDataCanBeEmpty($this->getDataCanBeEmpty());

    $timeExpValidator = new TimeExpValidator();
    $timeExpValidator->setDataCanBeNull($this->getDataCanBeNull());
    $timeExpValidator->setDataCanBeEmpty($this->getDataCanBeEmpty());

    $currencyExpValidator = new currencyExpValidator();
    $currencyExpValidator->setDataCanBeNull($this->getDataCanBeNull());
    $currencyExpValidator->setDataCanBeEmpty($this->getDataCanBeEmpty());

    //if $data is single variable, make it array
    //then continue with looping and checking
    if (!is_array($this->criteria_arr)) {
      //something is wrong... should never hit this case
      throw new Exception("Criteria array not array");
      $this->success = false;
      return $this->success;
    }

/* NOTE, IF NO CRITERIA ARE DEFINED, THEN DATA
   AUTOMATICALLY PASSES.  THIS IS HANDLED NATURALLY
   BY EXECUTION OF THE FUNCTION UNLESS 
   NOT NULL OR NOT EMPTY IS SPECIFIEID AND DATA IS...
*/

    if (count($this->criteria_arr)<1) {
      //test for null data
      if (is_null($data)) {
        if (!$this->getDataCanBeNull()) { 
          throw new Exception("Data cannot be null!");
          $this->success = false;
          return $this->success;
        } else {
          $this->success = true;
          return $this->success;
        }
      }

      //test for empty data
      if (''==$data) {
        if (!$this->getDataCanBeEmpty()) {
          throw new Exception("Data cannot be null!");
          $this->success = false;
          return $this->success;
        } else {
          $this->success = true;
          return $this->success;
        }
      }
    }

    //make sure we're starting from beginning of array
    //if we've looped through partial criteria validating an item
    //and fail, then the next time through the criteria array
    //will only validate from where the previous run stopped
    //example that will break if we don't have this:
    //$validator = new EZValidator();
    //$validator->addCriteria(__EZV_INTEGER_REGEXP);
    //$validator->addCriteria("<30");
    //$validator->addCriteria(">8");
    //$this->assertFalse($validator->validate("8"));
    //$this->assertFalse($validator->validate("30"));
    reset($this->criteria_arr);
    while (list($key,$crit)=each($this->criteria_arr)) {
      switch ($this->type_arr[$key]) {
        case __EZV_REGEXP:
          if (!$regExpValidator->validate($data, $crit)) {
            return false;
          } else {
            break;
          }
        case __EZV_COMPEXP:
          if (!$compExpValidator->validate($data, $crit)) {
            return false;
          } else {
            break;
          }
        case __EZV_DATEEXP:
          if (!$dateExpValidator->validate($data, $crit)) {
            return false;
          } else {
            break;
          }
        case __EZV_TIMEEXP:
          if (!$timeExpValidator->validate($data, $crit)) {
            return false;
          } else {
            break;
          }
        case __EZV_CURRENCYEXP:
          if (!$currencyExpValidator->validate($data, $crit)) {
            return false;
          } else {
            break;
          }

          //should never happen...
//          throw new Error("Cannot handle date or currency types at this time");
//          return false;
        case __EZV_UNKNOWN:
        default:
          if ($this->dieSilently) {
            return false;
          } else {
            //should never happen...
            throw new Error("Cannot determine type");
            return false;
          }
      }
    }


    //if we've made it here, check passed in criteria_count

    //if we've made it here, this data has passed!
    return true;

  }

/***************************************************************************************
  * FUNCTION          : removeCriteria()
  * AUTHOR            : barkley@mitre.org
  * LAST UPDATED      : 29 May 2008
  * PARAMS            :
  *   $criteria       : string, expected to be a valid expression as defined by the
  *			        specific Validator child class.  Default value is null
  *
  * DESCRIPTION       : This function removes a previously specified criteria from the
  *			instantiated class
  *
  * RETURNS           : Returns true if it successfully identifies and removes criteria,
  *			false if it does not.
  *
  * EXCEPTIONS        : None
  *
  ***************************************************************************************/
  public function removeCriteria($criteria) {

  }

  public function addCriteria($criteria, $type=null) {
    //self-identify criteria
    //regexp, which type of validator?
    switch($type) {
      case __EZV_REGEXP:
      case __EZV_COMPEXP:
      case __EZV_DATE:
      case __EZV_CURRENCY:
        //all of these cases are fine, and we just pass through to data structure
        //add to parallell arrays
        $this->criteria_arr[$this->criteria_count++] = $criteria;
        $this->type_arr[$this->type_count++] = $type;
        return ($this->criteria_count-1);

      case __EZV_UNKNOWN:
      default:
        //check $criteria for regexp, numexp, date, currency
        $cev = new CompExpValidator();
        $rev = new RegExpValidator();
        $dev = new DateExpValidator();
        $tev = new TimeExpValidator();
        $cuev = new CurrencyExpValidator();
        if ($cev->isValidCriteria($criteria)) {
          $this->criteria_arr[$this->criteria_count++] = $criteria;
          $this->type_arr[$this->type_count++] = __EZV_COMPEXP;
          return ($this->criteria_count-1);
        } else if ($rev->isValidCriteria($criteria)) {
          $this->criteria_arr[$this->criteria_count++] = $criteria;
          $this->type_arr[$this->type_count++] = __EZV_REGEXP;
          return ($this->criteria_count-1);
        } else if ($dev->isValidCriteria($criteria)) {
          $this->criteria_arr[$this->criteria_count++] = $criteria;
          $this->type_arr[$this->type_count++] = __EZV_DATEEXP;
          return ($this->criteria_count-1);
        } else if ($tev->isValidCriteria($criteria)) {
          $this->criteria_arr[$this->criteria_count++] = $criteria;
          $this->type_arr[$this->type_count++] = __EZV_TIMEEXP;
          return ($this->criteria_count-1);
        } else if ($cuev->isValidCriteria($criteria)) {
          $this->criteria_arr[$this->criteria_count++] = $criteria;
          $this->type_arr[$this->type_count++] = __EZV_CURRENCYEXP;
          return ($this->criteria_count-1);
        } else {
          if ($dieSilently) {
            return false;
          } else {
            throw new Exception("Cannot determine criteria type");
            return false;
          }
        }
    }

  }


  public function lengthGreaterThan($max) { addCriteria(__EZV_STRMAX + $max); }

  public function lengthLessThan($min) { addCriteria(__EZV_STRMAX + $min); }

  public function lessThan($max) { addCriteria(__EZV_LESS_THAN + $max); }

  public function greaterThan($min) { addCriteria(__EZV_GREATER_THAN + $min); }

  public function email() { addCriteria(__EZV_EMAIL); }

  public function ip() { addCriteria(__EZV_IP); }

//  public function addDate($locale) { addCriteria(__EZV_DATE); }
//  public function addCurrency($locale) { addCriteria(__EZV_DATE); }

  public function boolean() { addCriteria(__EZV_BOOLEAN); }

  public function integer() { addCriteria(__EZV_INTEGER); }

  public function float($precision=null) { addCriteria(__EZV_FLOAT); }

  public function alphabetic() { addCriteria(__EZV_ALPHABETIC); }

  public function alphaNumeric() { addCriteria(__EZV_ALPHA_NUMERIC); }

  public function numeric() { addCriteria(__EZV_NUMERIC); }

  public function range($min, $max) {


  }

  public function rangeInclusive($min, $max) {

  }

  public function rangeExclusive($min, $max) {

  }


  public function not($exclude) {

  }

  public function serializeToDojo($version='1.1.1') {
    reset($this->criteria_arr);

    $dojoString = "";

    while (list($key,$crit)=each($this->criteria_arr)) {
      switch ($this->type_arr[$key]) {
        case __EZV_REGEXP:
          $dojoString .= "regExp=$crit";

          //$dojoString += "regExp=$crit ";
          $regExpValidator = new RegExpValidator();
          //$dojoString += $regExpValidator->getDojoFragment($crit);
          break;
        case __EZV_COMPEXP:
//    $compExpValidator = new CompExpValidator();
          $dojoString += ''; //
          break;
        case __EZV_DATE:
        case __EZV_CURRENCY:
          //should never happen...
          throw new Error("Cannot handle date or currency types at this time");
	  break;
        case __EZV_UNKNOWN:
        default:
          //should never happen...
          throw new Error("Cannot determine type");
          break;
      }
    }

    return $dojoString;

  }

  public function getDojoName($criteria_key) {
    if (!$this->criteria_arr[$criteria_key]) {
      throw new Error("Invalid criteria key in getDojoName");
      return false;
    } else {
      return "regExp";
    }
  }

  public function getDojoValue($criteria_key) {
    if (!$this->criteria_arr[$criteria_key]) {
      throw new Error("Invalid criteria key in getDojoName");
      return false;
    }

    switch($this->type_arr[$criteria_key]) {
      case __EZV_REGEXP:
        //get expression
        $val = $this->criteria_arr[$criteria_key];
        //remove delimeters because dojo does not need them
        $val = substr($val, 0, strlen($val)-1);
        $val = substr($val, 1, strlen($val));
        return $val;
      case __EZV_COMPEXP:
        return ''; //$value;
      case __EZV_DATE:
      case __EZV_CURRENCY:
        //should never happen...
        throw new Error("Type not supported");
        return false;
      case __EZV_UNKNOWN:
      default:
        //should never happen...
        throw new Error("Cannot determine type");
        return false;
    }

  }


  /**********************************
   *
   * STATIC FUNCTION
   *
  **********************************/
  public function getDojoFragmentAsArray($criteria) {
    $validator = new EZValidator();
    $criteria_key = $validator->addCriteria($criteria);
    $name = $validator->getDojoName($criteria_key);
    $value = $validator->getDojoValue($criteria_key);

    $retval[] = $name;
    $retval[] = $value;



    return $retval;
  }


  public function serializeToDojoAsArray() {
    $regExpValidator = new RegExpValidator();
    $compExpValidator = new CompExpValidator();

    $constraintParams = array();
    $dojoParams = array();


    if (!is_array($this->criteria_arr)) {
      //something is wrong... should never hit this case
    }

    //make sure we're starting from beginning of array
    reset($this->criteria_arr);

    while (list($key,$crit)=each($this->criteria_arr)) {
      switch ($this->type_arr[$key]) {
        case __EZV_REGEXP:
          $name = $this->getDojoName($key);
          $value = $this->getDojoValue($key);
          $dojoParams[$name] = $value;
          break;
        case __EZV_COMPEXP:
          $constraintParams[] = $compExpValidator->getDojoConstraint($crit);
          break;
        case __EZV_DATE:
        case __EZV_CURRENCY:
          //should never happen...
          //throw new Error("Cannot handle date or currency types at this time");
          //return false;
          break;
        case __EZV_UNKNOWN:
        default:
          //should never happen...
          //throw new Error("Cannot determine type");
          //return false;
          break;
      }
    }


    //if we've made it here, check passed constraint_params
    if (count($constraintParams)>0) {
      $dojoParams['constraints'] = '{' . join(",", $constraintParams) . '}';
    }

    //check for dojo "required" constraints
    if (!$this->getDataCanBeNull() && !$this->getDataCanBeEmpty()) {
      $dojoParams['required'] = 'true';
    }

    //if we've made it here, this data has passed!
    return $dojoParams;
  }



}

?>
