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

Visit System -> Config -> Developer -> Translate Inline -> Log Untranslated Strings and set it to Yes.

## Expected Results

Currently, the module only collects untranslated strings into the table `ew_untranslatedstrings_strings`. If I have more time I'll add a proper Magento report with filter and export capabilities -- see the issues for some of my ideas. 
