<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddPrimaryArtistInAlbumsTable extends AbstractMigration
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
        $this->table('albums', ['schema' => 'public'])
            ->addColumn('primary_artist_id', 'string', ['limit' => 64])
            ->addColumn('primary_artist_name', 'string', ['limit' => 64])
            ->update();
    }
}
