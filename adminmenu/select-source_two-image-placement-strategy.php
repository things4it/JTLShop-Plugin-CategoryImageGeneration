<?php declare(strict_types=1);

use Plugin\t4it_category_image_generation\src\service\placementStrategy\DefaultThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\FlipThreeProductImagesPlacementStrategy;

// TODO: load the strategies dynamically - just check for classes which implements the interface

$option = new stdClass();

$option->cWert = DefaultThreeProductImagesPlacementStrategy::class;
$option->cName = DefaultThreeProductImagesPlacementStrategy::getName();
$option->nSort = 1;
$options[] = $option;

$option = new stdClass();
$option->cWert = FlipThreeProductImagesPlacementStrategy::class;
$option->cName = FlipThreeProductImagesPlacementStrategy::getName();
$option->nSort = 2;
$options[] = $option;

return $options;
