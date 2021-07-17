<?php declare(strict_types=1);


// TODO: load the strategies dynamically - just check for classes which implements the interface

use Plugin\t4it_category_image_generation\src\service\placementStrategy\flippedOffset\FlippedOffsetOneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\OffsetOneProductImagePlacementStrategy;

$option = new stdClass();

$option->cWert = OffsetOneProductImagePlacementStrategy::getCode();
$option->cName = OffsetOneProductImagePlacementStrategy::getName();
$option->nSort = 1;
$options[] = $option;

$option = new stdClass();
$option->cWert = FlippedOffsetOneProductImagePlacementStrategy::getCode();
$option->cName = FlippedOffsetOneProductImagePlacementStrategy::getName();
$option->nSort = 2;
$options[] = $option;

return $options;
