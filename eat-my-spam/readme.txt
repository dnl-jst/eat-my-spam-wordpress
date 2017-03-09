=== EatMySpam ===
Contributors: danieljost
Tags: spam, comments, antispam
Requires at least: 4.7
Tested up to: 4.7.3
Stable tag: 0.4.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0

This plugin allows you to use the free EatMySpam public API to check your WordPress comments for spam.

== Description ==

This plugin allows you to use the free EatMySpam public API to check your WordPress comments for spam. Tested to be compatible with PHP 7.0 and PHP 7.1.

== Installation ==

* Put the plugin file in your plugin directory and activate it in your WP backend.

== Changelog ==

= 0.6.0 =

* add add more information to spam notification mail
* check compatibility with PHP 7.0 and PHP 7.1
* report ham and spam classification to EatMySpam api (can be disabled in settings)

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