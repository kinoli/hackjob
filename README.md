# Hack Job plugin for [Craft CMS 3.x](http://craftcms.com/)

This extension adds a Twig filter for CraftCMS templates to refine fields of entries.

Inspired by the Craft 2 [Prune](https://github.com/mattstauffer/craftcms-prune) plugin. This is mostly a port of that wonderful plugin.

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require /hack-job

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Hack Job.

## Hack Job Overview

The primary reason for this extension is to control the fields being output to `json_encode`. It is very useful for generating a json output from twig template data. If you need an api for consuming externally, or even internally via Javascript frameworks such as Vue.js, React or Angular with Axios or other http libraries, this will make it easy for you.

## Using Hack Job

This template will get all entries from the "news" section, grab just the title and body fields from each, and then output it to JSON.

```twig
{{ craft.entries.section('news').find() | hackjob(['title', 'body']) | json_encode() | raw }}
```

This template will get output a json array of all selected users and display the user fields and custom fields associated with their profile.

```twig
{% set users = craft.users.group('members').all() %}
{{ users | hackjob(['id', 'username', 'name', 'email', 'photoUrl', 'customFieldOne', 'customFieldTwo']) | json_encode() | raw }}
```

Brought to you by [Jesse Knowles](http://www.jesseknowles.com)
