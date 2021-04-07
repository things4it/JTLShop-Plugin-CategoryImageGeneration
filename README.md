# Category-Image-Generation

Plugin which generates images for categories which doesnt have a image - based on three random products of the category.
By default - images will be checked every day at 00:00.

## Supported image types

Article images could be type of

* jpeg
* gif
* png

## Implementation details

The plugin uses the jtl-shop tables for the images - no custom mechanism was provided for image handling

## TODOs

* check deletion of generated images at uninstall -> maybe delete it manually
* implement enable/disable feature
* manually triggering image generation
* i18n
* check also all subcategories for images ...