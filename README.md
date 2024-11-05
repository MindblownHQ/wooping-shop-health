# Wooping Shop Health

Shop Health is a free tool for Woocommerce that helps you increase your sales with action-minded checklists. 
Extend your Woocommerce shop with pre-launch, post-launch, and in-flight checklists to make sure you're always ready and up-to-date.

Shop Health uses Action Scheduler and runs all analysis on the background, without disturbing your store. 
Start your scan by visiting the Shop Health dashboard page?

### Install Shop Health 
1. Go to wooping.io/shop-health
2. Click the download button
3. Go to Plugins -> Add new
4. Click the Upload Plugin button
5. Drag or select the zip file to install Shop Health

### Install Shop Health using composer
1. `composer config repositories.wooping composer https://composer.wooping.io`
2. `composer require wooping/shop-health`

### WP CLI
Shop Health also works with WP-CLI, below you'll find a few of our commands

_wp shop-health schedule_

- `wp shop-health schedule setting_scan <setting_slug>` _`(example: has_terms_and_conditions)`_
- `wp shop-health schedule product_scan <id>` _`(example: 12)`_
- `wp shop-health schedule all_product_scans`
- `wp shop-health schedule all_setting_scans`
- `wp shop-health schedule all`

_wp shop-health run_

- `wp shop-health run setting_scan <setting_slug>` _`(example: has_terms_and_conditions)`_
- `wp shop-health run product_scan <id>` _`(example: 12)`_
- `wp shop-health run all_product_scans`
- `wp shop-health run all_setting_scans`
- `wp shop-health run all`

_wp shop-health clean_

- `wp shop-health clean issues`
- `wp shop-health clean objects`
- `wp shop-health clean actions`
- `wp shop-health clean all`

### How to install for contributing
1. Clone the repository: `git@github.com:MindblownHQ/wooping-shop-health.git`
2. Run `composer install` for installing composer dependencies
3. Run `npm install` to install node dependencies
4. And finally, run `npm run watch` to watch for SCSS and JS changes or `npm run production` for building the production assetsInstal
