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
  $matchedValidityAndException = collection($validityAndExceptions)
    ->firstMatch(
      [
        'validity' => false
      ]
    );
  if ($matchedValidityAndException)
    throw $matchedValidityAndException['exception'];
}
