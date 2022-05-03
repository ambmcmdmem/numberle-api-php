<?php

declare(strict_types=1);

use \Exception;

class Validation
{
  private $validation;
  private $exception;

  function __construct(?callable $validation, ?Exception $exception)
  {
    $this->validation = $validation;
    $this->exception = $exception;
  }

  public function getValidation(): callable
  {
    return $this->validation;
  }

  public function throwIfInvalid(): void
  {
    if ($this->exception)
      throw $this->exception;
  }
}
