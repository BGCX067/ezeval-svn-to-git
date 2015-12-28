<?php
/* **************************************************************************************
  * FILE            : ValidatorInterface.php
  * CODE            : Interface ValidatorInterface
  * AUTHOR          : barkley@mitre.org
  * LAST UPDATED    : 29 May 2008
  *
  * DESCRIPTION     : This piece of code is just here so as to be explicit about 
  *		      how to create a new validator class.  Most classes will only 
  *		      need to override the two methods below, assuming they also 
  *		      inherit from DefaultValidator.
  *		      This was developed specifically for use with the EZValidator library. 
  *
  * USED BY         : EZValidator.class.php 
  *		      RegExpValidator.class.php
  *	              CompExpValidator.class.php
  *
  * LICENSE         :
  *
  *
  * DEPENDS ON      : None
  *
  *
  ***************************************************************************************/

interface ValidatorInterface {

  /* this function should test a piece of data to see if it is valid or not */
  public function testData($data, $criteria);

  /* this function should test a criteria to see if it is valid for a validator class */
  public function isValidCriteria($criteria);

  /* this function should return a partial dojo string representing the criteria */
  public function getDojoFragment($criteria);

}

?>
