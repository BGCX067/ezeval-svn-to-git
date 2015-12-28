<?php
require_once('ValidatorInterface.php');

class DefaultValidator {

  public $success = false;

  public $errorMessage = '';

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


  public function validate($data, $criteria) {
    //test for null criteria
    if (is_null($criteria)) {
      throw new Exception("Invalid criteria: $criteria");
      $this->success = false;
      return false;
    }

    //test for empty criteria
    if (''==$criteria) {
      throw new Exception("Invalid criteria: $criteria");
      $this->success = false;
      return false;
    }

    //test for invalid criteria
    if (!$this->isValidCriteria($criteria)) {
      throw new Exception("Invalid criteria: $criteria");
      $this->success = false;
      return $this->success;
    }

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



    //if we made it here, we can actually test the validation
    $this->success = $this->testData($data, $criteria);

    return $this->success;
  }

  public function testData($data, $criteria) {
  }

  public function isValidCriteria($criteria) {
    return true;
 }

  public function getDojoFragment() {
    return '';
  }


}

?>
