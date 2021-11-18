<?php declare(strict_types=1);

// TODO: recheck dynamic resolving: doesnt worked in prod
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio1to1\OffsetRatio1to1OneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio4to3\OffsetRatio4to3OneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\row\flat\RowFlatOneProductImagePlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped\flat\RowCroppedFlatOneProductImagePlacementStrategy;

$option = new stdClass();
$option->cWert = OffsetRatio1to1OneProductImagePlacementStrategy::getCode();
$option->cName = OffsetRatio1to1OneProductImagePlacementStrategy::getName();
$option->nSort = 1;
$options[] = $option;

$option = new stdClass();
$option->cWert = OffsetRatio4to3OneProductImagePlacementStrategy::getCode();
$option->cName = OffsetRatio4to3OneProductImagePlacementStrategy::getName();
$option->nSort = 2;
$options[] = $option;

$option = new stdClass();
$option->cWert = RowFlatOneProductImagePlacementStrategy::getCode();
$option->cName = RowFlatOneProductImagePlacementStrategy::getName();
$option->nSort = 3;
$options[] = $option;

$option = new stdClass();
$option->cWert = RowCroppedFlatOneProductImagePlacementStrategy::getCode();
$option->cName = RowCroppedFlatOneProductImagePlacementStrategy::getName();
$option->nSort = 4;
$options[] = $option;

return $options;
