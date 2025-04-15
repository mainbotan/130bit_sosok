<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateArtistsTable extends AbstractMigration
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
        $table = $this->table('artists', [
            'id' => false,
            'primary_key' => ['id'],
            'schema' => 'public'
        ]);
        $table 
            ->addColumn('id', 'string', ['limit' => 64])
            ->addColumn('uri', 'string', ['limit' => 128])
            ->addColumn('name', 'string', ['limit' => 128])
            ->addColumn('is_verified', 'boolean', ['default' => false])
            ->addColumn('is_tracking', 'boolean', ['default' => true])
            ->addColumn('followers', 'integer')
            ->addColumn('images', 'json')
            ->addColumn('genres', 'json', ['null' => true])
            ->addColumn('meta', 'json', ['default' => json_encode([])])
            ->addColumn('popularity', 'integer', ['default' => 30])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['id'], ['unique' => true])
            ->addIndex(['uri'], ['unique' => true])
            ->addIndex(['name'], ['name' => 'idx_artists_name']) // ❗ без GIN
            ->create();        
    }
}
