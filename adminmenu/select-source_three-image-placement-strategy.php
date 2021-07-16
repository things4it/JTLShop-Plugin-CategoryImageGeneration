<?php declare(strict_types=1);

use Plugin\t4it_category_image_generation\src\service\placementStrategy\DefaultTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\FlipTwoProductImagesPlacementStrategy;

// TODO: load the strategies dynamically - just check for classes which implements the interface

$option = new stdClass();

$option->cWert = DefaultTwoProductImagesPlacementStrategy::class;
$option->cName = DefaultTwoProductImagesPlacementStrategy::getName();
$option->nSort = 1;
$options[] = $option;

$option = new stdClass();
$option->cWert = FlipTwoProductImagesPlacementStrategy::class;
$option->cName = FlipTwoProductImagesPlacementStrategy::getName();
$option->nSort = 2;
$options[] = $option;

return $options;
