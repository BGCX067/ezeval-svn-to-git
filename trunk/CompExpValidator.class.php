<?php
/* **************************************************************************************
  * FILE            : CompExpValidator.class.php
  * CODE            : class CompExpValidator
  * AUTHOR          : barkley@mitre.org
  * LAST UPDATED    : 29 May 2008
  *
  * DESCRIPTION     : This piece of code is used to validate data against
  *		      comparative expressions, such as "<30", "!=15.5", etc.
  *		      Any one of the following expressions can be used:
  *		      <, >, <=, >=, =, !=
  *		      or several defaults are included using define.  This class can be used
  *		      stand-alone or in conjunction with other libraries, but
  *		      was developed specifically for use with the EZValidator library.
  *
  * USED BY         : EZValidator.class.php
  *
  * LICENSE         :
  *
  *
  * DEPENDS ON      :
  *			DefaultValidator.class.php
  *			ValidatorInterface.php
  *
  *
  ***************************************************************************************/
require_once('DefaultValidator.class.php');
require_once('ValidatorInterface.php');


/******************************************************
 * $__EZV_XXXXX will be defined as internal
 * static consts - a registered part of the EZValidator
 * system.
 * All variables of this type defined
 * and used here will be enumerating a set of choices
 *
 * $__EZV_XXXXX is used to decrease the possibility of
 * these variables getting mixed up with variables
 * defined elsewhere in the same namespace, such as
 * from downloaded code or php builtins.
 ******************************************************/


/******************************************************
 * define the RegExpValidator primitives
 * These expressions assume all whitespace has been removed from the string
 ******************************************************/
define ("__EZV_LESS_THAN_REGEXP","/^<(\+|\-){0,1}[[:digit:]]+(\.){0,1}[[:digit:]]*$/"); // <30
define ("__EZV_GREATER_THAN_REGEXP","/^>(\+|\-){0,1}[[:digit:]]+(\.){0,1}[[:digit:]]*$/"); // >30
define ("__EZV_EQUAL_TO_REGEXP","/^=(\+|\-){0,1}[[:digit:]]+(\.){0,1}[[:digit:]]*$/"); // =30
define ("__EZV_LESS_THAN_OR_EQUAL_TO_REGEXP","/^<=(\+|\-){0,1}[[:digit:]]+(\.){0,1}[[:digit:]]*$/"); // <=30
define ("__EZV_GREATER_THAN_OR_EQUAL_TO_REGEXP","/^>=(\+|\-){0,1}[[:digit:]]+(\.){0,1}[[:digit:]]*$/"); // >=30
define ("__EZV_NOT_EQUAL_TO_REGEXP","/^!=(\+|\-){0,1}[[:digit:]]+(\.){0,1}[[:digit:]]*$/"); // !=30

class CompExpValidator extends DefaultValidator implements ValidatorInterface {

  public $success = false;

  public $errorMessage = '';

  public $dataCanBeNull = false;

  public $dataCanBeEmpty = false;


/***************************************************************************************
  * FUNCTION          : testData
  * AUTHOR            : barkley@mitre.org
  * LAST UPDATED      : 29 May 2008
  * PARAMS            :
  *   $data           : string,
  *   $criteria       : string, expected to be a valid comparative expression
  *		                as defined above.  Whitespace is allowed.
  *
  * DESCRIPTION       : This function tests a piece of data against a specific comparative
  *			expression.
  *
  * RETURNS           : Returns true if it matches, false if it does not.  It also sets
  *			$this->errorMessage on failure.
  *
  * EXCEPTIONS        : None
  *
  ***************************************************************************************/
  public function testData($data, $criteria) {
    //replace whitespace
    $criteria = str_replace(array("\n", "\r", "\t", " ", "\o", "\xOB"), '', $criteria);

    //test against allowable numeric expressions
    if (preg_match(__EZV_LESS_THAN_REGEXP, $criteria)) {
      list($expr, $condition) = split("<", $criteria);
      if (!is_numeric($condition)) {
              $this->errorMessage = "Invalid criteria";
              return false;
      }
      if ($data < $condition) {
        $this->success = true;
        return true;
      } else {
        $this->success = false;
        return false;
      }
    } else if (preg_match(__EZV_GREATER_THAN_REGEXP, $criteria)) {
      list($expr, $condition) = split(">", $criteria);
      if (!is_numeric($condition)) {
              $this->errorMessage = "Invalid criteria";
              return false;
      }
      if ($data > $condition) {
        $this->success = true;
        return true;
      } else {
        $this->success = false;
        return false;
      }
    } else if (preg_match(__EZV_LESS_THAN_OR_EQUAL_TO_REGEXP, $criteria)) {
      list($expr, $condition) = split("<=", $criteria);
      if (!is_numeric($condition)) {
              $this->errorMessage = "Invalid criteria";
              return false;
      }
      if ($data <= $condition) {
        $this->success = true;
        return true;
      } else {
        $this->success = false;
        return false;
      }
    } else if (preg_match(__EZV_GREATER_THAN_OR_EQUAL_TO_REGEXP, $criteria)) {
      list($expr, $condition) = split(">=", $criteria);
      if (!is_numeric($condition)) {
              $this->errorMessage = "Invalid criteria";
              return false;
      }
      if ($data >= $condition) {
        $this->success = true;
        return true;
      } else {
        $this->success = false;
        return false;
      }
    } else if (preg_match(__EZV_EQUAL_TO_REGEXP, $criteria)) {
      list($expr, $condition) = split("=", $criteria);
      if (!is_numeric($condition)) {
              $this->errorMessage = "Invalid criteria";
              return false;
      }
      if ($data == $condition) {
        $this->success = true;
        return true;
      } else {
        $this->success = false;
        return false;
      }
    } else if (preg_match(__EZV_NOT_EQUAL_TO_REGEXP, $criteria)) {
      list($expr, $condition) = split("!=", $criteria);
      if (!is_numeric($condition)) {
              $this->errorMessage = "Invalid criteria";
              return false;
      }
      if ($data == $condition) {
        $this->success = false;
        return false;
      } else {
        $this->success = true;
        return true;
      }
    } else {
      $this->errorMessage = "Invalid criteria";
      return false;
    }
  }

/***************************************************************************************
  * FUNCTION          : isValidCriteria
  * AUTHOR            : barkley@mitre.org
  * LAST UPDATED      : 29 May 2008
  * PARAMS            :
  *   $criteria       : string, expected to be a valid comparative expression
  *		                            as defined above
  *
  * DESCRIPTION       : This function tests a "criteria" to see if it is a valid
  *			comparative expression.  If it is not, this validator class
  *			cannot be used to test this piece of data.  Whitespace is allowed.
  *
  * RETURNS           : Returns true if valid, false if it does not.
  *
  * EXCEPTIONS        : None
  *
  ***************************************************************************************/
  public function isValidCriteria($criteria) {
    //replace whitespace
    $criteria = str_replace(array("\n", "\r", "\t", " ", "\o", "\xOB"), '', $criteria);

    //test against allowable numeric expressions
    if (preg_match(__EZV_LESS_THAN_REGEXP, $criteria)) {
      return true;
    } else if (preg_match(__EZV_GREATER_THAN_REGEXP, $criteria)) {
      return true;
    } else if (preg_match(__EZV_LESS_THAN_OR_EQUAL_TO_REGEXP, $criteria)) {
      return true;
    } else if (preg_match(__EZV_GREATER_THAN_OR_EQUAL_TO_REGEXP, $criteria)) {
      return true;
    } else if (preg_match(__EZV_EQUAL_TO_REGEXP, $criteria)) {
      return true;
    } else if (preg_match(__EZV_NOT_EQUAL_TO_REGEXP, $criteria)) {
      return true;
    } else {
      $this->errorMessage = "Invalid criteria";
      return false;
    }
 }

 public function getDojoFragment($criteria) {
    //replace whitespace
    $criteria = str_replace(array("\n", "\r", "\t", " ", "\o", "\xOB"), '', $criteria);

    //test against allowable numeric expressions
    if (preg_match(__EZV_LESS_THAN_REGEXP, $criteria)) {
      list($expr, $condition) = split("<", $criteria);
      if (!is_numeric($condition)) {
              $this->errorMessage = "Invalid criteria";
              return false;
      } else {
        return "max=$condition";
      }
    } else if (preg_match(__EZV_GREATER_THAN_REGEXP, $criteria)) {
      list($expr, $condition) = split(">", $criteria);
      if (!is_numeric($condition)) {
              $this->errorMessage = "Invalid criteria";
              return false;
      } else {
        return "min=$condition";
      }
    } else if (preg_match(__EZV_LESS_THAN_OR_EQUAL_TO_REGEXP, $criteria)) {
      list($expr, $condition) = split("<=", $criteria);
      if (!is_numeric($condition)) {
              $this->errorMessage = "Invalid criteria";
              return false;
      } else {
        return "max=".($condition + 1);
      }
    } else if (preg_match(__EZV_GREATER_THAN_OR_EQUAL_TO_REGEXP, $criteria)) {
      list($expr, $condition) = split(">=", $criteria);
      if (!is_numeric($condition)) {
              $this->errorMessage = "Invalid criteria";
              return false;
      } else {
        return "min=".($condition + 1);
      }
    } else if (preg_match(__EZV_EQUAL_TO_REGEXP, $criteria)) {
      list($expr, $condition) = split("=", $criteria);
      if (!is_numeric($condition)) {
              $this->errorMessage = "Invalid criteria";
              return false;
      } else {
        return "regexp=$condition";
      }
    } else if (preg_match(__EZV_NOT_EQUAL_TO_REGEXP, $criteria)) {
      list($expr, $condition) = split("!=", $criteria);
      if (!is_numeric($condition)) {
              $this->errorMessage = "Invalid criteria";
              return false;
      } else {
        return "regexp=!$condition";
      }
    } else {
      $this->errorMessage = "Invalid criteria";
      return false;
    }
 }

  public function getDojoConstraint($criteria) {
    //replace whitespace
    $criteria = str_replace(array("\n", "\r", "\t", " ", "\o", "\xOB"), '', $criteria);

    //test against allowable numeric expressions
    if (preg_match(__EZV_LESS_THAN_REGEXP, $criteria)) {
      list($expr, $condition) = split("<", $criteria);
      return "max:" . ($condition-1);
    } else if (preg_match(__EZV_GREATER_THAN_REGEXP, $criteria)) {
      list($expr, $condition) = split(">", $criteria);
      return "min:" . ($condition+1);
    } else if (preg_match(__EZV_LESS_THAN_OR_EQUAL_TO_REGEXP, $criteria)) {
      list($expr, $condition) = split("<=", $criteria);
      return "max:$condition";
    } else if (preg_match(__EZV_GREATER_THAN_OR_EQUAL_TO_REGEXP, $criteria)) {
      list($expr, $condition) = split(">=", $criteria);
      return "min:$condition";
    } else if (preg_match(__EZV_EQUAL_TO_REGEXP, $criteria)) {
      list($expr, $condition) = split("=", $criteria);
      return "min:$condition,max:$condition";
    } else if (preg_match(__EZV_NOT_EQUAL_TO_REGEXP, $criteria)) {
      $this->errorMessage = "Can't handle this criteria";
      return false;
    } else {
      $this->errorMessage = "Invalid criteria";
      return false;
    }
  }


}

?>
