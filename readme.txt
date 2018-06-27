=== LDS Linker ===
Contributors: joeyday
Donate link: http://nickels.joeyday.com
Tags: LDS, scriptures
Requires at least: 2.0
Tested up to: 2.8.4
Stable tag: 2.5

Automagically changes any Latter-day Saint scripture reference into a hyperlink
pointing to the passage at the Internet Edition of the LDS Scriptures.

== Description ==

LDS Linker is a WordPress plugin that automagically changes any Latter-day Saint
scripture reference into a hyperlink pointing to the appropriate passage at the
Internet Edition of the LDS Scriptures. It recognizes references whether the
book name is written out or shortened using the
[standard abbreviations](http://scriptures.lds.org/helps/abbrvtns).

= Features =

* You can choose two different ways of linking to LDS Standard Works passages.
  To understand the difference, compare these two links: (1) direct lookup:
  [Moroni 10:3–5](http://scriptures.lds.org/en/moro/10/3-5#3), and (2) passage
  search: [Moroni 10:3–5](http://scriptures.lds.org/search?search=moro+10%3A3-5).
* You can control whether the scripture links open in a new window or the same
  window.
* You can prevent the linking of any passage by preceding it with an exclamation
  mark, ie. !Moroni 10:3-5. The exclamation mark will not show up in the
  published blog post. This is mostly useful when you inadvertently trigger a
  scripture link where you don’t want one, as in “the Omni 2 digital camera is
  an improvement over the popular Omni 1.”
* You can optionally disable linking of all Bible references so as not to
  conflict with other Bible linking plugins such as the excellent
  [Holy Scripturizer](http://scripturizer.wordpress.org).

= Known issues =

* Does not play well with many code prettification plugins like Markdown,
  Textile, etc.

= Updates =

If you’re using the latest version of WordPress, you’ll receive notifications of
available updates directly in the admin interface, and you can install them
there automatically.

If you’d like to receive email notifications of updates, let me know using my
[contact form](http://www.joeyday.com/contact) and I’ll add you to the mailing
list. The list also announces betas and release candidates for those who’d like
to help test new versions of the plugin before they are generally available.

= Feedback =

Are *you* using LDS Linker? Have any great ideas for new features? Let me know
using my [contact form](http://www.joeyday.com/contact). Cheers!

== Installation ==

To install the plugin:

1. Place the `ldslinker.php` file in your plugins folder (usually
   `wp-content/plugins`). 
2. Activate the plugin via the “Plugins” menu.
3. You’re done!

Note there are a few options that can be set from within the administration
panel. Go to *Settings* › *LDS Linker* to find them.

== Frequently Asked Questions ==

= Why isn’t the plugin working? =

Are you spelling book names correctly or using the universally recognized
[standard abbreviations](http://scriptures.lds.org/helps/abbrvtns)? LDS Linker
won’t work unless you do.

= I’m spelling everything right, but it’s still not working =

Does the specific issue you’re experiencing have to do with a D&C reference, or
a reference to Joseph Smith—History or Joseph Smith—Matthew, or are you using
en-dashes in verse ranges? If so, you may be running into a character encoding
problem. There are half a dozen ways of typing ampersands, en-, and em-dashes.
I’ve tried my darndest to make sure LDS Linker recognizes them all, but I’m
bound to have missed one. When in doubt, simply use `&` for ampersands, `--` for en-dashes, and `---` for em-dashes, or try typing the HTML character entity
codes, i.e., `&amp;` for ampersands, `&ndash;` for en-dashes, and `&mdash;` for
em-dashes.

It may also be worth taking a look at how your database is character encoding
the text it stores. You can learn more about general issues with WordPress and
database character collation at
[WordPress Codex › Converting Database Character
Sets](http://codex.wordpress.org/Converting_Database_Character_Sets).

= I’m spelling everything right and I don't think it’s an encoding issue =

Have you got another plugin activated that modifies the content of your posts or
comments in any way? LDS Linker is known to not play well with code
prettification plugins like Markdown or Textile, and may have problems with
other plugins that modify post or comment content in any way.

= My question’s not here. How can I contact the developer? =

You’re welcome to use my [contact form](http://www.joeyday.com/contact). Cheers!

== Changelog ==

= What's new in 2.5 =

* Added option to have passage links open in a new window.
* You can now use an exclamation mark to cancel the linking of any reference.
* Added support for using en-dashes in verse ranges instead of hyphens.
  Additionally, the plugin now replaces all hyphens with en-dashes in the final
  output of link text.
* Squashed a bunch of bugs with passage search mode and various ways of encoding
  ampersands, en-, and em-dashes (for verse ranges, references to D&C, and
  references to Joseph Smith—Matthew and Joseph Smith—History).

= What’s new in 2.1 =

* There’s a shiny new *Settings* page located within the administration panel.
  You no longer need to set options by editing the plugin file directly.

= What’s new in 2.0 =

* The plugin now does a direct passage lookup instead of a passage search by
  default. You can still set it to do the passage search if you liked that
  better.
* You can now reference Articles of Faith without including the chapter number.
  One exception is that if you want to reference a range of articles of faith,
  say 1–5 for instance, you still have to include the chapter, as in: Articles
  of Faith 1:1–5.
* You can now put a space after the colon and the plugin will still recognize
  the reference. I had one person request this feature because that’s how he
  likes to format his scripture references.
* Lots of bug fixes.

= What’s new in 1.4 =

* Finally squashed the D&C ampersand bug for good using the `urlencode()`
  function.

= What’s new in 1.3 =

* The LDS Church made significant changes to the way the passage search method
  worked on their website, so this update fixes the plugin to work with the new
  website.
* A known issue in this version is those flippin’ D&C references are broken
  again.

= What’s new in 1.2.1 =

* Fixed links to 4 Nephi and the Official Declarations.

= What’s new in 1.2 = 

* Really fixed the issue with D&C passage references. Seems there are yet *more*
  ways to encode ampersands. Pesky little buggers.

= What’s new in 1.1 =

* Fixed an issue with D&C passage references. Seems there are many more ways
  than I realized to encode an ampersand, and the plugin wasn’t recognizing some
  of them.

= What’s new in 1.0 =

* Initial release.