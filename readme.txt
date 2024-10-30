=== Hide Old Posts ===
Contributors: zaantar
Tags: hide, filter, old, posts
Donate link: http://zaantar.eu/index.php?page=Donate
Requires at least: 3.2
Tested up to: 3.3.1
Stable tag: 1.2.1

Hides posts older than given amount of time. 

== Description ==

Hides posts older than given date or amount of time relative to present (see `(http://php.net/manual/en/function.strtotime.php strtotime)`). 

You can also choose a capability - users who have this capability will then see all posts. And if you enter `none`, even admins will be excluded.

Uses `posts_where` and `getarchives_where` filters.

Developed for private use, but has perspective for more extensive usage. I can't guarantee any support in the future nor further development, but it is to be expected. Kindly inform me about bugs, if you find any, or propose new features: zaantar@zaantar.eu.

== Installation ==

Install as usual. After activation setup via Options --> Hide Old Posts.

== Frequently Asked Questions ==

No questions yet.

== Changelog ==

= 1.2.1 =
* fix: checkbox for hide_posts_only setting instead of text input field
* new option: show singular content: If checked, old posts or pages can be viewed through their url, but will not be listed anywhere
* minor settings page changes

= 1.2 =
* added pot file and czech translation

= 1.1 =
* Added option to hide only posts and keep other old content (pages, attachments etc.) visible.

= 1.0 =
* First version, basic functionality.
