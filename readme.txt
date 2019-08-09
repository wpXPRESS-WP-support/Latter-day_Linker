=== Latter-day Linker ===
Contributors: hiren1612, wordxpress1, thefiddler, joeyday
Donate link: https://wordx.press
Tags: scriptures, jesus christ, christian, mormon, lds
Requires at least: 4.9
Tested up to: 5.3
Stable tag: 3.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically changes any restored gospel scripture reference into a link to the online scriptures on ChurchofJesusChrist.org.

== Description ==

Latter-day Linker (formerly "LDS Linker") is a WordPress plugin that automagically changes any properly-formatted scripture reference into a link pointing to that passage at the ChurchOfJesusChrist.org online scripture library. It recognizes references whether the book name is written out or shortened using the [standard abbreviations](https://www.churchofjesuschrist.org/study/scriptures/quad/quad/abbreviations).

Updated and maintainted by [WordXpress](https://wordx.press) WordPress support and maintenance. Need help with your WP website? We'd love to help.

= Features =

* Automagically change a scripture reference such as "Moroni 10:3-5" into a link like this: [Moroni 10:3–5](https://www.churchofjesuschrist.org/study/scriptures/moro/10/3-5#3).
* Control whether the scripture links open in a new window or the same window.
* Prevent the linking of any passage by preceding it with an exclamation mark, ie. !Moroni 10:3-5. The exclamation mark will not show up in the published blog post. This is mostly useful when you inadvertently trigger a scripture link where you don’t want one, as in “the Omni 2 digital camera is an improvement over the popular Omni 1.”
* Disable linking of all Bible references so as not to conflict with other Bible linking plugins.

== Screenshots ==

1. Latter-day Linker settings page.
2. Unlinked references in the post editor.
3. Automatically linked references in the published post.

== Installation ==

From WP Admin:

1. Go to Plugins > Add New
2. Search "Latter-day Linker"
3. Click the "Install Now" button, followed by the "Activate" button

Now set your options under Settings > Latter-day Linker.

== Frequently Asked Questions ==

= Why isn’t the plugin working? =

Are you spelling book names correctly or using the universally recognized
[standard abbreviations](https://www.churchofjesuschrist.org/study/scriptures/quad/quad/abbreviations)? Latter-day Linker
won’t work unless you do.

= I’m spelling everything right, but it’s still not working =

Does the specific issue you’re experiencing have to do with a D&C reference, or a reference to Joseph Smith—History or Joseph Smith—Matthew, or are you using en-dashes in verse ranges? If so, you may be running into a character encoding problem. There are half a dozen ways of typing ampersands, en-, and em-dashes.

I’ve tried my darndest to make sure Latter-day Linker recognizes them all, but I’m bound to have missed one. When in doubt, simply use `&` for ampersands, `--` for en-dashes, and `---` for em-dashes, or try typing the HTML character entity codes, i.e., `&amp;` for ampersands, `&ndash;` for en-dashes, and `&mdash;` for em-dashes.

It may also be worth taking a look at how your database is character encoding the text it stores. You can learn more about general issues with WordPress and database character collation at [WordPress Codex › Converting Database Character
Sets](http://codex.wordpress.org/Converting_Database_Character_Sets).

= I’m spelling everything right and I don't think it’s an encoding issue =

Have you got another plugin activated that modifies the content of your posts or comments in any way? Latter-day Linker is known to not play well with code prettification plugins like Markdown or Textile, and may have problems withother plugins that modify post or comment content in any way.

= My question’s not here. How can I contact the developer? =

Use the (support forums)[https://wordpress.org/support/plugin/lds-linker/#new-post) on WP.org for plugin support. For general WordPress support or other questions, use the WordXpress [contact form](https://wordx.press/contact/). Cheers!

== Changelog ==

= What's new in 3.0 =

* Fix: works properly with the new Block Editor (Gutenberg).
* Fix: works with PHP 5.6 - 7.3.
* Fix: links to www.churchofjesuschrist.org/study/scriptures/ instead of scriptures.lds.org.
* Enhancement: new name!

= What's new in 2.5 =

* Added option to have passage links open in a new window.
* You can now use an exclamation mark to cancel the linking of any reference.
* Added support for using en-dashes in verse ranges instead of hyphens.
  Additionally, the plugin now replaces all hyphens with en-dashes in the final output of link text.
* Squashed a bunch of bugs with passage search mode and various ways of encoding ampersands, en-, and em-dashes (for verse ranges, references to D&C, and references to Joseph Smith—Matthew and Joseph Smith—History).

= What’s new in 2.1 =

* There’s a shiny new *Settings* page located within the administration panel. You no longer need to set options by editing the plugin file directly.

= What’s new in 2.0 =

* The plugin now does a direct passage lookup instead of a passage search by default. You can still set it to do the passage search if you liked that better.
* You can now reference Articles of Faith without including the chapter number. One exception is that if you want to reference a range of articles of faith, say 1–5 for instance, you still have to include the chapter, as in: Articles of Faith 1:1–5.
* You can now put a space after the colon and the plugin will still recognize the reference. I had one person request this feature because that’s how he likes to format his scripture references.
* Lots of bug fixes.

= What’s new in 1.4 =

* Finally squashed the D&C ampersand bug for good using the `urlencode()`
  function.

= What’s new in 1.3 =

* The LDS Church made significant changes to the way the passage search method worked on their website, so this update fixes the plugin to work with the new website.
* A known issue in this version is those flippin’ D&C references are broken again.

= What’s new in 1.2.1 =

* Fixed links to 4 Nephi and the Official Declarations.

= What’s new in 1.2 = 

* Really fixed the issue with D&C passage references. Seems there are yet *more* ways to encode ampersands. Pesky little buggers.

= What’s new in 1.1 =

* Fixed an issue with D&C passage references. Seems there are many more ways than I realized to encode an ampersand, and the plugin wasn’t recognizing some of them.

= What’s new in 1.0 =

* Initial release.
