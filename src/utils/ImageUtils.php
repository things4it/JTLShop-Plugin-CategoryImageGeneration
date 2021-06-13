<?php


namespace Plugin\t4it_category_image_generation\src\utils;


use Plugin\t4it_category_image_generation\src\service\ProductImagesPlacementService;

class ImageUtils
{

    /**
     * @param int $width
     * @param int $height
     * @return false|\GdImage|resource
     */
    public static function createTransparentImage(int $width, int $height)
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
     * @param int $padding
     * @return false|\GdImage|resource
     */
    public static function createResizedImage(string $originalImagePath, int $targetWidth = 640, int $targetHeight = 640, int $padding = 15)
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

        $newWidth = $originalImageWidth * $scale - $padding * 2;
        $newHeight = $originalImageHeight * $scale - $padding * 2;

        $offsetX = ($targetWidth - $newWidth) / 2;
        $offsetY = ($targetHeight - $newHeight) / 2;

        $imageResized = ImageUtils::createTransparentImage($targetWidth, $targetHeight);
        \imagecopyresampled($imageResized, $imageOriginal, $offsetX, $offsetY, 0, 0, $newWidth, $newHeight, $originalImageWidth, $originalImageHeight);
        \imagedestroy($imageOriginal);

        return $imageResized;
    }

}