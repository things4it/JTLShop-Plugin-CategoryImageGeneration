<?php declare(strict_types=1);

// TODO: load the strategies dynamically - just check for classes which implements the interface

use Plugin\t4it_category_image_generation\src\service\placementStrategy\flippedOffset\FlippedOffsetThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\row\RowThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped\HorizontalCroppedThreeProductImagesPlacementStrategy;

$option = new stdClass();
$option->cWert = OffsetThreeProductImagesPlacementStrategy::getCode();
$option->cName = OffsetThreeProductImagesPlacementStrategy::getName();
$option->nSort = 1;
$options[] = $option;

$option = new stdClass();
$option->cWert = FlippedOffsetThreeProductImagesPlacementStrategy::getCode();
$option->cName = FlippedOffsetThreeProductImagesPlacementStrategy::getName();
$option->nSort = 2;
$options[] = $option;

$option = new stdClass();
$option->cWert = RowThreeProductImagesPlacementStrategy::getCode();
$option->cName = RowThreeProductImagesPlacementStrategy::getName();
$option->nSort = 3;
$options[] = $option;

$option = new stdClass();
$option->cWert = HorizontalCroppedThreeProductImagesPlacementStrategy::getCode();
$option->cName = HorizontalCroppedThreeProductImagesPlacementStrategy::getName();
$option->nSort = 4;
$options[] = $option;

return $options;
