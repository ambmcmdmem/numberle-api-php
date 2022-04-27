<?php

declare(strict_types=1);

namespace App\Controller;

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
            empty($this->request->getData('checkDigit')) ||
            (int)$this->request->getData('checkDigit') !==
            1234509876 * (int)$this->request->getData('seed')
        )
            throw new \Exception('You cannot connect.');

        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Numberle', [
            'seed' => $this->request->getData('seed')
        ]);
        $this->viewBuilder()->setClassName('Json');
    }

    public function collation()
    {
        $this->loadComponent('Collation', [
            'answer' => $this->Numberle->getAnswer()
        ]);
        $this->set('collation', $this->Collation->statusOfProposedSolution($this->request->getData('proposedSolution')));
        $this->set('_serialize', ['collation']);
    }

    public function answer()
    {
        $this->set('answer', $this->Numberle->getAnswer());
        $this->set('_serialize', ['answer']);
    }
}
