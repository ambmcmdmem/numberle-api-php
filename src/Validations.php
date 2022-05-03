<?php

declare(strict_types=1);

final class Validations
{
  private array $validationAndExceptions;
  private Validation $defaultValidation;

  final public function __construct(array $validationAndExceptions = [])
  {
    $this->validationAndExceptions = $validationAndExceptions;
    $this->defaultValidation = new Validation(function (): bool {
      return true;
    }, null);
  }

  final public function next(Validation $validation): Validations
  {
    $this->validationAndExceptions[] = $validation;
    return $this;
  }

  final public function validate(array $props): void
  {
    (collection($this->validationAndExceptions)->filter(
      function (Validation $validationAndException) use ($props): bool {
        return !$validationAndException->getValidation()($props);
      }
    )->first() ?? $this->defaultValidation)
      ->throwIfInvalid();
  }
}
