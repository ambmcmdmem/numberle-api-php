<?php

declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\NumberleComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;
use \SeedException;

/**
 * App\Controller\Component\NumberleComponent Test Case
 */
class NumberleComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\NumberleComponent
     */
    protected $Numberle;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Numberle = new NumberleComponent($registry);
    }

    public function testBelowSeed(): void
    {
        $this->expectException(SeedException::class);
        $this->Numberle->validateSeed(0);
    }

    public function testExceedSeed(): void
    {
        $this->expectException(SeedException::class);
        $this->Numberle->validateSeed(1001);
    }

    public function testCorrectAnswer(): void
    {
        $this->assertEquals('16298', $this->Numberle->getAnswer(1));
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Numberle);

        parent::tearDown();
    }
}
