<?php

namespace Plugin\t4it_category_image_generation\src\service;

use JTL\DB\DbInterface;
use JTL\Media\Image\Category;
use JTL\Plugin\Helper;
use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\db\dao\CategoryHelperDao;
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

    private DbInterface $db;
    private int $maxArticleImages;

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
        } else {
            $this->maxArticleImages = (int)$plugin->getConfig()->getValue(Constants::SETTINGS_MAX_ARTICLE_IMAGES_PER_CATEGORY);
        }
    }


    public function generateCategoryImage(int $categoryId)
    {
        $randomArticleImages = CategoryHelperDao::findRandomArticleImages($categoryId, $this->maxArticleImages, $this->db);
        $randomArticleImagesCount = sizeof($randomArticleImages);
        if ($randomArticleImagesCount > 0) {
            $categoryImagePath = CategoryImageGenerator::generateCategoryImage($categoryId, $randomArticleImages);
            CategoryHelperDao::saveCategoryImage($categoryId, $categoryImagePath, $this->db);

            Category::clearCache($categoryId);
        } else {
            throw new \Exception(sprintf('No articles with images found for category %s', $categoryId));
        }

    }


}