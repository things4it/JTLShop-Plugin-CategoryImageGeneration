<?php

namespace Plugin\t4it_category_image_generation\src\service;

use JTL\DB\DbInterface;
use JTL\Media\Image\Category;
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

    /**
     * CategoryImageGenerationService constructor.
     * @param DbInterface $db
     */
    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }


    public function generateCategoryImage(int $categoryId)
    {
        $randomArticleImages = CategoryHelperDao::findRandomArticleImages($categoryId, $this->db);
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