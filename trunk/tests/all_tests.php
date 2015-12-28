<?php

  //simpletest base path
  if (! defined('SIMPLE_TEST')) {
    define('SIMPLE_TEST', './simpletest/');
  }

  //simpletest includes
  require_once(SIMPLE_TEST . 'unit_tester.php');
  require_once(SIMPLE_TEST . 'reporter.php');

  //overridden simple test class includes
  require_once('EzeValHtmlReporter.class.php');

  //validation library includes
  require_once('../RegExpValidator.class.php');
  require_once('../CompExpValidator.class.php');
  require_once('../DefaultValidator.class.php');
  require_once('../EZValidator.class.php');

  //unit test includes
  require_once('regexp_tests.php');
  require_once('compexp_tests.php');
  require_once('EZEValidator_tests.php');






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

  $test = &new CompexpUnspecifiedDataTest();
  $test->run($reporter);

  $test = &new ComparisonExpressionTest();
  $test->run($reporter);

  $test = &new EZEValUnspecifiedDataTest();
  $test->run($reporter);

  $test = &new IntegerWithMinAndMaxTest();
  $test->run($reporter);

  $test = &new FloatWithMinAndMaxTest();
  $test->run($reporter);
?>
