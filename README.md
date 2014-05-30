PrestaShop-MadModules
=====================

CartLimiter is a module that limit what set of products can be added to cart.
Limit is done by category ID's.

It's only been developed to work with ajax cart, if you don't use ajax cart, you need to figureout what to modifiy yourself.

This module requires a manual modification of your theme.
/theme/YOUR_THEME/js/modules/blockcart/ajax-cart.js

I provied a sample for the default-bootstrap theme
Search ajax-cart.js for /* Madman Patch */
All the lines that ends with that, is the lines I added.
