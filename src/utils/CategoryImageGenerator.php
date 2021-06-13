<?php


namespace Plugin\t4it_category_image_generation\src\utils;


use Plugin\t4it_category_image_generation\src\db\entity\Image;
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
     * @return string
     */
    public static function generateCategoryImage(int $categoryId, array $productImages): string
    {
        $categoryImage = ImageUtils::createTransparentImage(1024, 1024);

        self::placeProductImageToCategoryImage($categoryImage, $productImages);

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
     */
    private static function placeProductImageToCategoryImage($categoryImage, array $productImages)
    {
        $productImageFiles = array();
        foreach ($productImages as $productImage) {
            $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $productImage->getCPath();
            if (\file_exists($sourceImagePath)) {
                $image = ImageUtils::createResizedImage($sourceImagePath, 1024, 1024, 40);
                $productImageFiles[] = $image;
            }
        }

        $productImageFilesCount = sizeof($productImageFiles);
        if ($productImageFilesCount == 3) {
            $imageNumber = 0;
            foreach ($productImageFiles as $productImageFile) {
                if ($imageNumber == 0) {
                    \imagecopyresized($categoryImage, $productImageFile, 0, 0, 0, 0, 500, 500, imagesx($productImageFile), imagesy($productImageFile));
                } elseif ($imageNumber == 1) {
                    \imagecopyresized($categoryImage, $productImageFile, 500, 24, 0, 0, 500, 500, imagesx($productImageFile), imagesy($productImageFile));
                } else {
                    \imagecopyresized($categoryImage, $productImageFile, 250, 500 + 24, 0, 0, 500, 500, imagesx($productImageFile), imagesy($productImageFile));
                }

                $imageNumber++;
            }

        } elseif ($productImageFilesCount == 2) {
            $imageNumber = 0;
            foreach ($productImageFiles as $productImageFile) {
                if ($imageNumber == 0) {
                    \imagecopyresized($categoryImage, $productImageFile, 0, 0, 0, 0, 500, 500, imagesx($productImageFile), imagesy($productImageFile));
                } else {
                    \imagecopyresized($categoryImage, $productImageFile, 500, 500, 0, 0, 500, 500, imagesx($productImageFile), imagesy($productImageFile));
                }

                $imageNumber++;
            }
        } elseif ($productImageFilesCount == 1) {
            $productImageFile = $productImageFiles[0];

            \imagecopyresized($categoryImage, $productImageFile, 0, 0, 0, 0, 1024, 1024, imagesx($productImageFile), imagesy($productImageFile));
        }

        foreach ($productImageFiles as $productImageFile) {
            \imagedestroy($productImageFile);
        }
    }

}