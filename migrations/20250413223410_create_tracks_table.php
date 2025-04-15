<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTracksTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('tracks', [
            'id' => false,
            'primary_key' => ['id'],
            'schema' => 'public'
        ]);

        $table
            ->addColumn('id', 'string', ['limit' => 64])
            ->addColumn('uri', 'string', ['limit' => 128])
            ->addColumn('name', 'string', ['limit' => 128])

            ->addColumn('artists', 'json', ['default' => json_encode([])])
            ->addColumn('album', 'json', ['default' => json_encode([])])

            ->addColumn('primary_artist_id', 'string', ['limit' => 64])
            ->addColumn('album_id', 'string', ['limit' => 64, 'null' => true])

            ->addColumn('explicit', 'boolean', ['default' => false])
            ->addColumn('is_local', 'boolean', ['default' => false])

            ->addColumn('disc_number', 'integer', ['null' => true])
            ->addColumn('track_number', 'integer', ['null' => true])
            ->addColumn('duration_ms', 'integer', ['null' => true])

            ->addColumn('genres', 'json', ['null' => true])
            ->addColumn('meta', 'json', ['default' => json_encode([])])
            ->addColumn('popularity', 'integer', ['default' => 30])

            ->addColumn('preview_url', 'string', ['limit' => 512, 'null' => true])
            ->addColumn('isrc', 'string', ['limit' => 64, 'null' => true])

            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])

            ->addIndex(['id'], ['unique' => true])
            ->addIndex(['uri'], ['unique' => true])
            ->addIndex(['name'], ['name' => 'idx_tracks_name'])

            ->create();
    }
}
