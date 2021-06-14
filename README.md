# Category-Image-Generation

This plugin generates images for categories which doesn't have a image.

The generated image is based on random products of the category and their subcategories (down to 5th level).

* The max amount of images which should be used is configurable (1 to 3, 3 is default)
* The ratio of the generated image is configurable (1:1 or 4:3)
* Images of articles which are a direct child of the category will be preferred - only when there are not enough
  articles with images (config max-count) - the articles of subcategories down to level 5 will be checked

By default missing images will be checked every day at 00:00 via cron-job.

The plugin uses the jtl-shop default tables for the images

* no custom mechanism was provided for image handling
* no performance impact
* configured images via jtl-wawi will win

See also: https://github.com/things4it/JTLShop-Plugin-CategoryImageGeneration/wiki

## Plugin Settings

Changes at settings will trigger the regeneration of all generated images!
Before regeneration all generated images will be deleted - so for a short time you have no category images!

## Re-Generate images for specific categories

You can generate images for specific categories via the plugin settings tab "Bild neu generieren (einzeln)"/"Re-Create a
category image".

**Note**: if you "override" a image which was provided by the jtl-wawi sync - it will be overridden by the next sync.

## Supported image types

Article images could be type of

* jpeg
* gif
* png

# Installation

## Plugin Upload

Ensure the plugin-folder is named as ``t4it_category_image_generation``, zip and upload it via the Plugin-Manager :)

## PHP-DB is required

PHP-GD have to be installed with support for jpeg, png and gif.

Example for PHP-GD: ``apt install php7.4-gd``