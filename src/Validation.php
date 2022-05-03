<?php

declare(strict_types=1);

use \Exception;

class Validation
{
  /**
   * @var array $validationAndExceptions
   */
  private $validationAndExceptions;

  function __construct(array $validationAndExceptions = [])
  {
    $this->validationAndExceptions = $validationAndExceptions;
  }

  public function next(callable $validation, Exception $exception): Validation
  {
    return new Validation(
      array_merge(
        $this->validationAndExceptions,
        [
          [
            'validation' => $validation,
            'exception' => $exception
          ]
        ]
      )
    );
  }

  public function validate(): void
  {
    (new Validation(
      collection($this->validationAndExceptions)->filter(
        function (array $validationAndException): bool {
          return !$validationAndException['validation']();
        }
      )->first() ?? []
    ))->throwIfInvalid();
  }

  private function throwIfInvalid(): void
  {
    if (!empty($this->validationAndExceptions['exception']))
      throw $this->validationAndExceptions['exception'];
  }
}
