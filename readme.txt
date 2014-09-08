Google Earth Tours Embed
========================

Allow for embedding of Google Earth Tour kmz files via shortcode

## Installation ##

1. Upload the `google-earth-tours-embed` folder to the `/wp-content/plugins/` directory or install from the dashboard
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place the shortcode in your WordPress content

## Setup

* Upload a .kmz file of your Google Earth Tour into WordPress. Instructions on exporting the content [can be found here](http://www.google.com/earth/outreach/tutorials/kmz.html)
* Place the shortcode in your post / page content

## Shortcode Paramaters

* file		- required
* title		- optional. will not display if not provided
* height	- optional. will default to 400px. can also set value in CSS
* width		- optional. will default to 600px. can also set value in CSS
```
[gearthtour file="YOUR-FILE-NAME.kmz" title="My Optional Title"]
```