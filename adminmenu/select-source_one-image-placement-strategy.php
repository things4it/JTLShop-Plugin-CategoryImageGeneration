<?php declare(strict_types=1);


use Plugin\t4it_category_image_generation\src\service\placementStrategy\OneProductImagePlacementStrategyInterface;
use Plugin\t4it_category_image_generation\src\utils\PhpUtils;

$implementsOneProductImagePlacementStrategy = PhpUtils::getImplementations(OneProductImagePlacementStrategyInterface::class);

$options = [];
$sort = 1;
foreach ($implementsOneProductImagePlacementStrategy as $strategy){
    $option = new stdClass();
    $option->cWert = $strategy::getCode();
    $option->cName = $strategy::getName();
    $option->nSort = $sort++;
    $options[] = $option;
}

return $options;
