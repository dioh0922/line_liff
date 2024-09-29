<?php

use Phpmig\Migration\Migration;

class AddChatLog extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
        CREATE TABLE gemini_chat_log (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `question` TEXT NOT NULL,
            `response` TEXT NOT NULL,
            `registered_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
        DROP TABLE gemini_chat_log
        ";
        $container = $this->getContainer();
        $container["db"]->query($sql);
    }
}
