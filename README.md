# Menu module for [Fork-cms](http://www.fork-cms.com)

## About
* Version: 0.0.9
* ForkCMS version: 3.5.0+
* Name: Quickmenu
* Author: Lysander
* Website: http://www.reclame-mediabureau.nl/

## Installation
* You can install the module through the backend by uploading the downloaded zipball (first unpak and remove the readme file and repack the backend and frontend folders).
* You can unpack the zipball and upload the quickmenu folders in the /backend/modules and /frontend/modules files manually. After that you need to click install in the backend settings/modules page.

## Information
### What does the quickmenu module do?
The Quickmenu is for you if you need to display an alternate menu on your website.

### What are it's features?
* You can create your own menus by using categories.
* Organise the menu items in the backend by drag 'n drop.
* You can add a menu by adding a widget on a page or hardcoded in the tempate by using (where 1 stands for the category id):

        {$var|parsewidget:"quickmenu":"detail":"1"}

