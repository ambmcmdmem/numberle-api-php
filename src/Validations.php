<?php

declare(strict_types=1);

use \Validation;

class Validations
{
  /**
   * @var array $validationAndExceptions
   */
  private $validationAndExceptions;
  private $defaultValidation;

  function __construct(array $validationAndExceptions = [])
  {
    $this->validationAndExceptions = $validationAndExceptions;
    $this->defaultValidation = new Validation(null, null);
  }

  public function next(Validation $validation): Validations
  {
    $this->validationAndExceptions[] = $validation;
    return $this;
  }

  public function validate(): void
  {
    (collection($this->validationAndExceptions)->filter(
      function (Validation $validationAndException): bool {
        return !$validationAndException->getValidation()();
      }
    )->first() ?? $this->defaultValidation)
      ->throwIfInvalid();
  }
}
