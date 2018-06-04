[![GitHub tag](https://img.shields.io/github/tag/FabianBeiner/Todoist-PHP-API-Library.svg)](https://github.com/FabianBeiner/Todoist-PHP-API-Library/tags) * [![GitHub stars](https://img.shields.io/github/stars/FabianBeiner/Todoist-PHP-API-Library.svg)](https://github.com/FabianBeiner/Todoist-PHP-API-Library/stargazers) * [![GitHub issues](https://img.shields.io/github/issues/FabianBeiner/Todoist-PHP-API-Library.svg)](https://github.com/FabianBeiner/Todoist-PHP-API-Library/issues) * [![GitHub license](https://img.shields.io/github/license/FabianBeiner/Todoist-PHP-API-Library.svg)](https://github.com/FabianBeiner/Todoist-PHP-API-Library/blob/master/LICENSE) * [![Codacy Badge](https://api.codacy.com/project/badge/Grade/a8cad853a2b041a896753b4dda5659ad)](https://www.codacy.com/app/FabianBeiner/Todoist-PHP-API-Library?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=FabianBeiner/Todoist-PHP-API-Library&amp;utm_campaign=Badge_Grade) * [![StyleCI Badge](https://styleci.io/repos/28313097/shield)](https://styleci.io/repos/28313097/)

# PHP Client for Todoist

**This repository contains a PHP client library that provides a native interface to the official 
[Todoist REST API (v8)](https://developer.todoist.com/rest/v8/).**

*The project is not created by, affiliated with, or supported by Doist.*

## Requirements

- [PHP](https://secure.php.net/): >= 7.0
- [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle): 6.3.x

## Installation

The recommended way is using **[Composer](https://getcomposer.org/)**. You also can **[download the latest release](https://github.com/FabianBeiner/Todoist-PHP-API-Library/releases)** and 
start from there.

### Composer

If you don’t have Composer installed, follow the [installation instructions](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

Once composer is installed, execute the following command in your project root to install this library:

```sh
composer require fabian-beiner/todoist-php-api-library
```

Finally, remember to include the autoloader to your project:

```php
require __DIR__ . '/vendor/autoload.php';
```

## Obtain your personal API token

[Click here](https://todoist.com/Users/viewPrefs?page=integrations). Your API token is listed at the bottom of this page.

If the link doesn’t work, open the [Todoist web app](https://todoist.com/app), click on the gear icon ![gear icon image](https://user-images.githubusercontent.com/86269/40932241-0257ed7e-682e-11e8-8ad6-06b41dec7155.png)
, select “Settings,” then “Integrations.”

## Usage

```php
$Todoist = new FabianBeiner\Todoist\TodoistClient('YOUR_API_TOKEN');
```

## Methods & Examples

### [“Projects” methods and examples](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Projects#projects-methods-and-examples)

* [Get all projects](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Projects#get-all-projects)
* [Create a new project](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Projects#create-a-new-project)
* [Get a project](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Projects#get-a-project)
* [Update a project](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Projects#update-actually-rename-a-project)
* [Delete a project](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Projects#delete-a-project)

### [“Tasks” methods and examples](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Tasks)

* [Get tasks](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Tasks)
* [Create a new task](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Tasks)
* [Get a task](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Tasks)
* [Update a task](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Tasks)
* [Close a task](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Tasks)
* [Reopen a task](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Tasks)
* [Delete a task](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Tasks)

### [“Comments” methods and examples](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Comments#comments-methods-and-examples)

* [Get all comments](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Comments#get-all-comments)
* [Create a new comment](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Comments#create-a-new-comment)
* [Get a comment](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Comments#get-a-comment)
* [Update a comment](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Comments#update-a-comment)
* [Delete a comment](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Comments#delete-a-comment)

### [“Labels” methods and examples](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Labels#labels-methods-and-examples)

* [Get all labels](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Labels#get-all-labels)
* [Create a new label](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Labels#create-a-new-label)
* [Get a label](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Labels#get-a-label)
* [Update a label](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Labels#update-actually-rename-a-label)
* [Delete a label](https://github.com/FabianBeiner/Todoist-PHP-API-Library/wiki/Methods:-Labels#delete-a-label)

## Contributing
I’d be happy if you contribute to this library. Please try to follow the existing coding style and use proper comments in your commit message. Thanks! 🙇 

## License

Please see the [license file](https://github.com/FabianBeiner/Todoist-PHP-API-Library/blob/master/LICENSE) for more information.
