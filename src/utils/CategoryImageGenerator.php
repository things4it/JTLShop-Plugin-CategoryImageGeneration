<?php


namespace Plugin\t4it_category_image_generation\src\utils;


use Exception;
use JTL\Shop;
use Plugin\t4it_category_image_generation\src\db\entity\Image;

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
     * @param string $imageStrategyOneImage
     * @param string $imageStrategyTwoImages
     * @param string $imageStrategyThreeImages
     * @return string
     */
    public static function generateCategoryImage(int $categoryId, array $productImages, string $imageStrategyOneImage, string $imageStrategyTwoImages, string $imageStrategyThreeImages): string
    {
        $categoryImage = self::generateImageWithConfiguredStrategy($productImages, $imageStrategyOneImage, $imageStrategyTwoImages, $imageStrategyThreeImages);

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
     * @param Image[] $productImages
     * @throws Exception
     */
    private static function generateImageWithConfiguredStrategy(array $productImages, string $imageStrategyOneImage, string $imageStrategyTwoImages, string $imageStrategyThreeImages)
    {
        $productImageFiles = array();
        foreach ($productImages as $productImage) {
            $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $productImage->getCPath();
            if (\file_exists($sourceImagePath)) {
                $image = ImageUtils::createImage($sourceImagePath);
                $productImageFiles[] = $image;
            }
        }

        $productImageFilesCount = sizeof($productImageFiles);
        if ($productImageFilesCount == 3) {
            $threeProductImagePlacementStrategyInterface = Shop::Container()->get($imageStrategyThreeImages);
            return $threeProductImagePlacementStrategyInterface->placeProductImages($productImageFiles[0], $productImageFiles[1], $productImageFiles[2]);
        } elseif ($productImageFilesCount == 2) {
            $twoProductImagePlacementStrategyInterface = Shop::Container()->get($imageStrategyTwoImages);
            return $twoProductImagePlacementStrategyInterface->placeProductImages($productImageFiles[0], $productImageFiles[1]);
        } elseif ($productImageFilesCount == 1) {
            $oneProductImagePlacementStrategyInterface = Shop::Container()->get($imageStrategyOneImage);
            return $oneProductImagePlacementStrategyInterface->placeProductImages($productImageFiles[0]);
        }

        foreach ($productImageFiles as $productImageFile) {
            \imagedestroy($productImageFile);
        }
    }

}