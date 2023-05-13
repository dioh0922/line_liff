<?php

use Phpmig\Migration\Migration;

class Credit extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
        CREATE TABLE credit(
            `pay_value` INT(11) NOT NULL DEFAULT 0,
            `created_date` datetime,
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
        DROP TABLE credit
        ";
        $container = $this->getContainer();
        $container["db"]->query($sql);
    }
}
