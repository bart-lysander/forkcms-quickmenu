# Menu module for [Fork-cms](http://www.fork-cms.com)

## About
* Version: 0.0.9
* ForkCMS version: 3.5.0+
* Name: Quickmenu
* Author: Lysander
* Website: http://www.reclame-mediabureau.nl/

## Installation
* You can install the module through the backend by uploading the downloaded zipball (first unpak and remove the files (*.md, .gitignore) and repack the backend and frontend folders).
* You can unpack the zipball and upload the quickmenu folders in the /backend/modules and /frontend/modules files manually. After that you need to click install in the backend settings/modules page.

## Information
### What does the quickmenu module do?
The Quickmenu is for you if you need to display an alternate menu on your website.
ForkCMS already has a main and footer navigation menu.
But sometimes you need an extra navigation list.

### What are it's features?
* You can create your own menus by using categories.
* Organise (order) the menu items in the backend by drag 'n drop.
* Also works in multi language mode.
* You can add a menu by adding a widget on a page or hardcoded in the tempate by adding this line (where 1 stands for the category id):

        {$var|parsewidget:"quickmenu":"detail":"1"}
