<?php
if (! defined('SIMPLE_TEST')) {
  define('SIMPLE_TEST', './simpletest/');
}

require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once('EzeValHtmlReporter.class.php');
require_once('../EZValidator.class.php');

/*
 * testing incomplete
 */

class EZEValUnspecifiedDataTest extends UnitTestCase {

  function UnspecifiedDataTest() {
    $this->UnitTestCase("Invalid Data");
  }

  function testInvalidCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(true); //shouldn't matter
    try {
      $this->assertFalse($validator->addCriteria("invalid criteria"));
    } catch (Exception $e) {
      $this->pass(); 
    }
  }

  function testAllowEmptyWithCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(true);

    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertTrue($validator->getDataCanBeEmpty());
    $this->assertTrue($validator->validate(""));
  }


  function testNoAllowEmptyWithCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(false);

    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertFalse($validator->getDataCanBeEmpty());
    try {
      $this->assertFalse($validator->validate(""));
    } catch (Exception $e) {
      $this->pass();
    }
  }


  function testAllowNullWithCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeNull(true);

    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");
  
    $this->assertTrue($validator->getDataCanBeNull());  
    $this->assertTrue($validator->validate(null, __EZV_INTEGER_REGEXP));
  }

  function testNoAllowNullWithCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeNull(false);
  
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");
  
    $this->assertFalse($validator->getDataCanBeNull());  
    try { 
      $this->assertFalse($validator->validate(null, __EZV_INTEGER_REGEXP));
    } catch (Exception $e) {
      $this->pass();
    }
  }


/*

 MUST DO THIS ONCE FOR VALIDATOR WITH DEFINED CRITERIA AND ONCE FOR 
 VALIDATOR WITH NO DEFINED CRITERIA

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
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(true);

    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");
  
    $this->assertTrue($validator->validate("")); 
  }

  function testNoAllowEmptyDoAllowNullWithNullData() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(true);

    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");
   
    $this->assertTrue($validator->validate(null)); 
  }

  function testNoAllowEmptyNoAllowNullWithEmptyData() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(false);
   
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");
   
    try {  
      $this->assertFalse($validator->validate("")); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testNoAllowEmptyNoAllowNullWithNullData() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(false);
   
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    try {  
      $this->assertFalse($validator->validate(null)); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowEmptyNoAllowNullWithEmptyData() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(false);
   
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertTrue($validator->validate("")); 
  }

  function testAllowEmptyNoAllowNullWithNullData() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(false);
   
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    try {  
      $this->assertFalse($validator->validate(null)); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowNullNoAllowEmptyWithEmptyData() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(true);
   
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    try {  
      $this->assertFalse($validator->validate("")); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowNullNoAllowEmptyWithNullData() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(true);
   
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertTrue($validator->validate(null)); 
  }


  function testNoAllowEmptyDoAllowNullWithEmptyDataAndNoCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(true);

    $this->assertTrue($validator->validate("")); 
  }

  function testNoAllowEmptyDoAllowNullWithNullDataAndNoCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(true);

    $this->assertTrue($validator->validate(null)); 
  }

  function testNoAllowEmptyNoAllowNullWithEmptyDataAndNoCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(false);
   
    try {  
      $this->assertFalse($validator->validate("")); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testNoAllowEmptyNoAllowNullWithNullDataAndNoCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(false);
   
    try {  
      $this->assertFalse($validator->validate(null)); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowEmptyNoAllowNullWithEmptyDataAndNoCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(false);
   
    $this->assertTrue($validator->validate("")); 
  }

  function testAllowEmptyNoAllowNullWithNullDataAndNoCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(true);
    $validator->setDataCanBeNull(false);
   
    try {  
      $this->assertFalse($validator->validate(null)); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowNullNoAllowEmptyWithEmptyDataAndNoCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(true);
   
    try {  
      $this->assertFalse($validator->validate("")); 
    } catch (Exception $e) {
      $this->pass();
    }
  }

  function testAllowNullNoAllowEmptyWithNullDataAndNoCriteria() {
    $validator = new EZValidator();
    $validator->setDataCanBeEmpty(false);
    $validator->setDataCanBeNull(true);
   
    $this->assertTrue($validator->validate(null)); 
  }
}


class IntegerWithMinAndMaxTest extends UnitTestCase {
  function IntegerWithMinAndMaxTest() {
    $this->UnitTestCase("Tests Composite Validation on integer with a min and a max");
  }

  function testIntegerWithinMinAndMax() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_INTEGER_REGEXP);
    $validator->addCriteria("<30");
    $validator->addCriteria(">8");

    $this->assertTrue($validator->validate("15"));
    $this->assertTrue($validator->validate("9"));
  }

  function testNonIntegerWithinMinAndMax() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_INTEGER_REGEXP);
    $validator->addCriteria("<30");
    $validator->addCriteria(">8");

    $this->assertFalse($validator->validate("15i"));
  }

  function testIntegerBelowMinimum() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_INTEGER_REGEXP);
    $validator->addCriteria("<30");
    $validator->addCriteria(">8");

    $this->assertFalse($validator->validate("8"));
  }

  function testIntegerAboveMaximum() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_INTEGER_REGEXP);
    $validator->addCriteria("<30");
    $validator->addCriteria(">8");

    $this->assertFalse($validator->validate("30"));
  }

  function testFloatingPointWithinMinAndMax() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_INTEGER_REGEXP);
    $validator->addCriteria("<30");
    $validator->addCriteria(">8");

    $this->assertFalse($validator->validate("15.25"));
  }

}

class FloatWithMinAndMaxTest extends UnitTestCase {
  function FloatWithMinAndMaxTest() {
    $this->UnitTestCase("Tests Composite Validation on float with a min and a max");
  }

  function testFloatWithMinAndMax() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertTrue($validator->validate("3.14"));
  }

  function testFloatBarelyWithinMinAndMax() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertTrue($validator->validate("1500.5031"));
  }


  function testIntegerWithPointZeroZeroWithinBounds() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertTrue($validator->validate("1500.00"));
  }

  function testNegativeFloatBarelyWithinBounds() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertTrue($validator->validate("-25.299999"));
  }

  function testNegativeIntegerWithPointZeroWithinBounds() {  
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertTrue($validator->validate("-25.0")); 
  }

  function testZeroWithinBounds() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertTrue($validator->validate("0"));
  }

  function testNegativeZeroWithinBounds() {
    //hey, it's valid if you consider 2's complement.... 
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");
 
    $this->assertTrue($validator->validate("-0"));
  }

  function testNegativeIntegerWithNoDecimalsWithinBounds() { 
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertTrue($validator->validate("-15"));
  }


  function testIntegerOutOfBounds() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertFalse($validator->validate("50000"));
  }

  function testFloatWithDecimalPointButNoTrailingDigits() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertFalse($validator->validate("25."));
  }

  function testFloatWithTrailingFCharacter() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertFalse($validator->validate("25.2f"));
  }

  function testNegativeIntegerBelowMin() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertFalse($validator->validate("-27"));
  }

  function testNegativeFloatBelowMin() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertFalse($validator->validate("-25.3"));
  }

  function testNegativeFloatBelowMinWithTrailingZero() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertFalse($validator->validate("-25.30"));
  }

  function testNegativeFloatBarelyBelowMin() {
    $validator = new EZValidator();
    $validator->addCriteria(__EZV_FLOAT_REGEXP);
    $validator->addCriteria("<1500.5032");
    $validator->addCriteria(">-25.30");

    $this->assertFalse($validator->validate("-25.30359"));
  }
}


function EZEVal_run_tests() {
  //$reporter = new DefaultReporter();
  //$reporter = new HTMLReporter();
  $reporter = new EzeValHtmlReporter();

  $test = &new EZEValUnspecifiedDataTest();
  $test->run($reporter);

  $test = &new IntegerWithMinAndMaxTest();
  $test->run($reporter);

  $test = &new FloatWithMinAndMaxTest();
  $test->run($reporter);
}

?>
