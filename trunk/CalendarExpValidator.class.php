<?php
/* **************************************************************************************
  * FILE            : 
  * CODE            : 
  * AUTHOR          : barkley@mitre.org
  * LAST UPDATED    : 
  *
  * DESCRIPTION     : 
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
//define ("__EZV_LESS_THAN_REGEXP","/^<(\+|\-){0,1}[[:digit:]]+(\.){0,1}[[:digit:]]*$/"); // <30

class CalendarExpValidator extends DefaultValidator implements ValidatorInterface {

  public $success = false;

  public $errorMessage = '';

  public $dataCanBeNull = false;

  public $dataCanBeEmpty = false;


/***************************************************************************************
  * FUNCTION          : testData
  * AUTHOR            : barkley@mitre.org
  * LAST UPDATED      : 
  * PARAMS            :
  *   $data           : 
  *   $criteria       : 
  *
  * DESCRIPTION       : 
  *
  * RETURNS           : 
  *
  * EXCEPTIONS        : None
  *
  ***************************************************************************************/
  public function testData($data, $criteria) {
  }

/***************************************************************************************
  * FUNCTION          : isValidCriteria
  * AUTHOR            : barkley@mitre.org
  * LAST UPDATED      : 
  * PARAMS            :
  *   $criteria       : 
  *		       
  *
  * DESCRIPTION       : 
  *
  * RETURNS           : 
  *
  * EXCEPTIONS        : None
  *
  ***************************************************************************************/
  public function isValidCriteria($criteria) {
    //replace whitespace
    $criteria = str_replace(array("\n", "\r", "\t", " ", "\o", "\xOB"), '', $criteria);
 }

 public function getDojoFragment($criteria) {
   return "dojoType='datebox'";
 }

}
?>
