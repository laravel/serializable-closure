# Release Notes

## [Unreleased](https://github.com/laravel/serializable-closure/compare/v2.0.3...2.x)

## [v2.0.3](https://github.com/laravel/serializable-closure/compare/v2.0.2...v2.0.3) - 2025-02-11

* Fix unable to serialize PHP8.4 object with virtual property by [@crynobone](https://github.com/crynobone) in https://github.com/laravel/serializable-closure/pull/108

## [v2.0.2](https://github.com/laravel/serializable-closure/compare/v2.0.1...v2.0.2) - 2025-01-24

* Supports Laravel 12 by [@crynobone](https://github.com/crynobone) in https://github.com/laravel/serializable-closure/pull/105

## [v2.0.1](https://github.com/laravel/serializable-closure/compare/v2.0.0...v2.0.1) - 2024-12-16

* Fix broken code for ternary operator with class instantiation with omitted parentheses by [@panzer-punk](https://github.com/panzer-punk) in https://github.com/laravel/serializable-closure/pull/102

## [v2.0.0](https://github.com/laravel/serializable-closure/compare/v1.3.6...v2.0.0) - 2024-11-19

* [2.x] Fix namespaced closures being considered first class callables by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/laravel/serializable-closure/pull/97
* [2.x] Cleanup phpunit.xml by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/serializable-closure/pull/95
* [2.x] PHP 8.4 support by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/serializable-closure/pull/90
* [2.x] Supports PHPStan 2 by [@crynobone](https://github.com/crynobone) in https://github.com/laravel/serializable-closure/pull/100
* Prepare `2.0.0` release by [@crynobone](https://github.com/crynobone) in https://github.com/laravel/serializable-closure/pull/99

## [v1.3.6](https://github.com/laravel/serializable-closure/compare/v1.3.5...v1.3.6) - 2024-11-11

* Fix repeated word "the" in code comment by [@caendesilva](https://github.com/caendesilva) in https://github.com/laravel/serializable-closure/pull/98

## [v1.3.5](https://github.com/laravel/serializable-closure/compare/v1.3.4...v1.3.5) - 2024-09-23

* CI Improvements by [@crynobone](https://github.com/crynobone) in https://github.com/laravel/serializable-closure/pull/94

## [v1.3.4](https://github.com/laravel/serializable-closure/compare/v1.3.3...v1.3.4) - 2024-08-02

* [1.x] Adds tests regarding carbon instances by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/laravel/serializable-closure/pull/82
* Fix bug related to readonly properties by [@rust17](https://github.com/rust17) in https://github.com/laravel/serializable-closure/pull/87

## [v1.3.3](https://github.com/laravel/serializable-closure/compare/v1.3.2...v1.3.3) - 2023-11-08

- Fixes switch cases namespace resolution by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/laravel/serializable-closure/pull/80

## [v1.3.2](https://github.com/laravel/serializable-closure/compare/v1.3.1...v1.3.2) - 2023-10-17

- Fixes FQCN on anonymous classes definition  by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/laravel/serializable-closure/pull/75

## [v1.3.1](https://github.com/laravel/serializable-closure/compare/v1.3.0...v1.3.1) - 2023-07-14

- Fixes namespace resolution on named arguments by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/laravel/serializable-closure/pull/69

## [v1.3.0](https://github.com/laravel/serializable-closure/compare/v1.2.2...v1.3.0) - 2023-01-30

### Changed

- Add support for specifying if it should sign by @olivernybroe in https://github.com/laravel/serializable-closure/pull/62
- Fixes and tests unsigned closures by @nunomaduro in https://github.com/laravel/serializable-closure/pull/64

## [v1.2.2](https://github.com/laravel/serializable-closure/compare/v1.2.1...v1.2.2) - 2022-09-08

### Changed

- Adds PHP 8.2 Support by @driesvints in https://github.com/laravel/serializable-closure/pull/57

## [v1.2.1](https://github.com/laravel/serializable-closure/compare/v1.2.0...v1.2.1) - 2022-08-26

### Fixed

- Fixes serialization of date carbon objects by @nunomaduro in https://github.com/laravel/serializable-closure/pull/56

## [v1.2.0](https://github.com/laravel/serializable-closure/compare/v1.1.1...v1.2.0) - 2022-05-16

### Added

- Adds Function Attributes support by @nunomaduro in https://github.com/laravel/serializable-closure/pull/46
- Adds support for closure inside context with enum property by @ksassnowski in https://github.com/laravel/serializable-closure/pull/47

## [v1.1.1](https://github.com/laravel/serializable-closure/compare/v1.1.0...v1.1.1) - 2022-02-15

### Fixed

- Fixes first class callables namespaces by @nunomaduro in https://github.com/laravel/serializable-closure/pull/39

## [v1.1.0](https://github.com/laravel/serializable-closure/compare/v1.0.5...v1.1.0) - 2022-02-01

### Changed

- Adds support for first class callable syntax ([#33](https://github.com/laravel/serializable-closure/pull/33))

## [v1.0.5 (2020-11-30)](https://github.com/laravel/serializable-closure/compare/v1.0.4...v1.0.5)

### Fixed

- Fixes serialisation of closures with named arguments code ([#29](https://github.com/laravel/serializable-closure/pull/29))

## [v1.0.4 (2020-11-16)](https://github.com/laravel/serializable-closure/compare/v1.0.3...v1.0.4)

### Fixed

- Fixes the serialization of Enum objects ([#28](https://github.com/laravel/serializable-closure/pull/28))

## [v1.0.3 (2020-10-07)](https://github.com/laravel/serializable-closure/compare/v1.0.2...v1.0.3)

### Fixed

- Possible stream protocol collision with `opis/closure` ([#23](https://github.com/laravel/serializable-closure/pull/23))

## [v1.0.2 (2020-09-29)](https://github.com/laravel/serializable-closure/compare/v1.0.1...v1.0.2)

### Fixed

- Fixes serialization of closures that got rebound ([#19](https://github.com/laravel/serializable-closure/pull/19))

## [v1.0.1 (2020-09-29)](https://github.com/laravel/serializable-closure/compare/v1.0.0...v1.0.1)

### Fixed

- Fixes null safe operator with properties ([#16](https://github.com/laravel/serializable-closure/pull/16))

## v1.0.0 (2020-09-14)

Initial release
