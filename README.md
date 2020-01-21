# wp-avorg

[![latest build | master](https://img.shields.io/badge/latest%20build-master-75d60d)](https://wp-avorg-plugin-master.surge.sh/wp-avorg-plugin.zip)
[![latest build | dev](https://img.shields.io/badge/latest%20build-dev-f39f37)](https://wp-avorg-plugin-dev.surge.sh/wp-avorg-plugin.zip)

## Installation

### Development

0. `git clone https://github.com/AVORG/wp-avorg-dev.git`
0. Follow [`wp-avorg-dev` readme instructions](https://github.com/AVORG/wp-avorg-dev)
0. Optional: Update dependencies: `cd wp-avorg-dev/wp-avorg-plugin && composer install`

If you edit the Composer auto-load rules, you'll need to run `composer dump-autoload` to make them go into effect.

Run `composer update narthur/natlib` to update Composer's commit reference to the natlib dependency. CircleCI will do 
this while running tests, but the updated reference won't be persisted back to the repository.

### Production

0. [Open CircleCI](https://circleci.com/gh/avorg/wp-avorg-plugin) and select the latest build
0. Select the "Artifacts" tab
0. Drill down until you see a file named `wp-avorg-plugin.zip` and click it to download
0. In the WordPress admin panel, navigate to "Plugins > Add New" and click the "Upload Plugin" button
0. Upload the zip file you downloaded
0. Activate the plugin

### PHP 7.2

On MacOS install using [these scripts](https://php-osx.liip.ch/), then add /usr/local/php5/bin to your path.

## Routes

Routes are defined and documented in [the routes.csv file](routes.csv).

## API

Endpoint                                                            | Description
--------------------------------------------------------------------|------------------------------------
`/wp-json/avorg/v1/placeholder-ids`                                 | Retrieve placeholder identifiers
`/wp-json/avorg/v1/placeholder-content/{id}`                        | Retrieve placeholder content

### Data Object Endpoints

All the following endpoints should support `search` and `start` GET query parameters for search and
pagination functionality.

Endpoint                                                            | Description
--------------------------------------------------------------------|------------------------------------
`/wp-json/avorg/v1/books`                                           |
`/wp-json/avorg/v1/conferences`                                     |
`/wp-json/avorg/v1/playlists`                                       |
`/wp-json/avorg/v1/presenters`                                      |
`/wp-json/avorg/v1/series`                                          |
`/wp-json/avorg/v1/sponsors`                                        |
`/wp-json/avorg/v1/stories`                                         |
`/wp-json/avorg/v1/topics`                                          |
`/wp-json/avorg/v1/user/playlists`                                  |


## Testing

```bash
composer test
```

## Working With Translation Files

Install gettext on your mac:

```bash
brew install gettext
brew link --force gettext
```

Convert a PO language file to an MO language file:

```bash
msgfmt -o wp-avorg-plugin-es_ES.mo wp-avorg-plugin-es_ES.po
```

File names:

- `{textdomain}-{languagecode}.po`
- `{textdomain}-{languagecode}.mo`

Pluralized translations should appear in a language's `.po` file in the following format:

```po
msgid "%1$d day ago"
msgid_plural "%1$d days ago"
msgstr[0] "Hace %1$d días"
msgstr[1] "Hace %1$d días"
```

More information on plugin localization:

- [Everything You Need to Know About Translating WordPress Plugins](https://premium.wpmudev.org/blog/translating-wordpress-plugins/)