<?php

namespace Plugin\t4it_category_image_generation\src\service;

use JTL\DB\DbInterface;
use JTL\Media\Image\Category;
use JTL\Plugin\PluginInterface;
use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\db\dao\CategoryHelperDao;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\model\ImageRatioFactory;
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
     * @var PluginInterface
     */
    private PluginInterface $plugin;

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
     * @param PluginInterface $plugin
     */
    public function __construct(DbInterface $db, PluginInterface $plugin)
    {
        $this->db = $db;
        $this->plugin = $plugin;

        $this->maxArticleImages = (int)$plugin->getConfig()->getValue(Constants::SETTINGS_MAX_ARTICLE_IMAGES_PER_CATEGORY);
        $this->imageRatio = ImageRatioFactory::createFromRatioString((string)$plugin->getConfig()->getValue(Constants::SETTINGS_CATEGORY_IMAGE_RATIO));
        $this->imageStrategyOneImage = $plugin->getConfig()->getValue(Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_ONE_IMAGE);
        $this->imageStrategyTwoImages = $plugin->getConfig()->getValue(Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TWO_IMAGES);
        $this->imageStrategyThreeImages = $plugin->getConfig()->getValue(Constants::SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TREE_IMAGES);
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