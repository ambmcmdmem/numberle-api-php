<?php

declare(strict_types=1);

final class Validation
{
  /**
   * @var callable $validation
   */
  private $validation;
  private ?Exception $exception;

  final public function __construct(callable $validation, ?Exception $exception)
  {
    $this->validation = $validation;
    $this->exception = $exception;
  }

  final public function getValidation(): callable
  {
    return $this->validation;
  }

  final public function throwIfInvalid(): void
  {
    if ($this->exception)
      throw $this->exception;
  }
}
