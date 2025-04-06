<?php

use Phpmig\Migration\Migration;

class CreateLiffWishList extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
        CREATE TABLE wish_list(
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `date` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `item_name` TEXT NOT NULL,
            `is_delete` BOOLEAN DEFAULT FALSE
        );
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
        DROP TABLE wish_list
        ";
        $container = $this->getContainer();
        $container["db"]->query($sql);
    }
}
