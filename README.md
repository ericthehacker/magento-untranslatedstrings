# EW_UntranslatedStrings

This is a WIP module to optionally log untranslated strings as they are discovered by locale.

## Installation

Install via [modman](https://github.com/colinmollenhour/modman):

```
$ cd <magento root>
$ modman init # if you've never used modman before
$ modman clone https://github.com/ericthehacker/magento-untranslatedstrings.git
```

## Configuration

Visit *System -> Config -> Developer -> Translate Inline -> Log Untranslated Strings* and set it to Yes to enable untranslated string logging. When pages are rendered, any string missing translations for the current locale is logged.

To log results from more than one locale at a time, enable *System -> Config -> Developer -> Translate Inline -> Batch Check Translation Locales*, then select some locales in the multiselect below. With this enabled, when a page is rendered, each translated string is checked against each of the selected locales and logged individually if there is a translation gap.

## Usage

After enabling the functionality in the system config as stated above, any time a string is run through the translater but no translation for the selected locale(s) is found, the string along with other useful information will be logged.

To see a report of this log, visit *Reports -> Untranslated Strings -> Untranslated Strings Report* in the Magento admin. This report shows all logged untranslated strings and allows you to filter, sort, and export.

## Caveat

Currently, the module introduces a small to moderate preformance penalty, depending on the number of locales you have configured to check. However, this penalty is realized **only if enabled in system configuration**. That means that it should be safe to have installed on both production and stage, but you probably don't want to enable it on production all the time. 

Note that it's disabled by default.
