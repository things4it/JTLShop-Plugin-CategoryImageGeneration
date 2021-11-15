<?php declare(strict_types=1);


// TODO: load the strategies dynamically - just check for classes which implements the interface

use Plugin\t4it_category_image_generation\src\service\placementStrategy\flippedOffset\FlippedOffsetTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\row\RowTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped\RowCroppedTwoProductImagesPlacementStrategy;

$option = new stdClass();
$option->cWert = OffsetTwoProductImagesPlacementStrategy::getCode();
$option->cName = OffsetTwoProductImagesPlacementStrategy::getName();
$option->nSort = 1;
$options[] = $option;

$option = new stdClass();
$option->cWert = FlippedOffsetTwoProductImagesPlacementStrategy::getCode();
$option->cName = FlippedOffsetTwoProductImagesPlacementStrategy::getName();
$option->nSort = 2;
$options[] = $option;

$option = new stdClass();
$option->cWert = RowTwoProductImagesPlacementStrategy::getCode();
$option->cName = RowTwoProductImagesPlacementStrategy::getName();
$option->nSort = 3;
$options[] = $option;

$option = new stdClass();
$option->cWert = RowCroppedTwoProductImagesPlacementStrategy::getCode();
$option->cName = RowCroppedTwoProductImagesPlacementStrategy::getName();
$option->nSort = 4;
$options[] = $option;

return $options;
