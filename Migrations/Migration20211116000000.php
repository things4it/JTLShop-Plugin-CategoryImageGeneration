<?php declare(strict_types=1);

namespace Plugin\t4it_category_image_generation\Migrations;

use JTL\Plugin\Migration;
use JTL\Update\IMigration;
use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetOneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio1to1\OffsetRatio1to1OneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio1to1\OffsetRatio1to1ThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio1to1\OffsetRatio1to1TwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio4to3\OffsetRatio4to3OneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio4to3\OffsetRatio4to3ThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio4to3\OffsetRatio4to3TwoProductImagesPlacementStrategy;

/**
 * Class Migration20211116000000
 * @package Plugin\t4it_category_image_generation\Migrations
 */
class Migration20211116000000 extends Migration implements IMigration
{
    private const SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE = '
            UPDATE tplugineinstellungen   
            SET cWert=:wert 
            WHERE cName=:name 
                AND cWert=:wertAlt 
        ';

    /**
     * @inheritdoc
     */
    public function up()
    {
        // move offset-ratio-placement strategies to the new splitted values for each ratio
        $ratioSettingRow = $this->getDB()->select('tplugineinstellungen', 'cName', 't4it_category_image_generation-categoryImageRatio');

        if($ratioSettingRow->cWert == '1:1'){
            // remove flipped-offset sample strategy -> migrate to normal 'offset'
            $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
                'wert' => OffsetRatio1to1OneProductImagePlacementStrategy::getCode(),
                'wertAlt' => Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "flipped-offset-one",
                'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_ONE_IMAGE
            ]);

            $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
                'wert' => OffsetRatio1to1TwoProductImagesPlacementStrategy::getCode(),
                'wertAlt' => Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "flipped-offset-two",
                'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TWO_IMAGES
            ]);

            $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
                'wert' => OffsetRatio1to1ThreeProductImagesPlacementStrategy::getCode(),
                'wertAlt' => Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "flipped-offset-three",
                'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TREE_IMAGES
            ]);

            // migrate if offset was selected
            $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
                'wert' => OffsetRatio1to1OneProductImagePlacementStrategy::getCode(),
                'wertAlt' => Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-one",
                'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_ONE_IMAGE
            ]);

            $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
                'wert' => OffsetRatio1to1TwoProductImagesPlacementStrategy::getCode(),
                'wertAlt' => Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-two",
                'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TWO_IMAGES
            ]);

            $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
                'wert' => OffsetRatio1to1ThreeProductImagesPlacementStrategy::getCode(),
                'wertAlt' => Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-three",
                'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TREE_IMAGES
            ]);
        } else {
            // remove flipped-offset sample strategy -> migrate to normal 'offset'
            $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
                'wert' => OffsetRatio4to3OneProductImagePlacementStrategy::getCode(),
                'wertAlt' => Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "flipped-offset-one",
                'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_ONE_IMAGE
            ]);

            $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
                'wert' => OffsetRatio4to3TwoProductImagesPlacementStrategy::getCode(),
                'wertAlt' => Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "flipped-offset-two",
                'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TWO_IMAGES
            ]);

            $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
                'wert' => OffsetRatio4to3ThreeProductImagesPlacementStrategy::getCode(),
                'wertAlt' => Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "flipped-offset-three",
                'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TREE_IMAGES
            ]);

            // migrate if offset was selected
            $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
                'wert' => OffsetRatio4to3OneProductImagePlacementStrategy::getCode(),
                'wertAlt' => Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-one",
                'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_ONE_IMAGE
            ]);

            $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
                'wert' => OffsetRatio4to3TwoProductImagesPlacementStrategy::getCode(),
                'wertAlt' => Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-two",
                'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TWO_IMAGES
            ]);

            $this->getDB()->executeQueryPrepared(self::SQL_FIX_INITIAL_PLUGIN_CONFIG_VALUE, [
                'wert' => OffsetRatio4to3ThreeProductImagesPlacementStrategy::getCode(),
                'wertAlt' => Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "offset-three",
                'name' => Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TREE_IMAGES
            ]);
        }

        // remove ratio-setting
        $this->getDB()->delete('tplugineinstellungen', 'cName', 't4it_category_image_generation-categoryImageRatio');
    }

    public function down()
    {
        // Nothing to do here
    }


}
