<?php
if (! defined('SIMPLE_TEST')) {
  define('SIMPLE_TEST', './simpletest/');
}

require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once('EzeValHtmlReporter.class.php');
require_once('../CompExpValidator.class.php');
require_once('../DefaultValidator.class.php');

class CompexpUnspecifiedDataTest extends UnitTestCase {
  function UnspecifiedDataTest() {
    $this->UnitTestCase("Invalid Data");
  }

  function testInvalidCriteria() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeEmpty(true); //shouldn't matter
    try {
      $this->assertFalse($validator->validate("", "invalid criteria"));
    } catch (Exception $e) {
      $this->pass(); 
    }
  }

  function testAllowEmptyWithCriteria() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeEmpty(true);
    $this->assertTrue($validator->getDataCanBeEmpty());
    $this->assertTrue($validator->validate("", "<30"));
  }


  function testNoAllowEmptyWithCriteria() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeEmpty(false);

    $this->assertFalse($validator->getDataCanBeEmpty());
    try {
      $this->assertFalse($validator->validate("", "<30"));
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowNullWithCriteria() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeNull(true);
  
    $this->assertTrue($validator->getDataCanBeNull());  
    $this->assertTrue($validator->validate(null, "<30"));
  }

  function testNoAllowNullWithCriteria() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeNull(false);
  
    $this->assertFalse($validator->getDataCanBeNull());  
    try { 
      $this->assertFalse($validator->validate(null, "<30"));
    } catch (Exception $e) {
      $this->pass();
    }
  }


/*

 +-----------------------------------------------------------------------------+
 |          |empty allowed|empty not allowed|empty allowed   |empty not allowed|
 |          |null allowed |null not allowed |null not allowed|null allowed     |
 +-----------------------------------------------------------------------------+
 |empty data|  true       | false           |  true          |   false         |
 |-----------------------------------------------------------------------------+
 |null data |  true       | false           |  false         |   true          |
 +-----------------------------------------------------------------------------+

*/

  function testNoAllowEmptyDoAllowNullWithEmptyData() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(true);
   
    $this->assertTrue($validator->validate("", "<30")); 
  }

  function testNoAllowEmptyDoAllowNullWithNullData() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(true);
   
    $this->assertTrue($validator->validate(null, "<30")); 
  }

  function testNoAllowEmptyNoAllowNullWithEmptyData() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(false);
   
    try {  
      $this->assertFalse($validator->validate("", "<30")); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testNoAllowEmptyNoAllowNullWithNullData() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(false);
   
    try {  
      $this->assertFalse($validator->validate(null, "<30")); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowEmptyNoAllowNullWithEmptyData() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(false);
   
    $this->assertTrue($validator->validate("", "<30")); 
  }

  function testAllowEmptyNoAllowNullWithNullData() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(false);
   
    try {  
      $this->assertFalse($validator->validate(null, "<30")); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowNullNoAllowEmptyWithEmptyData() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(true);
   
    try {  
      $this->assertFalse($validator->validate("", "<30")); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowNullNoAllowEmptyWithNullData() {
    $validator = new CompExpValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(true);
   
    $this->assertTrue($validator->validate(null, "<30")); 
  }

}


class ComparisonExpressionTest extends UnitTestCase {
  function LessThanTest() { 
    $this->UnitTestCase("Less Than Expression Tests"); 
  }

  function testLessThan() {
    $validator = new CompExpValidator();
    $this->assertTrue($validator->validate("15", "< 30"));
    $this->assertFalse($validator->validate("30", "< 30"));
  }

  function testGreaterThan() {
    $validator = new CompExpValidator();
    $this->assertFalse($validator->validate("15", "> 30"));
    $this->assertTrue($validator->validate("31", "> 30"));
  }

  function testEqualTo() {
    $validator = new CompExpValidator();
    $this->assertTrue($validator->validate("30", " = 30"));
    $this->assertFalse($validator->validate("15", "= 15.5"));
    $this->assertTrue($validator->validate("15", "= 15.0"));
  }

  function testNotEqualTo() {
    $validator = new CompExpValidator();
    $this->assertTrue($validator->validate("30", " ! = 31"));
    $this->assertFalse($validator->validate("15", "!= 15.0"));
    try {
      $this->assertFalse($validator->validate("15", "!= 15.0i"));
    } catch (Exception $e) {
      $this->pass(); 
    }
  }

}


function compexp_run_tests() {
  //$reporter = new DefaultReporter();
  //$reporter = new HTMLReporter();
  $reporter = new EzeValHtmlReporter();

  $test = &new CompexpUnspecifiedDataTest();
  $test->run($reporter);

  $test = &new ComparisonExpressionTest();
  $test->run($reporter);
}

?>
