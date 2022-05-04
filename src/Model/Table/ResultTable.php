<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Controller\Component\NumberleConfigComponent;
use Cake\Controller\ComponentRegistry;

/**
 * Result Model
 *
 * @method \App\Model\Entity\Result newEmptyEntity()
 * @method \App\Model\Entity\Result newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Result[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Result get($primaryKey, $options = [])
 * @method \App\Model\Entity\Result findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Result patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Result[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Result|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Result saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Result[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Result[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Result[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Result[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ResultTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('result');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $numberleConfig = new NumberleConfigComponent(new ComponentRegistry());
        $validator
            ->integer('seed', 'シードが整数値でありません。')
            ->requirePresence('seed', 'create', 'シードのフィールドが存在しません。')
            ->notEmptyString('seed', 'シードが空です。')
            ->greaterThanOrEqual(
                'seed',
                $numberleConfig->getSeedLowerLimit(),
                'シードが0以下の値になっています。'
            )
            ->lessThanOrEqual(
                'seed',
                $numberleConfig->getSeedUpperLimit(),
                'シードが1000より大きい値になっています。'
            );

        $validator
            ->integer('numberOfTries', '挑戦回数が整数値でありません。')
            ->requirePresence('numberOfTries', 'create', '挑戦回数のフィールドが存在しません。')
            ->notEmptyString('numberOfTries', '挑戦回数が空です。')
            ->greaterThanOrEqual('numberOfTries', -1, '挑戦回数に-1未満の値が入っています。')
            ->lessThanOrEqual(
                'numberOfTries',
                $numberleConfig->getMaxNumberOfTries(),
                '挑戦回数が最大挑戦回数を上回っています。'
            );

        return $validator;
    }
}
