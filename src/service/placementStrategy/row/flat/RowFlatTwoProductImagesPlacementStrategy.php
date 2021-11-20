<?php


namespace Plugin\t4it_category_image_generation\src\service\placementStrategy\row\flat;


use Plugin\t4it_category_image_generation\src\Constants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ImagePlacementData;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\ImagePlacementUtils;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\row\RowConstants;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\TwoProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\ImageUtils;

class RowFlatTwoProductImagesPlacementStrategy implements TwoProductImagePlacementStrategyInterface
{
    /**
     * @var int
     */
    private static $WIDTH = 1024;

    /**
     * @var int
     */
    private static $HEIGHT = 342;

    public static function getName(): string
    {
        return __("admin.settings.image-strategy.row-flat");
    }

    public static function getCode(): string
    {
        return Constants::IMAGE_GENERATION_STRATEGY_PREFIX . "row-flat-two";
    }

    public function placeProductImages($productImage1, $productImage2)
    {
        $categoryImage = ImageUtils::createTransparentImage(self::$WIDTH, self::$HEIGHT);

        $productImageSize = (int) ((self::$WIDTH / 3) - (2 * RowConstants::PADDING_BETWEEN));
        $productImage1 = ImageUtils::resizeImageToMaxWidthHeight($productImage1, $productImageSize, $productImageSize);
        $productImage2 = ImageUtils::resizeImageToMaxWidthHeight($productImage2, $productImageSize, $productImageSize);

        $productImage1Data = new ImagePlacementData($productImage1);
        $productImage2Data = new ImagePlacementData($productImage2);

        $productImageDatas = $this->createProductImageArraySortedByHeight($productImage1Data, $productImage2Data);

        $offsetX = ImagePlacementUtils::calculateOffsetXForImagesBlock($productImageDatas, self::$WIDTH, RowConstants::PADDING_BETWEEN);
        foreach ($productImageDatas as $productImageData){
            $offsetY = ImagePlacementUtils::calculateOffsetYByTargetImageHeight($productImageData, self::$HEIGHT);
            ImagePlacementUtils::copyImage($productImageData, $offsetX, $offsetY, $categoryImage);
            $offsetX += $productImageData->getWidth() + RowConstants::PADDING_BETWEEN;
        }

        return $categoryImage;
    }

    /**
     * @param ImagePlacementData[] $productImageDatas
     * @return ImagePlacementData[]
     */
    private function createProductImageArraySortedByHeight(... $productImageDatas): array
    {
        ImagePlacementUtils::sortImagesArrayByHeightAsc($productImageDatas);

        return array($productImageDatas[0], $productImageDatas[1]);
    }
}