# Suggest categories

Include categories in search suggestions offered by catalog/suggest HTML client
component and extends the catalog manager to allow searching/sorting by relevance
within the catalog label.

**Caution:** The catalog label can NOT be translated into different languages!

## Installation

Use composer to install the extension:

```
composer req aimeos/ai-catsuggest
```

Afterwards, update the database to add the required full text index:

* Laravel: `php artisan aimeos:setup`
* TYPO3: `php vendor/bin/typo3 aimeos:setup`
