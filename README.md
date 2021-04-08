# Category-Image-Generation

Plugin which generates images for categories which doesnt have a image - based on three random products of the category
and their subcategories (down to 5th level).

By default - images will be checked every day at 00:00.

The plugin uses the jtl-shop tables for the images - no custom mechanism was provided for image handling

## Supported image types

Article images could be type of

* jpeg
* gif
* png

## Requirements

php-gd support for jpeg, png, gif, ...

For example:
``apt install php7.4-gd``

## TODOs

* check deletion of generated images at uninstall -> maybe delete it manually
* implement enable/disable feature