<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\CollationComponent;
use App\Controller\Component\NumberleComponent;
use App\Controller\Component\NumberleConfigComponent;
use \AccessException;

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
    private $seed;

    public function initialize(): void
    {
        $this->seed = (int)$this->request->getData('seed');
        if (
            Router::url() !== '/numberleApi/numberleConfig' &&
            (empty($this->request->getData('checkDigit')) ||
                (int)$this->request->getData('checkDigit') !== 1234509876 * $this->seed)
        )
            throw new AccessException('You cannot connect.');

        parent::initialize();
        $this->Numberle = new NumberleComponent(new ComponentRegistry());
        $this->Collation = new CollationComponent(new ComponentRegistry(), [
            'proposedSolution' => $this->request->getData('proposedSolution')
        ]);
        $this->NumberleConfig = new NumberleConfigComponent(new ComponentRegistry());
        $this->viewBuilder()->setClassName('Json');
    }

    public function validateSeed(): void
    {
        $this->Numberle->validateSeed($this->seed);
        $this->set('seedValid', true);
        $this->set('_serialize', ['seedValid']);
    }

    public function collation(): void
    {
        $this->set('collation', $this->Collation->statusOfProposedSolution($this->Numberle->getAnswer($this->seed)));
        $this->set('_serialize', ['collation']);
    }

    public function answer(): void
    {
        $this->set('answer', $this->Numberle->getAnswer($this->seed));
        $this->set('_serialize', ['answer']);
    }

    public function numberleConfig(): void
    {
        $this->set('numberleConfig', [
            'maxNumberOfTries' => $this->NumberleConfig->getMaxNumberOfTries(),
            'maxNumberOfInput' => $this->NumberleConfig->getMaxNumberOfInput(),
        ]);
        $this->set('_serialize', ['numberleConfig']);
    }
}
