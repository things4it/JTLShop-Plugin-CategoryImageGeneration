<?php


namespace Plugin\t4it_category_image_generation\src\utils;


use JTL\Shop;
use Plugin\t4it_category_image_generation\src\db\entity\Image;
use Plugin\t4it_category_image_generation\src\model\ImageRatio;
use Plugin\t4it_category_image_generation\src\service\ProductImagesPlacementService;

class CategoryImageGenerator
{

    private const CATEGORY_IMAGE_NAME_PREFIX = 't4it_cig_generated_';

    public static function getImageNamePrefix(): string
    {
        return self::CATEGORY_IMAGE_NAME_PREFIX;
    }

    /**
     * @param int $categoryId
     * @param Image[] $productImages
     * @param ImageRatio $imageRatio
     * @param string $imageStrategyOneImage
     * @param string $imageStrategyTwoImages
     * @param string $imageStrategyThreeImages
     * @return string
     */
    public static function generateCategoryImage(int $categoryId, array $productImages, ImageRatio $imageRatio, string $imageStrategyOneImage, string $imageStrategyTwoImages, string $imageStrategyThreeImages): string
    {
        $categoryImage = ImageUtils::createTransparentImage($imageRatio->getWidth(), $imageRatio->getHeight());

        self::placeProductImageToCategoryImage($categoryImage, $productImages, $imageRatio, $imageStrategyOneImage, $imageStrategyTwoImages, $imageStrategyThreeImages);

        $targetImageName = self::CATEGORY_IMAGE_NAME_PREFIX . $categoryId . '.png';
        $targetImagePath = \PFAD_ROOT . \STORAGE_CATEGORIES . $targetImageName;
        \imagecropauto($categoryImage);
        \imagepng($categoryImage, $targetImagePath);
        \imagedestroy($categoryImage);

        return $targetImageName;
    }

    public static function removeGeneratedImages()
    {
        $categoryImageFiles = glob(\PFAD_ROOT . \STORAGE_CATEGORIES . self::CATEGORY_IMAGE_NAME_PREFIX . '*.png');
        foreach ($categoryImageFiles as $categoryImageFile) {
            if (file_exists($categoryImageFile) && !is_dir($categoryImageFile)) {
                unlink($categoryImageFile);
            }
        }
    }

    public static function removeGeneratedImage(int $categoryId)
    {
        $categoryImageFiles = glob(\PFAD_ROOT . \STORAGE_CATEGORIES . self::CATEGORY_IMAGE_NAME_PREFIX . $categoryId . '.png');
        foreach ($categoryImageFiles as $categoryImageFile) {
            if (file_exists($categoryImageFile) && !is_dir($categoryImageFile)) {
                unlink($categoryImageFile);
            }
        }
    }

    /**
     * @param $categoryImage
     * @param Image[] $productImages
     * @param ImageRatio $imageRatio
     */
    private static function placeProductImageToCategoryImage($categoryImage, array $productImages, ImageRatio $imageRatio, string $imageStrategyOneImage, string $imageStrategyTwoImages, string $imageStrategyThreeImages)
    {
        $productImageFiles = array();
        foreach ($productImages as $productImage) {
            $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $productImage->getCPath();
            if (\file_exists($sourceImagePath)) {
                $image = ImageUtils::createResizedImage($sourceImagePath, $imageRatio->getWidth(), $imageRatio->getHeight());
                $productImageFiles[] = $image;
            }
        }

        $productImageFilesCount = sizeof($productImageFiles);
        if ($productImageFilesCount == 3) {
            $threeProductImagePlacementStrategyInterface = Shop::Container()->get($imageStrategyThreeImages);
            $threeProductImagePlacementStrategyInterface->placeProductImages($categoryImage, $imageRatio, $productImageFiles[0], $productImageFiles[1], $productImageFiles[2]);
        } elseif ($productImageFilesCount == 2) {
            $twoProductImagePlacementStrategyInterface = Shop::Container()->get($imageStrategyTwoImages);
            $twoProductImagePlacementStrategyInterface->placeProductImages($categoryImage, $imageRatio, $productImageFiles[0], $productImageFiles[1]);
        } elseif ($productImageFilesCount == 1) {
            $oneProductImagePlacementStrategyInterface = Shop::Container()->get($imageStrategyOneImage);
            $oneProductImagePlacementStrategyInterface->placeProductImages($categoryImage, $imageRatio, $productImageFiles[0]);
        }

        foreach ($productImageFiles as $productImageFile) {
            \imagedestroy($productImageFile);
        }
    }

}