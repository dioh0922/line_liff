<?php

use Phpmig\Migration\Migration;

class AddTodoId extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
        ALTER TABLE `travel_todo` ADD PRIMARY KEY (destination);
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
        ALTER TABLE travel_todo DROP PRIMARY KEY;
        ";
        $container = $this->getContainer();
        $container["db"]->query($sql);
    }
}
