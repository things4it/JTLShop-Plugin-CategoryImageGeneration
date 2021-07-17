<?php declare(strict_types=1);

// TODO: load the strategies dynamically - just check for classes which implements the interface

use Plugin\t4it_category_image_generation\src\service\placementStrategy\flippedOffset\FlippedOffsetThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetThreeProductImagesPlacementStrategy;

$option = new stdClass();

$option->cWert = OffsetThreeProductImagesPlacementStrategy::class;
$option->cName = OffsetThreeProductImagesPlacementStrategy::getName();
$option->nSort = 1;
$options[] = $option;

$option = new stdClass();
$option->cWert = FlippedOffsetThreeProductImagesPlacementStrategy::class;
$option->cName = FlippedOffsetThreeProductImagesPlacementStrategy::getName();
$option->nSort = 2;
$options[] = $option;

return $options;