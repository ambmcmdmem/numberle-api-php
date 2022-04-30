<?php

declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\CollationComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;
use \CollationException;

use function App\Controller\Component\statusPattern;

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
        $this->Collation = new CollationComponent($registry);
    }

    public function testStatusIsInvalid(): void
    {
        $this->expectException(CollationException::class);
        $this->expectExceptionMessage('pattern関数の引数のステータスにおかしい値が入っています。');
        $this->expectExceptionCode(500);
        statusPattern(true, 'invalid Status');
    }

    public function testCollation(): void
    {
        $this->assertEquals([
            'correct',
            'wrong',
            'differentLocation',
            'wrong',
            'differentLocation'
        ], $this->Collation->statusOfProposedSolution('01234', '02468'));
    }

    public function testProposedSolutionIsEmpty(): void
    {
        $this->expectException(CollationException::class);
        $this->expectExceptionMessage('提案された回答が空です。');
        $this->expectExceptionCode(500);
        $this->Collation->statusOfProposedSolution('', '02468');
    }

    public function testAnswerIsEmpty(): void
    {
        $this->expectException(CollationException::class);
        $this->expectExceptionMessage('回答が空です。');
        $this->expectExceptionCode(500);
        $this->Collation->statusOfProposedSolution('01234', '');
    }

    public function testProposedSolutionLengthIsNotCorrect(): void
    {
        $this->expectException(CollationException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('提示された文字列長と回答の文字列長が異なります。');
        $this->Collation->statusOfProposedSolution('012345', '02468');
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
