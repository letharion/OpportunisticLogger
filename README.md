### Opinionated wrapper around monolog.

This project wraps [Monolog][1], or any other [PSR-3][2] compliant logging library, to output logging in a certain format every time.

### Features
#### UUIDs for log messages
A uuid for every message ensures that even if the wording of a message changes, or the logged message is translated, every unique instance of the same event can still be easily traced.

   [1]: https://github.com/Seldaek/monolog
   [2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
