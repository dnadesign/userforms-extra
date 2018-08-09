# Userforms Extra

## Introduction

This a colllection of extensions for User Definied Forms:
- Allow to restrict allowed file extensions on EditableFielField
- Allow to not attach uploaded files to emails generated after submission

## Requirements
* [silverstripe/framework](https://github.com/silverstripe/framework)
* [silverstripe/userforms](https://github.com/silverstripe/silverstripe-userforms)

## Install
Add the following to your composer.json file

```

    "require"          : {
		"dnadesign/userforms-extra": "0.1"
	}

```
## Configuration

### Allowed File Extensions

By default, EditableFielField restrict the file types to the ones allowed by `Config::inst()->get('File', 'allowed_extensions'`. With this extension, these file types can be overriden.
Just edit the EditableFieldField and enter the list of allowed extensions.

### Email File Attachement

By default, with this extension, files are not attached to email. To attach them, edit an email recipient and tick the `Attach Files to Email` in the `Email Content` tab.