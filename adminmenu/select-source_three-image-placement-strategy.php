<?php declare(strict_types=1);

// TODO: recheck dynamic resolving: doesnt worked in prod
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio1to1\OffsetRatio1to1ThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\offset\ratio4to3\OffsetRatio4to3ThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\row\flat\RowFlatThreeProductImagesPlacementStrategy;
use Plugin\t4it_category_image_generation\src\service\placementStrategy\rowCropped\flat\RowCroppedFlatThreeProductImagesPlacementStrategy;

$option = new stdClass();
$option->cWert = OffsetRatio1to1ThreeProductImagesPlacementStrategy::getCode();
$option->cName = OffsetRatio1to1ThreeProductImagesPlacementStrategy::getName();
$option->nSort = 1;
$options[] = $option;

$option = new stdClass();
$option->cWert = OffsetRatio4to3ThreeProductImagesPlacementStrategy::getCode();
$option->cName = OffsetRatio4to3ThreeProductImagesPlacementStrategy::getName();
$option->nSort = 2;
$options[] = $option;

$option = new stdClass();
$option->cWert = RowFlatThreeProductImagesPlacementStrategy::getCode();
$option->cName = RowFlatThreeProductImagesPlacementStrategy::getName();
$option->nSort = 3;
$options[] = $option;

$option = new stdClass();
$option->cWert = RowCroppedFlatThreeProductImagesPlacementStrategy::getCode();
$option->cName = RowCroppedFlatThreeProductImagesPlacementStrategy::getName();
$option->nSort = 4;
$options[] = $option;

return $options;

