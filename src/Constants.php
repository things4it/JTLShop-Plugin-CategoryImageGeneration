<?php

namespace Plugin\t4it_category_image_generation\src;

class Constants
{
    public const PLUGIN_ID = 't4it_category_image_generation';
    public const CRON_JOB_CATEGORY_IMAGE_GENERATION = 'things4it_category_image_generation_cronjob';
    public const SETTINGS_MAX_ARTICLE_IMAGES_PER_CATEGORY = 't4it_category_image_generation-maxArticleImagesPerCategory';
    public const SETTINGS_CATEGORY_IMAGE_RATIO = 't4it_category_image_generation-categoryImageRatio';
    public const SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_ONE_IMAGE = 't4it_category_image_generation-placement_strategy_for_1_image';
    public const SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TWO_IMAGES = 't4it_category_image_generation-placement_strategy_for_2_images';
    public const SETTINGS_CATEGORY_IMAGE_STRATEGY_FOR_TREE_IMAGES = 't4it_category_image_generation-placement_strategy_for_3_images';
}