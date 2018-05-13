# wp-avorg

PHP 7.2

## Installation

### Development

0. `git clone https://github.com/AVORG/wp-avorg-dev.git`
0. Follow [`wp-avorg-dev` readme instructions](https://github.com/AVORG/wp-avorg-dev)
0. Optional: Update dependencies: `cd wp-avorg-dev/wp-avorg-plugin && composer install`

### Production

0. [Download the plugin as a zip file](https://github.com/AVORG/wp-avorg-plugin/archive/master.zip)
0. In the WordPress admin panel, navigate to "Plugins > Add New" and click the "Upload Plugin" button
0. Upload the zip file you downloaded
0. Activate the plugin

## Testing

```bash
composer test
```