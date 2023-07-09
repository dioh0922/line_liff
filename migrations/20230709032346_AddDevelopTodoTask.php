<?php

use Phpmig\Migration\Migration;

class AddDevelopTodoTask extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
        CREATE TABLE dev_task_list(
            `todo_title` VARCHAR(200) NOT NULL PRIMARY KEY,
            `todo_detail` text,
            `created_date` datetime,
            `completed_date` datetime,
            `is_deleted` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
            `is_completed` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0
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
        DROP TABLE dev_task_list
        ";
        $container = $this->getContainer();
        $container["db"]->query($sql);
    }
}
