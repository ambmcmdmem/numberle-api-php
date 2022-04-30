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

function validate(array $validityAndException): void
{
  $matchedKey = array_search(false, array_column($validityAndException, 'validity'));
  if ($matchedKey !== false)
    throw $validityAndException[$matchedKey]['exception'];
}
