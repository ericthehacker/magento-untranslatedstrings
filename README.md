# EW_UntranslatedStrings

This is a WIP module to optionally log untranslated strings as they are discovered by locale.

## Installation

Install via modman:

```
$ cd <magento root>
$ modman init # if you've never used modman before
$ modman clone https://github.com/ericthehacker/magento-untranslatedstrings.git
```

## Configuration

Visit System -> Config -> Developer -> Translate Inline -> Log Untranslated Strings and set it to Yes to enable untranslated string logging. When pages are rendered, any page missing translations for the current locale are logged.

To log results from more than one locale at a time, enable System -> Config -> Developer -> Translate Inline -> Batch Check Translation Locales, then select some locales in the multiselect below. With this enabled, when a page is rendered, each translated string is checked against each of the selected locales and logged individually if there is a translation gap.

## Expected Results

Currently, the module only collects untranslated strings into the table `ew_untranslatedstrings_strings`. If I have more time I'll add a proper Magento report with filter and export capabilities -- see the issues for some of my ideas. 

## Caveat

Currently, the module introduces a preformance penalty, **only if enabled in system configuration**. That means, it should be safe to have installed on both production and stage, but you probably don't want to enable it on production all the time. Note that it's disabled by default.
