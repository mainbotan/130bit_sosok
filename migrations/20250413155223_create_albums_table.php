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
        $table = $this->table('albums', [
            'id' => false,
            'primary_key' => ['id'],
            'schema' => 'public'
        ]);
        $table 
            ->addColumn('id', 'string', ['limit' => 32])
            ->addColumn('uri', 'string', ['limit' => 128])
            ->addColumn('name', 'string', ['limit' => 128])
            ->addColumn('artists', 'json')
            ->addColumn('images', 'json')
            ->addColumn('upc', 'string', ['limit' => 32, 'null' => true])
            ->addColumn('release_date', 'date')
            ->addColumn('total_tracks', 'integer')
            ->addColumn('tracks', 'json')
            ->addColumn('genres', 'json', ['null' => true])
            ->addColumn('label', 'string', ['limit' => 128, 'null' => true])
            ->addColumn('meta', 'json', ['default' => '{}'])
            ->addColumn('popularity', 'integer', ['default' => 30])
            ->addColumn('created_at', 'date')
            ->addIndex(['id'], ['unique' => true])
            ->addIndex(['uri'], ['unique' => true])
            ->create();
    }
}
