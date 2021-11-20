<?php


namespace Plugin\t4it_category_image_generation\src\utils;


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
     * @return false|\GdImage|resource
     */
    public static function createImage(string $originalImagePath)
    {
        list($originalImageType) = \getimagesize($originalImagePath);
        switch ($originalImageType) {
            case \IMAGETYPE_GIF:
                return \imagecreatefromgif($originalImagePath);
            case \IMAGETYPE_PNG:
                return \imagecreatefrompng($originalImagePath);
            case \IMAGETYPE_JPEG:
            default:
                return \imagecreatefromjpeg($originalImagePath);
        }
    }

    public static function centerImageInSize($originalImage, int $targetWidth = 640, int $targetHeight = 640, int $padding = 15)
    {
        $originalImageWidth = imagesx($originalImage);
        $originalImageHeight = imagesy($originalImage);

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
        \imagecopyresampled($imageResized, $originalImage, $offsetX, $offsetY, 0, 0, $newWidth, $newHeight, $originalImageWidth, $originalImageHeight);
        \imagedestroy($originalImage);

        return $imageResized;
    }

    public static function resizeImageToMaxWidthHeight($originalImage, int $maxWidth = 640, int $maxHeight = 640)
    {
        $originalImageWidth = imagesx($originalImage);
        $originalImageHeight = imagesy($originalImage);

        if ($originalImageHeight > $originalImageWidth) {
            $ratio = $maxHeight / $originalImageHeight;
            $newHeight = $maxHeight;
            $newWidth = $originalImageWidth * $ratio;
        } else {
            $ratio = $maxWidth / $originalImageWidth;
            $newWidth = $maxWidth;
            $newHeight = $originalImageHeight * $ratio;
        }

        $imageResized = ImageUtils::createTransparentImage($newWidth, $newHeight);
        imagecopyresized($imageResized, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalImageWidth, $originalImageHeight);

        \imagedestroy($originalImage);

        return $imageResized;
    }

}