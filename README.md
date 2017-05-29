# Turiknox URL Rewrite Importer

## Overview

A simple Magento module that will allow you to import custom URL rewrites into the admin using a CSV file.

## Requirements

Magento 2.1.x

## Installationn

Copy the contents of the module into your Magento root directory.

Enable the module via the command line:

/path/to/php bin/magento module:enable Turiknox_Urlrewriteimporter

Run the database upgrade via the command line:

/path/to/php bin/magento setup:upgrade

Run the compile command and refresh the Magento cache:

/path/to/php bin/magento setup:di:compile /path/to/php bin/magento cache:clean

## Usage

Marketing -> URL Rewrites -> Import

## Additional

This is a port from the Magento 1 version of this extension.

The UrlRewriteFactory class is used to save the rewrites. Even though the save() method is deprecated, as of Magento version 2.1.5, there is no Urlrewrite API repository to use. I would also like to use the form UI component in future.
