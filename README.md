![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/FabianBeiner/Todoist-PHP-API-Library?style=flat-square) [![GitHub stars](https://img.shields.io/github/stars/FabianBeiner/Todoist-PHP-API-Library?style=flat-square)](https://github.com/FabianBeiner/Todoist-PHP-API-Library/stargazers) [![GitHub forks](https://img.shields.io/github/forks/FabianBeiner/Todoist-PHP-API-Library?style=flat-square)](https://github.com/FabianBeiner/Todoist-PHP-API-Library/network) [![GitHub issues](https://img.shields.io/github/issues/FabianBeiner/Todoist-PHP-API-Library?style=flat-square)](https://github.com/FabianBeiner/Todoist-PHP-API-Library/issues) [![GitHub license](https://img.shields.io/github/license/FabianBeiner/Todoist-PHP-API-Library?style=flat-square)](https://github.com/FabianBeiner/Todoist-PHP-API-Library/blob/master/LICENSE) ![Codacy grade](https://img.shields.io/codacy/grade/a8cad853a2b041a896753b4dda5659ad?style=flat-square) [![StyleCI Badge](https://styleci.io/repos/28313097/shield?style=flat-square)](https://styleci.io/repos/28313097/)

# PHP Client for Todoist

**This repository contains a PHP client library that provides a native interface to the official 
[Todoist REST API (v2)](https://developer.todoist.com/rest/v2/).**

## Requirements

- [PHP](https://php.net/): >=8.0
- [Guzzle](https://github.com/guzzle/guzzle): ^7.5

## Installation

The recommended way is using **[Composer](https://getcomposer.org/)**. If you don’t have Composer installed, follow the [installation instructions](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos).

Once you have installed Composer, execute the following command in your project root to install this library:

```shell
composer require fabian-beiner/todoist-php-api-library
```

Finally, include the autoloader in your project:

```php
require __DIR__ . '/vendor/autoload.php';
```

## Usage

```php
$Todoist = new FabianBeiner\Todoist\TodoistClient('YOUR_API_TOKEN');
```

[Please look at the Wiki of this project.](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki) It contains a list of all available methods and related usage examples.

## Obtain your API token

[Click here](https://todoist.com/app/settings/integrations) to find your API token at the bottom of that page.

If the link doesn’t work, open the [Todoist web app](https://todoist.com/app/), click on your profile image/icon, select “Settings,” then “Integrations.”

## Changelog

👉 [CHANGELOG.md](https://github.com/FabianBeiner/Todoist-PHP-API-Library/blob/master/CHANGELOG.md)

## Contributing
I’d be happy if you contributed to this library. Please follow the existing coding style and use proper comments in your commit message. Thanks! 🙇

## License

👉 [LICENSE](https://github.com/FabianBeiner/Todoist-PHP-API-Library/blob/master/LICENSE)

## Disclaimer

The project is not created by, affiliated with, or supported by Doist. 😢
