<p align="center">
<a href="https://craftcms.com/" target="_blank"><img width="312" height="90" src="https://craftcms.com/craftcms.svg" alt="Craft CMS"></a>
</p>

## About dadamotion/craft

This is an alternate scaffolding package for Craft 3 CMS projects to Pixel & Tonic's canonical [craftcms/craft](https://github.com/craftcms/craft) package based on [nystudio107/craft](https://github.com/nystudio107/craft) and [marbles/craft](https://github.com/marbles/craft).

In addition to setting up a new Craft 3 CMS project, this project sets up:

* [Craft 3 Multi-Environment](https://github.com/nystudio107/craft3-multi-environment) as described in the [Multi-Environment Config for Craft CMS](https://nystudio107.com/blog/multi-environment-config-for-craft-cms) article
* [Craft-Scripts](https://github.com/nystudio107/craft-scripts) as described in the [Database & Asset Syncing Between Environments in Craft CMS](https://nystudio107.com/blog/database-asset-syncing-between-environments-in-craft-cms), [Mitigating Disaster via Website Backups](https://nystudio107.com/blog/mitigating-disaster-via-website-backups) & [Hardening Craft CMS Permissions](https://nystudio107.com/blog/hardening-craft-cms-permissions) articles

...and sets up some other base scaffolding as described to the following articles:

* [A Better package.json for the Frontend](https://nystudio107.com/blog/a-better-package-json-for-the-frontend)
* Frontend Development Automation using Laravel Mix
* [Implementing Critical CSS on your website](https://nystudio107.com/blog/implementing-critical-css)
* [Enhancing a Craft CMS 3 Website with a Custom Module](https://nystudio107.com/blog/enhancing-a-craft-cms-3-website-with-a-custom-module)

It also installs a few base plugins that are used on every project. You can read more about it in the [Setting up a New Craft 3 CMS Project](https://nystudio107.com/blog/enhancing-a-craft-cms-3-website-with-a-custom-module) article.

## Assumptions Made

Since this is boilerplate that DADA motion uses for projects, it is by definition opinionated, and has a number of assumptions:

* Laravel Mix is used as a the frontend workflow automation tool
* [Vue](https://github.com/vuejs/vue) is used as the frontend JavaScript framework, with [Axios](https://github.com/axios/axios) providing the http client
* [Tailwind CSS](https://tailwindcss.com/docs/what-is-tailwind) is used as the utility-first CSS framework
* Critical CSS is used site-wide
* FontFaceObserver is used for font loading
* Craft-Scripts are used for db/asset synching
* Craft 3 Multi-Environment is used for the Craft 3 multi-environment setup

Obviously you're free to remove whatever components you don't need/want to use.

## Using dadamotion/craft

Requirements:

	node, homebrew, valet, MySQL, composer

This project package works exactly the way Pixel & Tonic's [craftcms/craft](https://github.com/craftcms/craft) package works; you create a new project by first creating & installing the project:

    composer create-project dadamotion/craft craft3

Make sure that `PATH` is the path to your project, including the name you want for the project, e.g.:

    composer create-project dadamotion/craft PATH

Then `cd` to your new project directory, and run Craft's `setup` console command to create your `.env` environments and optionally install:

    cd PATH
    ./craft setup

Finally, run the `dada-setup` command to configure Craft-Scripts & Craft 3 Multi-Environment based on your newly created `.env` settings:

    ./dada-setup

And last but not least:

	Import the databank found in your project folder (craft.sql)


That's it, enjoy!

If you ever delete the `vendor` folder or such, just re-run:

    ./dada-setup

...and it will re-create the symlink to your `.env.sh`; don't worry, it won't stomp on any changes you've made.

Below is the entire intact, unmodified `README.md` from Pixel & Tonic's [craftcms/craft](https://github.com/craftcms/craft):

.....

## About Craft CMS

Craft is a content-first CMS that aims to make life enjoyable for developers and content managers alike. It is optimized for bespoke web and application development, offering developers a clean slate to build out exactly what they want, rather than wrestling with a theme.

Learn more about Craft at [craftcms.com](https://craftcms.com).

## How to Install Craft 3 Beta

Installation instructions can be found in the [Craft 3 documentation](https://github.com/craftcms/docs/blob/master/en/installation.md).

## BrowserSync over a https proxy

To make BrowserSync work when you have a secured localhost e.g. `valet secure` go to the Chrome settings use the following `chrome://flags/#allow-insecure-localhost` and enable _Allow invalid certificates for resources loaded from localhost.

## Install puppeteer to be able to run critical css (forge)

On a Forge provisioned Ubuntu 16.04 server you can install the latest stable version of Chrome like this:

```
curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
sudo apt-get install -y nodejs gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libappindicator1 libnss3 lsb-release xdg-utils wget
sudo npm install --global --unsafe-perm puppeteer
sudo chmod -R o+rx /usr/lib/node_modules/puppeteer/.local-chromium
```

## Resources

#### Official Resources
- [Craft 3 Documentation](https://github.com/craftcms/docs)
- [Craft 3 Plugins](https://github.com/craftcms/plugins)
- [Demo site](https://demo.craftcms.com/)
- [Craft Slack](https://craftcms.com/community#slack)
- [Craft CMS Stack Exchange](http://craftcms.stackexchange.com/)

#### Community Resources
- [Mijingo](https://mijingo.com/craft) – Video courses and other learning resources
- [Envato Tuts+](https://webdesign.tutsplus.com/categories/craft-cms/courses) – Video courses
- [Straight Up Craft](http://straightupcraft.com/) – Articles, tutorials, and more
- [Craft Cookbook](https://craftcookbook.net/) – Quick answers for common tasks
- [pluginfactory.io](https://pluginfactory.io/) – Craft plugin scaffold generator