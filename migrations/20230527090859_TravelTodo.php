<?php

use Phpmig\Migration\Migration;

class TravelTodo extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
        CREATE TABLE travel_todo(
            `destination` VARCHAR(30),
            `created_date` datetime,
            `done_date` datetime,
            `is_done` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
            `is_deleted` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0
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
        DROP TABLE travel_todo
        ";
        $container = $this->getContainer();
        $container["db"]->query($sql);
    }
}
