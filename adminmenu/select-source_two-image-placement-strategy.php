<?php declare(strict_types=1);

// TODO: recheck dynamic resolving: doesnt worked in prod
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio1to1\OffsetRatio1to1TwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio4to3\OffsetRatio4to3TwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\row\flat\RowFlatTwoProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped\flat\RowCroppedFlatTwoProductImagesPlacementStrategy;

$option = new stdClass();
$option->cWert = OffsetRatio1to1TwoProductImagesPlacementStrategy::getCode();
$option->cName = OffsetRatio1to1TwoProductImagesPlacementStrategy::getName();
$option->nSort = 1;
$options[] = $option;

$option = new stdClass();
$option->cWert = OffsetRatio4to3TwoProductImagesPlacementStrategy::getCode();
$option->cName = OffsetRatio4to3TwoProductImagesPlacementStrategy::getName();
$option->nSort = 2;
$options[] = $option;

$option = new stdClass();
$option->cWert = RowFlatTwoProductImagesPlacementStrategy::getCode();
$option->cName = RowFlatTwoProductImagesPlacementStrategy::getName();
$option->nSort = 3;
$options[] = $option;

$option = new stdClass();
$option->cWert = RowCroppedFlatTwoProductImagesPlacementStrategy::getCode();
$option->cName = RowCroppedFlatTwoProductImagesPlacementStrategy::getName();
$option->nSort = 4;
$options[] = $option;

return $options;
