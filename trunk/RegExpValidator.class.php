<?php
/* **************************************************************************************
  * FILE            : RegExpValidator.class.php
  * CODE            : class RegExpValidator
  * AUTHOR          : barkley@mitre.org
  * LAST UPDATED    : 29 May 2008
  *
  * DESCRIPTION     : This piece of code is used to validate data against
  *		      regular expressions.  Any regexp can be passed, or several
  *		      defaults are included using define.  This class can be used
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
 * define the RegExpValidator primitives
 ******************************************************/

//22 June 2008, barkley@mitre.org
//had to change many regexp because f***ing dojo won't support posix-style

// boolean can be either 1/0 values or true/false keywords
define ("__EZV_BOOLEAN_REGEXP","/^(0|1|true|false){1}$/i");

// integer is an integer - whole number positive or negative
//define ("__EZV_INTEGER_REGEXP","/^(\+|\-){0,1}[[:digit:]]*$/");
define("__EZV_INTEGER_REGEXP", "/^(\+|\-){0,1}[0-9]*$/");

// whole number or decimal number (contains abscissa)
//define ("__EZV_FLOAT_REGEXP","/^(\+|\-){0,1}[[:digit:]]*(\.){0,1}[[:digit:]]*$/");
define ("__EZV_FLOAT_REGEXP","/^(\+|\-){0,1}[0-9]*(\.){0,1}[0-9]*$/");

// string of alphabetic (only) characters
//define ("__EZV_ALPHABETIC_REGEXP","/^[[:alpha:]]*$/");
define ("__EZV_ALPHABETIC_REGEXP","/^[a-zA-Z]*$/");

// string which can contain alphabetic or numeric characters
//define ("__EZV_ALPHA_NUMERIC_REGEXP","/^[[:alnum:]]*$/");
define ("__EZV_ALPHA_NUMERIC_REGEXP","/^[a-zA-Z0-9]*$/");

// a valid email address
//define ("__EZV_EMAIL_REGEXP","^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,6}$");
define ("__EZV_EMAIL_REGEXP","^[a-zA-Z0-9][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,6}$");

// only characters which can be typed, e.g. abc &%* 093
define ("__EZV_ASCII_TYPEABLE_REGEXP", "/^([[:print:]]|\n|\r|\r\n|\[|\]|\t)*$/");

// a valid-formed ip address (does not consider "invalid" by way of private addresses, etc.)
//define ("__EZV_IP_REGEXP","/^[[:digit:]]{1,3}(\.){1}[[:digit:]]{1,3}(\.){1}[[:digit:]]{1,3}(\.){1}[[:digit:]]{1,3}$/");
define ("__EZV_IP_REGEXP","/^[0-9]{1,3}(\.){1}[0-9]{1,3}(\.){1}[0-9]{1,3}(\.){1}[0-9]{1,3}$/");



class RegExpValidator extends DefaultValidator implements ValidatorInterface {

/***************************************************************************************
  * FUNCTION          : testData
  * AUTHOR            : barkley@mitre.org
  * LAST UPDATED      : 29 May 2008
  * PARAMS            :
  *   $data           : string
  *   $criteria       : string, expected to be a valid regular expression
  *		                as defined by preg_match
  *
  * DESCRIPTION       : This function tests a piece of data against a specific regular
  *			expression.
  *
  * RETURNS           : Returns true if it matches, false if it does not.
  *
  * EXCEPTIONS        : None
  *
  ***************************************************************************************/
  public function testData($data, $criteria) {

    if (@preg_match($criteria, $data)) {
      $this->success = true;
      return true;
    } else {
      $this->success = false;
      return false;
    }
  }

/***************************************************************************************
  * FUNCTION          : isValidCriteria
  * AUTHOR            : barkley@mitre.org
  * LAST UPDATED      : 29 May 2008
  * PARAMS            :
  *   $criteria       : string, expected to be a valid regular expression
  *		                            as defined by preg_match
  *
  * DESCRIPTION       : This function tests a "criteria" to see if it is a valid
  *			regular expression.  If it is not, this validator class
  *			cannot be used to test this piece of data.
  *
  * RETURNS           : Returns true if valid, false if it does not.
  *
  * EXCEPTIONS        : None
  *
  ***************************************************************************************/
  public function isValidCriteria($criteria) {
      if (sprintf("%s",@preg_match($criteria,'')) == '') {
        $this->errorMessage = error_get_last();
        //throw new Exception(substr($error['message'],70));
        return false;
      } else {
        return true;
    }
    return true;
  }

  public function getDojoFragment($criteria) {
    if (!$this->isValidCriteria($criteria)) {
      return "regexp='$criteria'";
    }
  }


}
?>
<?php
/*********************

 GOT TO FIND A WAY TO USE THIS FOR EMAIL VALIDATION, IT IS QUITE GOOD

***************************/

  /*************************************************************************
   *  isValidEmail routine v.1.00
   *  Language: PHP
   *  Author:   Vadim Bodrov (falkoris@hotmail.com)
   *  Date:     1 April 2002
   *
   *  Description:
   *    boolean isValidEmail(string address_to_check, [boolean checkMX]);
   *
   *  isValidEmail is an express email address validation routine
   *  it checks if the given email address is _looks_ like a valid one.
   *  Also it searches DNS for MX records corresponding to the hostname
   *  extracted from the address (note the checkMX flag).
   *************************************************************************/
/*
  function isValidEmail($address, $checkMX = false)
  {
    $valid_tlds = array("arpa", "biz", "com", "edu", "gov", "int", "mil", "net", "org",
      "ad", "ae", "af", "ag", "ai", "al", "am", "an", "ao", "aq", "ar", "as", "at", "au",
      "aw", "az", "ba", "bb", "bd", "be", "bf", "bg", "bh", "bi", "bj", "bm", "bn", "bo",
      "br", "bs", "bt", "bv", "bw", "by", "bz", "ca", "cc", "cf", "cd", "cg", "ch", "ci",
      "ck", "cl", "cm", "cn", "co", "cr", "cs", "cu", "cv", "cx", "cy", "cz", "de", "dj",
      "dk", "dm", "do", "dz", "ec", "ee", "eg", "eh", "er", "es", "et", "fi", "fj", "fk",
      "fm", "fo", "fr", "fx", "ga", "gb", "gd", "ge", "gf", "gh", "gi", "gl", "gm", "gn",
      "gp", "gq", "gr", "gs", "gt", "gu", "gw", "gy", "hk", "hm", "hn", "hr", "ht", "hu",
      "id", "ie", "il", "in", "io", "iq", "ir", "is", "it", "jm", "jo", "jp", "ke", "kg",
      "kh", "ki", "km", "kn", "kp", "kr", "kw", "ky", "kz", "la", "lb", "lc", "li", "lk",
      "lr", "ls", "lt", "lu", "lv", "ly", "ma", "mc", "md", "mg", "mh", "mk", "ml", "mm",
      "mn", "mo", "mp", "mq", "mr", "ms", "mt", "mu", "mv", "mw", "mx", "my", "mz", "na",
      "nc", "ne", "nf", "ng", "ni", "nl", "no", "np", "nr", "nt", "nu", "nz", "om", "pa",
      "pe", "pf", "pg", "ph", "pk", "pl", "pm", "pn", "pr", "pt", "pw", "py", "qa", "re",
      "ro", "ru", "rw", "sa", "sb", "sc", "sd", "se", "sg", "sh", "si", "sj", "sk", "sl",
      "sm", "sn", "so", "sr", "st", "su", "sv", "sy", "sz", "tc", "td", "tf", "tg", "th",
      "tj", "tk", "tm", "tn", "to", "tp", "tr", "tt", "tv", "tw", "tz", "ua", "ug", "uk",
      "um", "us", "uy", "uz", "va", "vc", "ve", "vg", "vi", "vn", "vu", "wf", "ws", "ye",
      "yt", "yu", "za", "zm", "zr", "zw");

    // Rough email address validation using POSIX-style regular expressions
    if (!eregi("^[a-z0-9_]+@[a-z0-9-]{2,}.[a-z0-9-.]{2,}$", $address))
      return false;
    else
      $address = strtolower($address);

    // Explode the address on name and domain parts
    $name_domain = explode("@", $address);

    // There can be only one ;-) I mean... the "@" symbol
    if (count($name_domain) != 2)
      return false;

    // Check the domain parts
    $domain_parts = explode(".", $name_domain[1]);
    if (count($domain_parts) < 2)
      return false;

    // Check the TLD ($domain_parts[count($domain_parts) - 1])
    if (!in_array($domain_parts[count($domain_parts) - 1], $valid_tlds))
      return false;

    // Searche DNS for MX records corresponding to the hostname ($name_domain[0])
    if ($checkMX && !getmxrr($name_domain[0], $mxhosts))
      return false;

    return true;
  }
*/
?>
