[![GitHub tag](https://img.shields.io/github/tag/FabianBeiner/Todoist-PHP-API-Library.svg)](https://github.com/FabianBeiner/Todoist-PHP-API-Library/tags) * [![GitHub stars](https://img.shields.io/github/stars/FabianBeiner/Todoist-PHP-API-Library.svg)](https://github.com/FabianBeiner/Todoist-PHP-API-Library/stargazers) * [![GitHub issues](https://img.shields.io/github/issues/FabianBeiner/Todoist-PHP-API-Library.svg)](https://github.com/FabianBeiner/Todoist-PHP-API-Library/issues) * [![GitHub license](https://img.shields.io/github/license/FabianBeiner/Todoist-PHP-API-Library.svg)](https://github.com/FabianBeiner/Todoist-PHP-API-Library/blob/master/LICENSE) * [![Codacy Badge](https://api.codacy.com/project/badge/Grade/a8cad853a2b041a896753b4dda5659ad)](https://www.codacy.com/app/FabianBeiner/Todoist-PHP-API-Library?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=FabianBeiner/Todoist-PHP-API-Library&amp;utm_campaign=Badge_Grade) * [![StyleCI Badge](https://styleci.io/repos/28313097/shield)](https://styleci.io/repos/28313097/)

# PHP Client for Todoist

**This repository contains a PHP client library that provides a native interface to the official 
[Todoist REST API (v1)](https://developer.todoist.com/rest/v1/).**

## Requirements

- [PHP](https://php.net/): >=7.2
- [Guzzle](https://github.com/guzzle/guzzle): ~6.5

## Installation

The recommended way is using **[Composer](https://getcomposer.org/)**. If you don’t have Composer installed, follow the [install instructions](hhttps://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos).

Once Composer is installed, execute the following command in your project root to install this library:

```sh
composer require fabian-beiner/todoist-php-api-library
```

Finally, include the autoloader to your project:

```php
require __DIR__ . '/vendor/autoload.php';
```

## Usage

```php
$Todoist = new FabianBeiner\Todoist\TodoistClient('YOUR_API_TOKEN');
```

[Please have a look at the Wiki of this project.](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki) It contains a list of all available methods and related usage examples.

## Obtain your API token

[Click here](https://todoist.com/prefs/integrations) to find your API token at the bottom of that page.

If the link doesn’t work, open the [Todoist web app](https://todoist.com/app), click on the gear icon ![gear icon image](.github/gear-icon.png), select “Settings,” then “Integrations.”

## Changelog

👉 [CHANGELOG.md](https://github.com/FabianBeiner/Todoist-PHP-API-Library/blob/master/CHANGELOG.md)

## Contributing
I’d be happy if you contribute to this library. Please try to follow the existing coding style and use proper comments in your commit message. Thanks! 🙇 

## License

👉 [LICENSE](https://github.com/FabianBeiner/Todoist-PHP-API-Library/blob/master/LICENSE)

## Disclaimer

The project is not created by, affiliated with, or supported by Doist.
