<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAlbumsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('albums');
        $table
            ->addColumn('id', 'string', ['limit' => 64])
            ->addColumn('name', 'string', ['limit' => 64])
            ->addColumn('uri', 'string', ['limit' => 128])
            ->addColumn('artists', 'json')
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
