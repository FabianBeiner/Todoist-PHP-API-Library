# Changelog
All notable changes to this project get documented in this file. _Unless I forget it. Sorry._

## 1.1.0 - 2021-01-09
### Changed
- Updated depenencies (Guzzle & PHPUnit) to latest versions. _(Thanks, @vdhicts)_

## 1.0.1 - 2020-11-22
### Changed
- Reintroduced the possibility to pass on Guzzle Configuration as a third parameter to the constructor (`new TodoistClient(string $apiToken, string $languageCode = 'en', array $guzzleConf = [])`).

## 1.0.0 - 2020-01-02
**This version is finally compatible with every available endpoint of the official REST API. Please check out the Wiki for more information on the methods, and many examples.**

### Changed
- Heavily updated the code. This might have introduced a few breaking changes, but I am currently not sure. 🤷‍
- Improved almost everything, and added the Section endpoints.

## 0.8.2 - 2020-01-01
### Changed
- `getAllTasks` has now an optional ID for a project.
- Updated Composer packages.

## 0.8.1 - 2019-11-28
### Fixed
- There was a typo in the endpoint of the reopen method. 
### Changed
- Some coding style changes, updated *.md & Dot files etc.
- Updated Composer packages.

## 0.8.0 - 2019-07-19
- _Changelog missing. Sorry!_

## 0.7.1 - 2018-05-29
### Fixed
- Updated some calls to use right method.

## 0.7.0 - 2018-03-22
### Changed
- Merged latest version from @balazscsaba2006.
- Updated some coding style settings.

## 0.6.0 - 2018-03-06
### Changed
- Improved the code quality overall.
- Added a helper function from @balazscsaba2006.
- Enabled GZIP.
- Requires PHP 7.x.
- Some more smaller improvements.

## 0.5.0 - 2018-03-02
### Changed
- Improved the code of old Traits.
- Added `TodoistProjectsTrait.php` from @balazscsaba2006.
- Implemented all the Projects endpoints.

## 0.4.0 - 2017-11-14
### Added
- Implemented all the Comments endpoints.

## 0.3.1 - 2017-11-11
### Changed
- Removed ramsey/uuid.

## 0.3.0 - 2017-11-11
### Added
- Implemented all the Labels endpoints.

## 0.2.0 - 2017-11-11
### Changed
- Yeah, I rewrote this thingy.
- Implemented all the Projects endpoints.

## 0.1.0 - 2014-12-21
### Added
- Started to code this library.
- Implemented loginUser() to login a user.
- Aliased loginUser() as getUserDetails(), because that’s what it does. ;)
- Implemented testToken() to check if the internally set token is valid.
- Implemented static getTimezones(), which returns all the different time zones Todoist supports.
- Implemented static registerUser() to register a user.
- Implemented static deleteUser() to delete a user. But be warned: This API(!) is buggy, because there is currently no way to check if the call really work, as the API returns “ok” all the time.
