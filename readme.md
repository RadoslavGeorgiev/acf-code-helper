# ACF Code Helper
This is a small helper library, whose purpose is to ease your field creation process when using the [Advanced Custom Fields](https://www.advancedcustomfields.com/) plugin to register fields via PHP.

Normally, ACF allows you to create fields via PHP, as you can read at [https://www.advancedcustomfields.com/resources/register-fields-via-php/](https://www.advancedcustomfields.com/resources/register-fields-via-php/). However, this process requires you to have a unique field key for each field, which is afterwards used in conditional logic statements and generally, this can be quite inconvinient. 

This helper assists you by automatically generating field keys based on the name of a fields group and the name of the current field.

One of my other plugins, the [Twig Framework for WordPress](https://github.com/RadoslavGeorgiev/twig-framework) is a perfect match for this plugin, as the framework has built-in support for content blocks, widgets and more, which rely on this class.

## Installation
1. Download the repository as a .zip file and install as a plugin.
2. Create some groups.

## Documentation
Please check the [Wiki](https://github.com/RadoslavGeorgiev/acf-code-helper/wiki) of the project.

## Author
The framework is being developed by me, [Radoslav Georgiev](http://rageorgiev.com), web developer at [DigitalWerk](https://digitalwerk.agency/en/).
