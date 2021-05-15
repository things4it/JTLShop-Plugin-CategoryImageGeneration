<?php

namespace Plugin\t4it_category_image_generation\src\service;

use JTL\DB\DbInterface;
use Plugin\t4it_category_image_generation\src\db\dao\CategoryHelperDao;
use Plugin\t4it_category_image_generation\src\utils\CategoryImageGenerator;


interface CategoryImageGenerationServiceInterface
{
    /**
     * @param int $categoryId
     * @param DbInterface $db
     * @throws \Exception
     */
    public function generateCategoryImage(int $categoryId, DbInterface $db);
}

class CategoryImageGenerationService implements CategoryImageGenerationServiceInterface
{

    public function generateCategoryImage(int $categoryId, DbInterface $db)
    {
        $randomArticleImages = CategoryHelperDao::findRandomArticleImages($categoryId, $db);
        $randomArticleImagesCount = sizeof($randomArticleImages);
        if ($randomArticleImagesCount > 0) {
            $categoryImagePath = CategoryImageGenerator::generateCategoryImage($categoryId, $randomArticleImages);
            CategoryHelperDao::saveCategoryImage($categoryId, $categoryImagePath, $db);
            \JTL\Media\Image\Category::clearCache($categoryId);
        } else {
            throw new \Exception(sprintf('No articles with images found for category %s', $categoryId));
        }

    }


}