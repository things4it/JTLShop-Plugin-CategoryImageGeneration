<?php declare(strict_types=1);


use Plugin\t4it_category_image_generation\src\service\placementStrategy\TwoProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\PhpUtils;

$implementsThreeProductImagePlacementStrategy = PhpUtils::getImplementations(TwoProductImagePlacementStrategyInterface::class);

$sort = 1;
foreach ($implementsThreeProductImagePlacementStrategy as $strategy){
    $option = new stdClass();
    $option->cWert = $strategy::getCode();
    $option->cName = $strategy::getName();
    $option->nSort = $sort++;
    $options[] = $option;
}

return $options;

