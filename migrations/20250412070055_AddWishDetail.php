<?php

use Phpmig\Migration\Migration;

class AddWishDetail extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {

        $sql = "
        ALTER TABLE wish_list ADD COLUMN `wish_detail` TEXT;
        ";
        $container = $this->getContainer();
        $container["db"]->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = "
        ALTER TABLE wish_list DROP COLUMN `wish_detail`;
        ";
        $container = $this->getContainer();
        $container["db"]->query($sql);
    }
}
