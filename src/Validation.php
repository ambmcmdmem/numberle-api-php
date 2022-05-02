<?php

declare(strict_types=1);

use \Exception;

class Validation
{
  private $validation;
  private $exception;

  public function __construct(callable $validation, Exception $exception)
  {
    $this->validation = $validation;
    $this->exception = $exception;
  }

  public static function validate(array $validationAndExceptions): void
  {
    $matchedValidationAndException = collection($validationAndExceptions)
      ->filter(function (Validation $validationAndException): bool {
        return !$validationAndException->getValidation()();
      })->first();
    if ($matchedValidationAndException)
      throw $matchedValidationAndException->throwException();
  }

  public function getValidation(): callable
  {
    return $this->validation;
  }

  public function throwException(): void
  {
    throw $this->exception;
  }
}
