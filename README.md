# wp-avorg

[![latest build | master](https://img.shields.io/badge/latest%20build-master-75d60d)](https://wp-avorg-plugin-master.surge.sh/wp-avorg-plugin.zip)
[![latest build | dev](https://img.shields.io/badge/latest%20build-dev-f39f37)](https://wp-avorg-plugin-dev.surge.sh/wp-avorg-plugin.zip)

PHP 7.2

## Installation

### Development

0. `git clone https://github.com/AVORG/wp-avorg-dev.git`
0. Follow [`wp-avorg-dev` readme instructions](https://github.com/AVORG/wp-avorg-dev)
0. Optional: Update dependencies: `cd wp-avorg-dev/wp-avorg-plugin && composer install`

If you edit the Composer auto-load rules, you'll need to run `composer dump-autoload` to make them go into effect.

### Production

0. [Open CircleCI](https://circleci.com/gh/avorg/wp-avorg-plugin) and select the latest build
0. Select the "Artifacts" tab
0. Drill down until you see a file named `wp-avorg-plugin.zip` and click it to download
0. In the WordPress admin panel, navigate to "Plugins > Add New" and click the "Upload Plugin" button
0. Upload the zip file you downloaded
0. Activate the plugin

## Shortcodes

### Content Bits Shortcode

The content bits shortcode is included in a page or post with the following code:

```
[avorg-bits id=identifierString]
```

Identifiers could be `mediaSidebar`, `homeFooter`, `smallAds`, or any other string that makes sense as a way to 
tie the shortcode instance to a collection of content bits. The identifier may be unique, but it doesn't need to be.

Once you've placed the shortcode in a page or post, you'll need to create one or more pieces of content in the Content
Bits page in the admin interface that specify the previously-used id in the "Identifier" meta box.

- If there are no pieces of content that are associated with the used identifier, the shortcode will output nothing.
- If there is one or more piece of content associated with the identifier, one will be chosen at random to be displayed.
- If there is a piece of content associated with the identifier and also associated with one or more media IDs, a
  content bits shortcode using that identifier on the page for one of those media items will display one of the pieces
  of content explicitly associated with both that media item's ID and the shortcode identifier.

### List Shortcode

Place the following usages in the content of a page or a post to retrieve a list of recent, featured, or popular 
recordings.

Usage                        | Result
-----------------------------|--------------------------------
`[avorg-list]`               | List of recent recordings
`[avorg-list list=featured]` | List of featured recordings
`[avorg-list list=popular]`  | List of popular recordings

## API

Endpoint                                                            | Description
--------------------------------------------------------------------|------------------------------------
`/wp-json/avorg/v1/placeholder-ids`                                 | Retrieve placeholder identifiers

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