<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ResultFixture
 */
class ResultFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'result';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'seed' => 1,
                'numberOfTries' => 1,
                'created' => '2022-05-04 00:25:42',
            ],
        ];
        parent::init();
    }
}
