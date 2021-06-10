<?php declare(strict_types=1);

namespace Plugin\t4it_category_image_generation\Migrations;

use JTL\Plugin\Migration;
use JTL\Update\IMigration;

/**
 * Class Migration20210610000000
 * @package Plugin\t4it_category_image_generation\Migrations
 */
class Migration20210610000000 extends Migration implements IMigration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute(
            'CREATE TABLE IF NOT EXISTS `xplugin_t4it_category_image_generation_settings` (    
                `id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `cKey` VARCHAR(255) NOT NULL UNIQUE,
                `cValue` TEXT,
                `dLetzteAktualisierung` DATETIME DEFAULT NOW() ON UPDATE NOW()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        if ($this->doDeleteData()) {
            $this->execute('DROP TABLE IF EXISTS `xplugin_t4it_category_image_generation_settings`');
        }
    }
}
