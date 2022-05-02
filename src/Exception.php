<?php

declare(strict_types=1);

class SeedException extends Exception
{
}
class CollationException extends Exception
{
}

function pattern(callable $validation, \Exception $exception): array
{
  return [
    'validation' => $validation,
    'exception' => $exception
  ];
}

function validate(array $validationAndExceptions): void
{
  $matchedValidationAndException = collection($validationAndExceptions)
    ->filter(function (array $validationAndException): bool {
      return !$validationAndException['validation']();
    })->first();
  if ($matchedValidationAndException)
    throw $matchedValidationAndException['exception'];
}
