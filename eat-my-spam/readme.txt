=== EatMySpam ===
Contributors: danieljost
Tags: spam, comments, antispam, anti-spam, anti spam, comment spam, contact form 7, contact form spam, askimet alternative
Requires at least: 4.7
Tested up to: 4.7.5
Stable tag: 0.7.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0

EatMySpam checks your comments and Contact Form 7 submissions against the EatMySpam web service to classify if it is spam or not.

== Description ==

EatMySpam checks your comments and Contact Form 7 submissions against the EatMySpam web service to classify if it is spam or not.

Main features of this plugin:

* Automatically checks your comments against the EatMySpam web service to classify if they are spam or not.
* Comments classfied as spam are marked as spam in the comment system and can be unspammed.
* Contact Form 7 integration: checks Contact Form 7 submissions against the EatMySpam web service. (can be disabled)
* User classifications are also reported to the EatMySpam web service. (can be disabled)
* Optional feature allows you to delete comment spam directly.
* Tested to be compatible with PHP 7.0 and PHP 7.1.
* Unlike other plugins EatMySpam is completely free for both personal and commercial use.

Check out the web service at: https://www.eat-my-spam.de

== Installation ==

* Upload the plugin to your plugin directory and activate it in your WP backend.

== Screenshots ==

1. Settings page for the EatMySpam plugin.

== Changelog ==

= 0.7.3 =

* reduce timeout for eat my spam requests

= 0.7.2 =

* clarify settings description and order
* cleanup plugin options on uninstall

= 0.7.1 =

* clarify description in readme.txt that plugin uses a web service to classify spam
* clarify features in readme.txt

= 0.7.0 =

* add first version of contact-form-7 integration

= 0.6.3 =

* internal changes

= 0.6.2 =

* bump version

= 0.6.1 =

* fix typos in plugin metadata

= 0.6.0 =

* add add more information to spam notification mail
* check compatibility with PHP 7.0 and PHP 7.1
* report ham and spam classification to EatMySpam api (can be disabled in settings)
* do not include wp-admin links in notification mail, see https://core.trac.wordpress.org/ticket/40081

= 0.5.0 =

* add possibility to translate plugin
* add translations for de_DE

= 0.4.1 =

* fix in_array warnings on settings page

= 0.4.0 =

* Spam messages are tagged as spam be default, but there is an option to delete those messages directly on settings page.
* New option to send spam notification emails to site admin if a message is considered spam.

= 0.3.1 =

* Unify plugin name.

= 0.3.0 =

* Add threshold parameter on settings page where custom spam threshold can be set.

= 0.2.0 =

* Add settings page where rulesets can be excluded.

= 0.1.0 =

* First version of the Eat My Spam! WordPress plugin