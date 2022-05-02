<?php

declare(strict_types=1);

class SeedException extends Exception
{
}
class CollationException extends Exception
{
}

function pattern(bool $validity, \Exception $exception): array
{
  return [
    'validity' => $validity,
    'exception' => $exception
  ];
}

function validate(array $validityAndExceptions): void
{
  $validityAndException = collection($validityAndExceptions)
    ->firstMatch(
      [
        'validity' => false
      ]
    );
  if ($validityAndException)
    throw $validityAndException['exception'];
}
