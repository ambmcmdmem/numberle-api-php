<?php

declare(strict_types=1);

namespace App\Controller\Component\NumberleComponent\_Private;

use \Validations;
use \Validation;
use \SeedException;
use App\Controller\Component\NumberleConfigComponent;
use Cake\Controller\ComponentRegistry;

final class SeedValidations
{
    private static SeedValidations $instance;
    private Validations $validations;

    private function __construct()
    {
        $numberleConfig = new NumberleConfigComponent(new ComponentRegistry());
        $this->validations = (new Validations())->next(
            new Validation(
                function (array $props) use ($numberleConfig): bool {
                    return $props['seed'] >= $numberleConfig->getSeedLowerLimit();
                },
                new SeedException("シードが0以下の値になっています。", 500)
            )
        )->next(
            new Validation(
                function (array $props) use ($numberleConfig): bool {
                    return $props['seed'] <= $numberleConfig->getSeedUpperLimit();
                },
                new SeedException('シードが1000より大きな値になっています。', 500)
            )
        );
    }

    final public static function getValidations(): Validations
    {
        if (!isset(self::$instance))
            self::$instance = new SeedValidations();

        return self::$instance->validations;
    }
}


namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\NumberleConfigComponent;
use App\Controller\Component\NumberleComponent\_Private\SeedValidations;

/**
 * Numberle component
 */

final class NumberleComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

    private NumberleConfigComponent $numberleComponent;
    private \Validations $seedValidations;
    /**
     *
     * x, y, z, wはXorShiftアルゴリズム実行のためのパラメータ
     * https://www.jstatsoft.org/article/view/v008i14
     */
    private int $x;
    private int $y;
    private int $z;
    private int $w;

    final public function initialize(array $config): void
    {
        $this->numberleComponent = new NumberleConfigComponent(new ComponentRegistry());
        $this->seedValidations = SeedValidations::getValidations();
    }

    private function xorshift(): int
    {
        $tmp = $this->x ^ ($this->x << 11);
        $this->x = $this->y;
        $this->y = $this->z;
        $this->z = $this->w;
        return ($this->w = $this->w ^ ($this->w >> 19) ^ ($tmp ^ ($tmp >> 8)));
    }

    private function shuffleReversibly(array $target): array
    {
        return collection(range(count($target) - 1, 1))
            ->reduce(function (array $toBeShuffled, int $i): array {
                $j = floor(abs($this->xorshift())) % ($i + 1);
                [$toBeShuffled[$i], $toBeShuffled[$j]] = [$toBeShuffled[$j], $toBeShuffled[$i]];
                return $toBeShuffled;
            }, $target);
    }

    final public function validateSeed(int $seed): void
    {
        $this->seedValidations->validate([
            'seed' => $seed
        ]);
    }

    final public function getAnswer(int $seed): string
    {
        $this->x = 31415926535;
        $this->y = 8979323846;
        $this->z = 2643383279;
        $this->w = $seed;

        return implode(
            collection($this->shuffleReversibly(range(0, 9)))
                ->take($this->numberleComponent->getMaxNumberOfInput())
                ->toArray()
        );
    }
}
