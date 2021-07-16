<?php declare(strict_types=1);

use Plugin\t4it_category_image_generation\src\service\placementStrategy\DefaultOneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\FlipOneProductImagePlacementStrategy;

// TODO: load the strategies dynamically - just check for classes which implements the interface

$option = new stdClass();

$option->cWert = DefaultOneProductImagePlacementStrategy::class;
$option->cName = DefaultOneProductImagePlacementStrategy::getName();
$option->nSort = 1;
$options[] = $option;

$option = new stdClass();
$option->cWert = FlipOneProductImagePlacementStrategy::class;
$option->cName = FlipOneProductImagePlacementStrategy::getName();
$option->nSort = 2;
$options[] = $option;

return $options;
