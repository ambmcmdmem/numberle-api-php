<?php

declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\NumberleConfigApiController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\NumberleConfigApiController Test Case
 *
 * @uses \App\Controller\NumberleConfigApiController
 */
class NumberleConfigApiControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [];

    public function testAccessNumberleConfigWithoutData(): void
    {
        $this->post('/numberleConfigApi');
        $this->assertResponseOk('numberleConfigにアクセスできません。');
    }
}
