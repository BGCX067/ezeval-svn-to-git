<?php
if (! defined('SIMPLE_TEST')) {
  define('SIMPLE_TEST', './simpletest/');
}

require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once('EzeValHtmlReporter.class.php');
require_once('../RegExpValidator.class.php');
require_once('../DefaultValidator.class.php');


class RegexpUnspecifiedDataTest extends UnitTestCase {
  function UnspecifiedDataTest() {
    $this->UnitTestCase("Invalid Data Test Group");
  }

  function testInvalidCriteria() {
    $validator = new RegExpValidator();
    $validator->setDataCanBeEmpty(true); //shouldn't matter
    try {
      $this->assertFalse($validator->validate("", "invalid criteria"));
    } catch (Exception $e) {
      $this->pass(); 
    }
  }

  function testAllowEmptyWithCriteria() {
    $validator = new RegExpValidator();
    $validator->setDataCanBeEmpty(true);
    $this->assertTrue($validator->getDataCanBeEmpty());
    $this->assertTrue($validator->validate("", __EZV_INTEGER_REGEXP));
  }


  function testNoAllowEmptyWithCriteria() {
    $validator = new RegExpValidator();
    $validator->setDataCanBeEmpty(false);

    $this->assertFalse($validator->getDataCanBeEmpty());
    try {
      $this->assertFalse($validator->validate("", __EZV_INTEGER_REGEXP));
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowNullWithCriteria() {
    $validator = new RegExpValidator();
    $validator->setDataCanBeNull(true);
  
    $this->assertTrue($validator->getDataCanBeNull());  
    $this->assertTrue($validator->validate(null, __EZV_INTEGER_REGEXP));
  }

  function testNoAllowNullWithCriteria() {
    $validator = new RegExpValidator();
    $validator->setDataCanBeNull(false);
  
    $this->assertFalse($validator->getDataCanBeNull());  
    try { 
      $this->assertFalse($validator->validate(null, __EZV_INTEGER_REGEXP));
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
    $validator = new RegExpValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(true);
   
    $this->assertTrue($validator->validate("", __EZV_INTEGER_REGEXP)); 
  }

  function testNoAllowEmptyDoAllowNullWithNullData() {
    $validator = new RegExpValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(true);
   
    $this->assertTrue($validator->validate(null, __EZV_INTEGER_REGEXP)); 
  }

  function testNoAllowEmptyNoAllowNullWithEmptyData() {
    $validator = new RegExpValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(false);
   
    try {  
      $this->assertFalse($validator->validate("", __EZV_INTEGER_REGEXP)); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testNoAllowEmptyNoAllowNullWithNullData() {
    $validator = new RegExpValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(false);
   
    try {  
      $this->assertFalse($validator->validate(null, __EZV_INTEGER_REGEXP)); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowEmptyNoAllowNullWithEmptyData() {
    $validator = new RegExpValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(false);
   
    $this->assertTrue($validator->validate("", __EZV_INTEGER_REGEXP)); 
  }

  function testAllowEmptyNoAllowNullWithNullData() {
    $validator = new RegExpValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(false);
   
    try {  
      $this->assertFalse($validator->validate(null, __EZV_INTEGER_REGEXP)); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowNullNoAllowEmptyWithEmptyData() {
    $validator = new RegExpValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(true);
   
    try {  
      $this->assertFalse($validator->validate("", __EZV_INTEGER_REGEXP)); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowNullNoAllowEmptyWithNullData() {
    $validator = new RegExpValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(true);
   
    $this->assertTrue($validator->validate(null, __EZV_INTEGER_REGEXP)); 
  }

}

class IntegerTest extends UnitTestCase {
  function IntegerTest() { 
    $this->UnitTestCase("Integer Test Group"); 
  }

  function testNormal() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("15", __EZV_INTEGER_REGEXP));
  }

  function testPositive() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("+15", __EZV_INTEGER_REGEXP));
  }

  function testNegative() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("-15", __EZV_INTEGER_REGEXP));
  }

  function testZero() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("0", __EZV_INTEGER_REGEXP));
  }

  function testStringFail() {
    $validator = new RegExpValidator();
    $this->assertFalse($validator->validate("15i", __EZV_INTEGER_REGEXP));
  }

  function testFloatFail() {
    $validator = new RegExpValidator();
    $this->assertFalse($validator->validate("5.5", __EZV_INTEGER_REGEXP));
  }
}

class BooleanTest extends UnitTestCase {
  function BooleanTest() { $this->UnitTestCase(); }

  function testTrue() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("true", __EZV_BOOLEAN_REGEXP));
  }

  function testFalse() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("false", __EZV_BOOLEAN_REGEXP));
  }

  function testOne() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("1", __EZV_BOOLEAN_REGEXP));
  }

  function testZero() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("0", __EZV_BOOLEAN_REGEXP));
  }

  function testInteger() {
    $validator = new RegExpValidator();
    $this->assertFalse($validator->validate(15, __EZV_BOOLEAN_REGEXP));
  }

  function testString() {
    $validator = new RegExpValidator();
    $this->assertFalse($validator->validate("string", __EZV_BOOLEAN_REGEXP));
  }

  function testEmpty() {
    $validator = new RegExpValidator();

    try {
      $this->assertFalse($validator->validate("", __EZV_BOOLEAN_REGEXP));
    } catch (Exception $e) {
      $this->pass(); 
    }
  }
}

class FloatTest extends UnitTestCase {
  function FloatTest() { $this->UnitTestCase(); }

  function testNegative() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("-15.2", __EZV_FLOAT_REGEXP));
  }

  function testPositive() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("15.28", __EZV_FLOAT_REGEXP));
  }

  function testZero() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("0", __EZV_FLOAT_REGEXP));
  }


  function testManyDecimalPlaces() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("15.288395313", __EZV_FLOAT_REGEXP));
  }


  function testInteger() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("3", __EZV_FLOAT_REGEXP));
  }


  function testPointWithNoDecimal() {
    $validator = new RegExpValidator();
    $this->assertFalse($validator->validate("150.", __EZV_FLOAT_REGEXP));
  }


  function testString() {
    $validator = new RegExpValidator();
    $this->assertFalse($validator->validate("15.28f", __EZV_FLOAT_REGEXP));
  }

}


class AlphabeticTest extends UnitTestCase {
  function AlphabeticTest() { $this->UnitTestCase(); }

  function testAlpha() {
    $validator = new RegExpValidator();
    $this->assertFalse($validator->validate("this is a test string", __EZV_ALPHABETIC_REGEXP));
  }

  function testAlphaNumeric() {
    $validator = new RegExpValidator();
    $this->assertFalse($validator->validate("this is a test string with 1 or 2 numbers", __EZV_ALPHABETIC_REGEXP));
  }

  function testAscii() {
    $validator = new RegExpValidator();
    $this->assertFalse($validator->validate("this is a test string with punctuation. :)", __EZV_ALPHABETIC_REGEXP));
  }


  function testNumeric() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("358031", __EZV_ALPHA_NUMERIC_REGEXP));
  }
}


class EmailTest extends UnitTestCase {
  function EmailTest() { $this->UnitTestCase("Email test group"); }

  function testValidEmail() {
    $validator = new RegExpValidator();
    $this->assertTrue($validator->validate("jbarkley@mitre.org", __EZV_EMAIL_REGEXP));
  }


  function testTwoDomainsInvalid() {
    $validator = new RegExpValidator();
    $this->assertFalse($validator->validate("jbarkley@mitre.org.net", __EZV_EMAIL_REGEXP));
  }
}



function regexp_run_tests() {
  //$reporter = new DefaultReporter();
  //$reporter = new HTMLReporter();
  $reporter = new EzeValHtmlReporter();

  $test = &new RegexpUnspecifiedDataTest();
  $test->run($reporter);

  $test = &new IntegerTest();
  $test->run($reporter);

  $test = &new BooleanTest();
  $test->run($reporter);

  $test = &new FloatTest();
  $test->run($reporter);

  $test = &new AlphabeticTest();
  $test->run($reporter);

  $test = &new EmailTest();
  $test->run($reporter);

}


?>
