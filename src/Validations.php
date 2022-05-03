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

use \CollationException;

class ProposedSolutionValidations
{
  private static $instance;
  private $validations;

  private function __construct()
  {
    $this->validations = (new Validations())->next(
      new Validation(
        function (array $props): bool {
          return (bool)$props['proposedSolution'];
        },
        new CollationException('解答案が空です。', 500)
      )
    )->next(
      new Validation(
        function (array $props): bool {
          return (bool)$props['answer'];
        },
        new CollationException('解答が空です。', 500)
      )
    )->next(
      new Validation(
        function (array $props): bool {
          return strlen($props['answer']) === strlen($props['proposedSolution']);
        },
        new CollationException('解答案の文字列長と解答の文字列長が異なります。', 500)
      )
    );
  }

  public static function getInstance(): ProposedSolutionValidations
  {
    if (!isset(self::$instance))
      self::$instance = new ProposedSolutionValidations();

    return self::$instance;
  }

  public function getValidations(): Validations
  {
    return $this->validations;
  }
}

use \SeedException;

class SeedValidations
{
  private static $instance;
  private $validations;

  private function __construct()
  {
    $this->validations = (new Validations())->next(
      new Validation(
        function (array $props): bool {
          return $props['seed'] > 0;
        },
        new SeedException('シードが0以下の値になっています。', 500)
      )
    )->next(
      new Validation(
        function (array $props): bool {
          return $props['seed'] <= 1000;
        },
        new SeedException('シードが1000より大きな値になっています。', 500)
      )
    );
  }

  public static function getInstance(): SeedValidations
  {
    if (!isset(self::$instance))
      self::$instance = new SeedValidations();

    return self::$instance;
  }

  public function getValidations(): Validations
  {
    return $this->validations;
  }
}
