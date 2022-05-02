<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\ComponentRegistry;
use App\Controller\Component\CollationComponent;
use App\Controller\Component\NumberleComponent;
use App\Controller\Component\NumberleConfigComponent;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Routing\Router;

/**
 * NumberleApi Controller
 *
 * @method \App\Model\Entity\NumberleApi[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NumberleApiController extends AppController
{
    /**
     * @var NumberleComponent $Numberle
     * @var CollationComponent $Collation
     * @var NumberleConfigComponent $NumberleConfig
     */
    private $Numberle;
    private $Collation;
    private $NumberleConfig;

    private function getSeed(): int
    {
        return (int)$this->getRequest()->getData('seed');
    }

    private function validateRequest(): void
    {
        if (
            empty($this->getRequest()->getData('checkDigit')) ||
            (int)$this->getRequest()->getData('checkDigit') !== $this->getSeed() * 1234509876
        )
            throw new BadRequestException('不正なリクエストです。');
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->Numberle = new NumberleComponent(new ComponentRegistry());
        $this->Collation = new CollationComponent(new ComponentRegistry());
        $this->NumberleConfig = new NumberleConfigComponent(new ComponentRegistry());
        $this->viewBuilder()->setClassName('Json');
    }

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        if (Router::url() !== '/numberleApi/numberleConfig') {
            $this->validateRequest();
            $this->Numberle->validateSeed($this->getSeed());
        }
    }

    public function validateSeed(): void
    {
        $this->set('seedValid', true);
        $this->viewBuilder()->setOption('serialize', ['seedValid']);
    }

    public function collation(): void
    {
        $this->set(
            'collation',
            $this->Collation->statusOfProposedSolution(
                $this->getRequest()->getData('proposedSolution'),
                $this->Numberle->getAnswer($this->getSeed())
            )
        );
        $this->viewBuilder()->setOption('serialize', ['collation']);
    }

    public function answer(): void
    {
        $this->set('answer', $this->Numberle->getAnswer($this->getSeed()));
        $this->viewBuilder()->setOption('serialize', ['answer']);
    }

    public function numberleConfig(): void
    {
        $this->set('numberleConfig', [
            'maxNumberOfTries' => $this->NumberleConfig->getMaxNumberOfTries(),
            'maxNumberOfInput' => $this->NumberleConfig->getMaxNumberOfInput(),
        ]);
        $this->viewBuilder()->setOption('serialize', ['numberleConfig']);
    }
}
