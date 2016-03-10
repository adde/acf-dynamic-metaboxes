WordPress ACF Dynamic Metaboxes
===============================

A WordPress plugin that gives you the option to show and hide ACF metaboxes based on chosen category.


Installation
------------
Download as zip. Unzip folder into `yoursite/wp-content/plugins`. Activate the plugin in WordPress admin.


Usage
-----
Configure metabox settings at "Settings" -> "Dynamic Metaboxes". Enter the configuration as an JSON expression using the following pattern:

```
[
	{
		"id": "acf_xxx",
		"types": [1,2,3]
	}
]
```

The types array should contain IDs of the categories that should trigger the metabox to show.


License
-------
This software is free and carries a MIT license.


Changelog
---------
v1.2.0 (2016-03-10)
* Added support for translations.

v1.1.0 (2016-03-10)
* Converted plugin to OOP.
* Added option for dropdown selector.

v1.0.0 (2016-03-09)
* First release.