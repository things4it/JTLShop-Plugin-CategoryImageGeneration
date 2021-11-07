<?php declare(strict_types=1);

namespace Plugin\t4it_category_image_generation\Migrations;

use JTL\Plugin\Migration;
use JTL\Update\IMigration;
use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetOneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetThreeProductImagesPlacementStrategy;

/**
 * Class Migration20211106000000
 * @package Plugin\t4it_category_image_generation\Migrations
 */
class Migration20211106000000 extends Migration implements IMigration
{
    private const SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE = '
            UPDATE tplugineinstellungen   
            SET cWert=:wert 
            WHERE cName=:name 
                AND (cWert IS NULL OR cWert = \'\')
        ';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
            'wert' => OffsetOneProductImagePlacementStrategy::getCode(),
            'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_ONE_IMAGE
        ]);

        $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
            'wert' => OffsetTwoProductImagesPlacementStrategy::getCode(),
            'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TWO_IMAGES
        ]);

        $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
            'wert' => OffsetThreeProductImagesPlacementStrategy::getCode(),
            'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TREE_IMAGES
        ]);
    }

    public function down()
    {
        // Nothing to do here
    }


}
