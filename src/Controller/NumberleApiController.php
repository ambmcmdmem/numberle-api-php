<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Routing\Router;

/**
 * NumberleApi Controller
 *
 * @method \App\Model\Entity\NumberleApi[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NumberleApiController extends AppController
{
    public function initialize(): void
    {
        if (
            Router::url() !== '/numberleApi/numberleConfig' &&
            (empty($this->request->getData('checkDigit')) ||
                (int)$this->request->getData('checkDigit') !==
                1234509876 * (int)$this->request->getData('seed'))
        )
            throw new \Exception('You cannot connect.');
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->viewBuilder()->setClassName('Json');
    }

    public function validateSeed(): void
    {
        $seed = $this->request->getData('seed');
        if ((int)$seed <= 0 || 1000 < (int)$seed || !preg_match('/^[0-9]+$/', $seed))
            throw new \Exception('シードが適当な値でありません。');

        $this->set('seedValid', true);
        $this->set('_serialize', ['seedValid']);
    }

    public function collation(): void
    {
        $this->loadComponent('Numberle', [
            'seed' => $this->request->getData('seed')
        ]);
        $this->loadComponent('Collation', [
            'answer' => $this->Numberle->getAnswer(),
            'proposedSolution' => $this->request->getData('proposedSolution')
        ]);
        $this->set('collation', $this->Collation->statusOfProposedSolution());
        $this->set('_serialize', ['collation']);
    }

    public function answer(): void
    {
        $this->loadComponent('Numberle', [
            'seed' => $this->request->getData('seed')
        ]);
        $this->set('answer', $this->Numberle->getAnswer());
        $this->set('_serialize', ['answer']);
    }

    public function numberleConfig(): void
    {
        $this->loadComponent('NumberleConfig');
        $this->set('numberleConfig', [
            'maxNumberOfTries' => $this->NumberleConfig->getMaxNumberOfTries(),
            'maxNumberOfInput' => $this->NumberleConfig->getMaxNumberOfInput(),
        ]);
        $this->set('_serialize', ['numberleConfig']);
    }
}
