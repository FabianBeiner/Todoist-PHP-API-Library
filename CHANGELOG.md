# Change Log
All notable changes to this project will be documented in this file.

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
