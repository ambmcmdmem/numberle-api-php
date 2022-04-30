<?php

declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\CollationComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;
use \CollationException;

/**
 * App\Controller\Component\CollationComponent Test Case
 */
class CollationComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\CollationComponent
     */
    protected $Collation;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Collation = new CollationComponent($registry, [
            'proposedSolution' => '01234',
        ]);
        $this->failedCollation = new CollationComponent($registry, [
            'proposedSolution' => '012345',
        ]);
    }

    public function testCollation(): void
    {
        $this->assertEquals([
            'correct',
            'wrong',
            'differentLocation',
            'wrong',
            'differentLocation'
        ], $this->Collation->statusOfProposedSolution('02468'));
    }

    public function testConfigLengthIsNotCorrect(): void
    {
        $this->expectException(CollationException::class);
        $this->failedCollation->statusOfProposedSolution('02468');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Collation);

        parent::tearDown();
    }
}
