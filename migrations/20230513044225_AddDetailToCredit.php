<?php

use Phpmig\Migration\Migration;

class AddDetailToCredit extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "
        ALTER TABLE `credit` ADD COLUMN `pay_detail` VARCHAR(30);
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
        ALTER TABLE `credit` DROP COLUMN `pay_detail`;
        ";
        $container = $this->getContainer();
        $container["db"]->query($sql);
    }
}
