# Category-Image-Generation

This plugin generates images for categories which doesn't have a image.

The generated image is based on three random products of the category and their subcategories (down to 5th level). By
default missing images will be checked every day at 00:00 via cron-job.

The plugin uses the jtl-shop default tables for the images
* no custom mechanism was provided for image handling
* no performance impact
* configured images via jtl-wawi will win

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

PHP-GD have to be installed ...

For example: ``apt install php7.4-gd``