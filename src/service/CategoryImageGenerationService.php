<?php

namespace Plugin\t4it_category_image_generation\src\service;

use JTL\DB\DbInterface;
use JTL\Media\Image\Category;
use JTL\Plugin\Helper;
use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\db\dao\CategoryHelperDao;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\model\ImageRatioFactory;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\DefaultOneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\DefaultThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\DefaultTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\utils\CategoryImageGenerator;


interface CategoryImageGenerationServiceInterface
{
    /**
     * @param int $categoryId
     * @throws \Exception
     */
    public function generateCategoryImage(int $categoryId);
}

class CategoryImageGenerationService implements CategoryImageGenerationServiceInterface
{

    /**
     * @var DbInterface
     */
    private $db;

    /**
     * @var int
     */
    private $maxArticleImages;


    /**
     * @var ImageRatio
     */
    private $imageRatio;

    /**
     * @var mixed|string|null
     */
    private $imageStrategyOneImage;

    /**
     * @var mixed|string|null
     */
    private $imageStrategyTwoImages;

    /**
     * @var mixed|string|null
     */
    private $imageStrategyThreeImages;

    /**
     * CategoryImageGenerationService constructor.
     * @param DbInterface $db
     */
    public function __construct(DbInterface $db)
    {
        $this->db = $db;

        $plugin = Helper::getPluginById(Constants::PLUGIN_ID);
        if ($plugin === null) {
            $this->maxArticleImages = 3;
            $this->imageRatio = ImageRatioFactory::createFromRatioString(ImageRatio::RATIO_1_TO_1);
            $this->imageStrategyOneImage = DefaultOneProductImagePlacementStrategy::class;
            $this->imageStrategyTwoImages = DefaultTwoProductImagesPlacementStrategy::class;
            $this->imageStrategyThreeImages = DefaultThreeProductImagesPlacementStrategy::class;
        } else {
            $this->maxArticleImages = (int)$plugin->getConfig()->getValue(Constants::SETTINGS_MAX_ARTICLE_IMAGES_PER_CATEGORY);

            $categoryImageRatio = (string)$plugin->getConfig()->getValue(Constants::SETTINGS_CATEGORY_IMAGE_RATIO);
            $this->imageRatio = ImageRatioFactory::createFromRatioString($categoryImageRatio);

            $configuredImageStrategyOneImage = $plugin->getConfig()->getValue(Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_ONE_IMAGE);
            if (strlen($configuredImageStrategyOneImage) > 0) {
                $this->imageStrategyOneImage = $configuredImageStrategyOneImage;
            }

            $configuredImageStrategyTwoImages = $plugin->getConfig()->getValue(Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TWO_IMAGES);
            if (strlen($configuredImageStrategyTwoImages) > 0) {
                $this->imageStrategyTwoImages = $configuredImageStrategyTwoImages;
            }

            $configuredImageStrategyTreeImages = $plugin->getConfig()->getValue(Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TREE_IMAGES);
            if (strlen($configuredImageStrategyTreeImages) > 0) {
                $this->imageStrategyThreeImages = $configuredImageStrategyTreeImages;
            }
        }
    }


    public function generateCategoryImage(int $categoryId)
    {
        $randomArticleImages = CategoryHelperDao::findRandomArticleImages($categoryId, $this->maxArticleImages, $this->db);
        $randomArticleImagesCount = sizeof($randomArticleImages);
        if ($randomArticleImagesCount > 0) {
            $categoryImagePath = CategoryImageGenerator::generateCategoryImage(
                $categoryId,
                $randomArticleImages,
                $this->imageRatio,
                $this->imageStrategyOneImage,
                $this->imageStrategyTwoImages,
                $this->imageStrategyThreeImages
            );
            CategoryHelperDao::saveCategoryImage($categoryId, $categoryImagePath, $this->db);

            Category::clearCache($categoryId);
        } else {
            throw new \Exception(sprintf('No articles with images found for category %s', $categoryId));
        }

    }


}