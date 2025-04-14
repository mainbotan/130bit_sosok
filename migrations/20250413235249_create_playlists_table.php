<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePlaylistsTable extends AbstractMigration
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
        $table = $this->table('playlists', [
            'id' => false,
            'primary_key' => ['id'],
            'schema' => 'public'
        ]);

        $table
            ->addColumn('id', 'string', ['limit' => 64])
            ->addColumn('uri', 'string', ['limit' => 128])
            ->addColumn('name', 'string', ['limit' => 128])
            ->addColumn('description', 'string', ['limit' => 512, 'null' => true])

            ->addColumn('collaborative', 'boolean', ['default' => false])
            ->addColumn('owner', 'json', ['default' => json_encode([])])
            ->addColumn('owner_id', 'string', ['limit' => 64])
            ->addColumn('owner_name', 'string', ['limit' => 128])
            
            ->addColumn('images', 'json', ['default' => json_encode([])])
            ->addColumn('tracks', 'json', ['default' => json_encode([])])

            ->addColumn('followers', 'integer', ['default' => 0])
            ->addColumn('genres', 'json', ['null' => true])
            ->addColumn('meta', 'json', ['default' => json_encode([])])
            ->addColumn('popularity', 'integer', ['default' => 30])

            ->addColumn('snapshot_id', 'string', ['limit' => 128, 'null' => true])

            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])

            ->addIndex(['id'], ['unique' => true])
            ->addIndex(['uri'], ['unique' => true])
            ->addIndex(['name'], ['name' => 'idx_playlists_name'])

            ->create();
    }
}
