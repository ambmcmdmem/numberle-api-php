<?php

declare(strict_types=1);

use \Validation;

class Validations
{
  private array $validationAndExceptions;
  private Validation $defaultValidation;

  function __construct(array $validationAndExceptions = [])
  {
    $this->validationAndExceptions = $validationAndExceptions;
    $this->defaultValidation = new Validation(function (): bool {
      return true;
    }, null);
  }

  public function next(Validation $validation): Validations
  {
    $this->validationAndExceptions[] = $validation;
    return $this;
  }

  public function validate(array $props): void
  {
    (collection($this->validationAndExceptions)->filter(
      function (Validation $validationAndException) use ($props): bool {
        return !$validationAndException->getValidation()($props);
      }
    )->first() ?? $this->defaultValidation)
      ->throwIfInvalid();
  }
}
