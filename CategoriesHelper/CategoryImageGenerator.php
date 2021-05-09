<?php


namespace Plugin\t4it_category_image_generation\CategoriesHelper;


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
        $categoryImage = self::createTransparentImage(1024, 1024);

        $productImagesCount = sizeof($productImages);
        if ($productImagesCount == 3) {
            $imageNumber = 0;
            foreach ($productImages as $productImage) {
                $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $productImage->getCPath();
                if (\file_exists($sourceImagePath)) {
                    $image = self::getResizedArticleImage($sourceImagePath, 500, 500);
                    if ($imageNumber == 0) {
                        \imagecopy($categoryImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                    } elseif ($imageNumber == 1) {
                        \imagecopy($categoryImage, $image, 500, 24, 0, 0, imagesx($image), imagesy($image));
                    } else {
                        \imagecopy($categoryImage, $image, 250, 500 + 24, 0, 0, imagesx($image), imagesy($image));
                    }

                    \imagedestroy($image);
                }

                $imageNumber++;
            }
        } elseif ($productImagesCount == 2) {
            $imageNumber = 0;
            foreach ($productImages as $productImage) {
                $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $productImage->getCPath();
                if (\file_exists($sourceImagePath)) {
                    $image = self::getResizedArticleImage($sourceImagePath, 500, 500);
                    if ($imageNumber == 0) {
                        \imagecopy($categoryImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                    } else {
                        \imagecopy($categoryImage, $image, 500, 500, 0, 0, imagesx($image), imagesy($image));
                    }

                    \imagedestroy($image);
                }

                $imageNumber++;
            }
        } elseif ($productImagesCount == 1) {
            $productImage = $productImages[0];

            $sourceImagePath = \PFAD_ROOT . \PFAD_MEDIA_IMAGE_STORAGE . $productImage->getCPath();
            if (\file_exists($sourceImagePath)) {
                $image = self::getResizedArticleImage($sourceImagePath, 1024, 1024);
                \imagecopy($categoryImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                \imagedestroy($image);
            }

        }

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
     * @param int $width
     * @param int $height
     * @return false|\GdImage|resource
     */
    private static function createTransparentImage(int $width, int $height)
    {
        $image = \imagecreatetruecolor($width, $height);
        $colorTransparent = \imagecolorallocatealpha($image, 0, 0, 0, 127);
        \imagefill($image, 0, 0, $colorTransparent);
        \imagealphablending($image, true);
        \imagesavealpha($image, true);

        return $image;
    }


    /**
     * @param string $originalImagePath
     * @param int $targetWidth
     * @param int $targetHeight
     * @return false|\GdImage|resource
     */
    private static function getResizedArticleImage(string $originalImagePath, int $targetWidth = 640, int $targetHeight = 640)
    {
        list($originalImageWidth, $originalImageHeight, $originalImageType) = \getimagesize($originalImagePath);
        switch ($originalImageType) {
            case \IMAGETYPE_GIF:
                $imageOriginal = \imagecreatefromgif($originalImagePath);
                break;
            case \IMAGETYPE_PNG:
                $imageOriginal = \imagecreatefrompng($originalImagePath);
                break;
            case \IMAGETYPE_JPEG:
            default:
                $imageOriginal = \imagecreatefromjpeg($originalImagePath);
                break;
        }

        if ($originalImageWidth > $originalImageHeight) {
            $scale = $targetWidth / $originalImageWidth;
        } else {
            $scale = $targetHeight / $originalImageHeight;
        }

        $newWidth = $originalImageWidth * $scale;
        $newHeight = $originalImageHeight * $scale;

        $offsetX = ($targetWidth - $newWidth) / 2;
        $offsetY = ($targetHeight - $newHeight) / 2;

        $imageResized = self::createTransparentImage($targetWidth, $targetHeight);
        \imagecopyresampled($imageResized, $imageOriginal, $offsetX, $offsetY, 0, 0, $newWidth, $newHeight, $originalImageWidth, $originalImageHeight);
        \imagedestroy($imageOriginal);

        return $imageResized;
    }

}