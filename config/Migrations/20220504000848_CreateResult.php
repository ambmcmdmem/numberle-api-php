<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

final class CreateResult extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    final public function change()
    {
        $table = $this->table('result');
        $table->addColumn('seed', 'integer', [
            'default' => null,
            'limit' => 5,
            'null' => false,
        ]);
        $table->addColumn('numberOfTries', 'integer', [
            'default' => null,
            'limit' => 1,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
