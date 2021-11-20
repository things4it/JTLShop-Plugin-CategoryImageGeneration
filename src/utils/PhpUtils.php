<?php


namespace Plugin\t4it_category_image_generation\src\utils;


use JTL\Shop;
use ReflectionClass;
use ReflectionException;

class PhpUtils
{

    /**
     * @param string $interface
     * @return array
     */
    public static function getImplementations(string $interface): array
    {
        $implementsInterface = array();

        foreach(get_declared_classes() as $clazz) {
            try {
                $reflectionClass = new ReflectionClass($clazz);
                if ($reflectionClass->implementsInterface($interface)) {
                    $implementsInterface[] = $clazz;
                }
            } catch (ReflectionException $e) {
                // ignore it: because it will blow up the logs and it should never be happen while iteration over get_declared_classes
            }
        }

        if(sizeof($implementsInterface) == 0){
            Shop::Container()->getLogService()->error(\sprintf('No implementations of %s found - CategoryImageGenerationPlugin is corrupt', $interface));
        }

        return $implementsInterface;
    }

}