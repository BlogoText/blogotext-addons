# Add(ons for [BlogoText]()

[![Build Status](https://travis-ci.org/BoboTiG/blogotext-addons.svg?branch=master)](https://travis-ci.org/BoboTiG/blogotext-addons)

---

This is the officiale repository for BlogoText addons. Few rules apply here:


### Rule 1

One addon by folder. See `calendar` one for inspiration.

### Rule 2

Write all in english.

### Rule 3

Before spreading the world with your addon, make sure it is PSR-2 compliant.
You can download this [useful tool](https://github.com/squizlabs/PHP_CodeSniffer) to help you:

    curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar
    php phpcs.phar --standard=PSR2 -np --extensions=php --tab-width=4 --encoding=utf-8 --colors "<path to the addon>/<addon>.php"

Example with this calendar addon:

    php phpcs.phar --standard=PSR2 -np --extensions=php --tab-width=4 --encoding=utf-8 --colors calendar/

### Rule 4

That's enough! Enjoy and good luck :)
