<?php
/*
Plugin Name: Latter-day Linker
Plugin URI: https://wordx.press
Description: Automagically changes any scripture reference into a hyperlink pointing to the passage at churchofjesuschrist.org.
Author: WordXpress
Version: 3.0
Author URI: https://wordx.press
Copyright: © WordXpress
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: lds-linker
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
exit;
}


/**
 * Namespace for LDSLinker.
 */
class LDSLinker {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Actions
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
    add_action( 'plugins_loaded', array( $this, 'init' ), 0 );
    add_action( 'admin_menu', array( $this, 'admin_settings_menu' ) );

    add_filter('the_content', array( $this, 'ldslinkify_callback'), 10);
    add_filter('comment_text', array( $this, 'ldslinkify_callback'), 10);

	}


  # If 'add_filter' function exists, then we assume that we are initialised as
  # a WordPress plugin. We will try to create a new global scope function, and
  # add it to filter post and comment content.
  public function ldslinkify_callback($content){
    return $this->ldslinkify($content);
  }

	/**
	 * Init localisations and hook
	 */
	public function init() {
		// Localisation
		load_plugin_textdomain( 'lds-linker', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Add relevant links to plugins page
	 * @param  array $links
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'options-general.php?page=latter-day-linker-settings' ) . '">' . __( 'Settings', 'lds-linker' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );
	}


  /**
	 * Add admin settings menu
	 */
	public function admin_settings_menu() {
    add_options_page('LDS Linker Options', 'Latter-day Linker', 'manage_options', 'latter-day-linker-settings', array(	$this,	'ldslinker_options_subpanel'));
	}


  public function ldslinker_options_subpanel() {
      if ( isset($_POST['lds_linker_info_update']) ) {
          if(isset($_POST['ldslinker_open_links_in_new_window'])){
  		        update_option('ldslinker_open_links_in_new_window', sanitize_text_field($_POST['ldslinker_open_links_in_new_window']));
          }else{
              update_option('ldslinker_open_links_in_new_window', '');
          }
          if(isset($_POST['ldslinker_include_bible_refs'])){
            update_option('ldslinker_include_bible_refs', sanitize_text_field($_POST['ldslinker_include_bible_refs']));
          }else{
            update_option('ldslinker_include_bible_refs', '');
          }
          ?><div class="updated"><p><strong><?php
              _e('Options saved.', 'lds-linker')
          ?></strong></p></div><?php
      } ?>
      <div class="wrap">
      	<div id="icon-options-general" class="icon32"><br /></div>
          <h2>Latter-day Linker Settings</h2>
          <form method="post">
              <?php # Get values from options table in database.
              $include_bible_refs = get_option('ldslinker_include_bible_refs');
              $open_links_in_new_window = get_option('ldslinker_open_links_in_new_window'); ?>
              <table class="form-table">
                  <tr valign="top">
                      <th scope="row">
                      	<label>URL construction method</label>
                      </th>
                      <td> Direct lookup
                          <br /><span class="description">Bring up passages on churchofjesuschrist.org. For example : <a href="https://www.churchofjesuschrist.org/study/scriptures/bofm/1-ne/3.7#7" target="_new2">1 Nephi 3:7</a>.</span>
                      </td>
                  </tr>
                  <tr valign="top">
                      <th scope="row">Link behavior</th>
                      <td>
                          <label for="ldslinker_open_links_in_new_window">
                          	<input type="checkbox" name="ldslinker_open_links_in_new_window" id="ldslinker_open_links_in_new_window"<?php if ($open_links_in_new_window) echo ' checked="checked"'; ?> />
                          	Open scripture passage links in a new window
                          </label>
                      </td>
                  </tr>
                  <tr valign="top">
                      <th scope="row">Holy Bible references</th>
                      <td>
                          <label for="ldslinker_include_bible_refs">
                          	<input type="checkbox" name="ldslinker_include_bible_refs" id="ldslinker_include_bible_refs"<?php if ($include_bible_refs) echo ' checked="checked"'; ?> />
                          	Linkify Holy Bible references
                          </label>
                          <br /><span class="description">You may want to uncheck this if you have another plugin already linkifying Bible references.</span>
                      </td>
                  </tr>
              </table>
              <div class="submit">
              <input type="submit" class="button-primary" name="lds_linker_info_update" value="<?php
                  _e('Save Changes', 'lds-linker')
              ?>" /></div>
          </form>
      </div><?php
  }
  # END ADMIN CONSOLE

  /**
	 * Install default data
	 */
	static function lds_plugin_install(){
    # Add some default options if they don't already exist.
    // update_option('ldslinker_url_style', 'direct'); #'Style for LDS scripture URLs. Possible values are: direct (Direct lookup), or search (Passage search)'
        update_option('ldslinker_include_bible_refs', TRUE); # 'Linkify references to the Holy Bible.'
        update_option('ldslinker_open_links_in_new_window', FALSE); #'Open scripture reference links in a new window.'
        update_option('ldslinker_language', 'en'); #'Language to use when linking to LDS scripture passages. Possible values are: de (Deutsch), en (English), es (Español), fr (Français), or it (Italiano).'
	}

  /**
   * Hyperlink Latter-day Saint Standard Works references in text.
   *
   * @param data String containing the text.
   * @param callback A function object to be used to replace scripture
   *     references with hyperlinks.
   * @return Linkified text.
   */
  function addLink($data, $callback) {

      # A volume number between 1 and 4 (for books like 1 John or 4 Nephi)
      $volumes = '[1-4]\s?';

      # Names of books either written out in full or abbreviated using the
      # standard abbreviations found at: http://scriptures.lds.org/en/helps/abbrvtns
      $books = '';
      $books .= 'Nephi|Ne\.?|Jacob|Enos|Jarom|Omni|Words\sof\sMormon|W\.?\sof\sM\.?|'.
          'Mosiah|Alma|Helaman|Hel\.?|Mormon|Morm\.?|Ether|Moroni|Moro\.?|'.
          'Doctrine(?:\sand\s|\s?(?:&|&amp;|&\#0*38;|&\#x0*26;)\s?)Covenants|D\s?(?:and|&|&amp;|&\#0*38;|&\#x0*26;)\s?C|'.
          'Joseph\sSmith(?:\s|-|–|—|&[nm]dash;|&\#0*15[01];|&\#0*821[12];|&\#x0*201[34];)(?:Matthew|History)|' .
          'JS(?:\s|-|–|—|&[nm]dash;|&\#0*15[01];|&\#0*821[12];|&\#x0*201[34];)?[MH]|' .
          'Abraham|Abr\.?|Moses|Official\sDeclaration|O\.?\s?D\.?|Articles?\sof\sFaith|A\.?\sof\sF\.?';
          
      # Bible references come last so that Joseph Smith—Matthew will match before Matthew
      if ( get_option('ldslinker_include_bible_refs') ) {
          $books .= '|Genesis|Gen\.?|Exodus|Ex\.?|Leviticus|Lev\.?|'.
              'Numbers|Num\.?|Deuteronomy|Deut\.?|'.
              'Joshua|Josh?\.?|Judges|Judg\.?|Ruth|Samuel|Sam\.?|'.
              'Kings|Kgs\.?|Chronicles|Chr\.?|Ezra|'.
              'Nehemiah|Neh\.?|Esther|Esth\.?|Job|'.
              'Psalms?|Ps\.?|Proverbs?|Prov\.?|Ecclesiastes|Eccl\.?|'.
              'Song of Solomon|Song\.?|Isaiah|Isa\.?|Jeremiah|Jer\.?|'.
              'Lamentations|Lam\.?|Ezekiel|Ezek\.?|Daniel|'.
              'Dan\.?|Hosea|Joel|Amos|Obadiah|Obad\.?|Jonah|'.
              'Micah|Nahum|Habakkuk|Hab\.?|Zephaniah|'.
              'Zeph\.?|Haggai|Hag\.?|Zechariah|Zech\.?|Malachi|Mal\.?|'.
              'Matthew|Matt\.?|Mark|Luke|John|Jn\.?|'.
              'Acts|Romans|Rom\.?|Corinthians|Cor\.?|'.
              'Galatians|Gal\.?|Ephesians|Eph\.?|'.
              'Philippians|Philip\.?|Colossians|Col\.?|Thessalonians|'.
              'Thes\.?|Timothy|Tim\.?|Titus|Philemon|Philem\.?|'.
              'Hebrews|Heb\.?|James|Peter|Pet\.?|Jude|'.
              'Revelation|Rev\.?';

      }

      # The chapter: 1 or more digits followed possibly by a colon and a space.
      $chapter = '\d+(?::\s)?:?';

      # The verse(s): 1 or more digits followed possibly by a hyphen or an en-dash and 1 or more digits.
      $verses  = '\d+(?:\s?(?:-|–|&\#0*150;|&\#0*8211;|&\#x0*2013;|&ndash;)\s?\d+)?';

      # String all the above regexes together to form the entire passage regex.
      # Notice that $verses is used recursively to catch comma separated verse
      # ranges in the same chapter, such as 1 Nephi 11:1-2, 15.
      return preg_replace_callback("/(!)?(?:($volumes))?($books)(?=\s)(?:\s($chapter))($verses(?:\s?,\s?$verses)*)?/mx", $callback, $data);

  }

  /**
   * Callback function for replacing scripture references in the text with
   * HTML hyperlinks.
   *
   * @param match Regex matching result array.
   * @return A shiny new hyperlink.
   */
  function addLinkCallback($match) {

    $die = $match[1];
    $vol = $match[2];
    $bok = $match[3];
    $chp = $match[4];
    $ver = $match[5];

    # Save a cleaned up copy of the complete passage before we mangle the variables.

    # Replace hyphens and variously encoded en- and em-dashes in book name with character entity em-dashes
    # Replace variously encoded ampersands in book name with character entity ampersands
    $cleanBok = preg_replace( array ( '/Smith(?:\s|-|–|—|&[nm]dash;|&\#0*15[01];|&\#0*821[12];|&\#x0*201[34];)/',
                                      '/S(?:-|–|—|&[nm]dash;|&\#0*15[01];|&\#0*821[12];|&\#x0*201[34];)?(H|M)/',
                                      '/&(?:amp;|\#0*38;|\#x0*26;)?(?![nm#])/' ),
                        array ( 'Smith&mdash;', 'S-\1', '&amp;' ),
                        $bok);
    # Replace hyphens and variously encoded en-dashes in verse range with character entity en-dashes
    if ($ver)
      $cleanVer = preg_replace('/(?:-|–|&\#0*150;|&\#0*8211;|&\#x0*2013;)/', '&ndash;', $ver);
    $psg = $this->xmlentities(($vol ? "$vol" : "").$cleanBok.($chp ? " $chp" : "").($ver ? "$cleanVer" : ""));


    # If the user put an exclamation mark on the front of the reference, pass back the passage with no link.
    if ($die) return $psg;

    # Prepare the first part of the link. This is the same whether we're using
    # passage search or direct lookup.
    $link = 'https://www.churchofjesuschrist.org/study/scriptures/';

    # This allows linking directly to an Article of Faith without including the
    # chapter number.
    if ((preg_match('/Articles?\sof\sFaith/', $bok) || preg_match('/A\.?\sof\sF\.?/', $bok)) && !$ver) {
        $ver = $chp;
        $chp = '1:';
    }


    # If there's a volume number, remove any whitespace and tack an underscore to the end of it.
    if ($vol) {
      $vol = preg_replace('/\s/', '', $vol);
      $vol .= '-';
    }

    # Trim hyphens, en-dashes, em-dashes, whitespace, dots, and ampersands from book
    # and translate all to lowercase.
    $bok = strtolower(preg_replace('/(?:\s|\.|-|–|—|&(?:[nm]dash;|\#0*15[01];|\#0*821[12];|\#x0*201[34]|amp;|\#0*38;|\#x0*26;)?)/', '', $bok));

    # If the book isn't abbreviated, we need to abbreviate it before we tack
    # it onto the link string. We start by creating an array of book names
    # and their abbreviations.
    $abbr = array(
      'ot' => array(
                  'genesis' => 'gen',             'exodus' => 'ex',                'leviticus' => 'lev',
                  'numbers' => 'num',             'deuteronomy' => 'deut',         'joshua' => 'josh',
                  'judges' => 'judg',             'samuel' => 'sam',               'kings' => 'kgs',
                  'chronicles' => 'chr',          'nehemiah' => 'neh',             'esther' => 'esth',
                  'psalms' => 'ps',               'proverbs' => 'prov',            'ecclesiastes' => 'eccl',
                  'songofsolomon' => 'song',      'isaiah' => 'isa',               'jeremiah' => 'jer',
                  'lamentations' => 'lam',        'ezekiel' => 'ezek',             'daniel' => 'dan',
                  'obadiah' => 'obad',            'habakkuk' => 'hab',             'zephaniah' => 'zeph',
                  'haggai' => 'hag',              'zechariah' => 'zech',           'malachi' => 'mal',
                  'ezra' => 'ezra',               'job' => 'job',                  'hosea' => 'hosea',
                  'joel' => 'joel',               'amos' => 'amos',                'jonah' => 'jonah',
                  'micah' => 'micah',             'nahum' => 'nahum'
              ),
      'nt' => array(
                  'matthew' => 'matt',            'romans' => 'rom',               'corinthians' => 'cor',
                  'galatians' => 'gal',           'ephesians' => 'eph',            'philippians' => 'philip',
                  'colossians' => 'col',          'thessalonians' => 'thes',       'timothy' => 'tim',
                  'philemon' => 'philem',         'hebrews' => 'heb',              'peter' => 'pet',
                  'revelation' => 'rev',          'mark' => 'mark',                'luke' => 'luke',
                  'john' => 'jn',                 'acts' => 'acts',                'jude' => 'jude',
                  'jn' => 'john'
              ),
      'bofm' => array(
                  'nephi' => 'ne',                'wordsofmormon' => 'w_of_m',     'wofm' => 'w_of_m',
                  'helaman' => 'hel',             'mormon' => 'morm',              'moroni' => 'moro',
                  'jacob' => 'jacob',             'enos' => 'enos',                'jarom' => 'jarom',
                  'omni' => 'omni',               'mosiah' => 'mosiah',            'alma' => 'alma',
                  'ether' => 'ether'
              ),
      'dc-testament' => array(
                  'doctrineandcovenants' => 'dc', 'doctrinecovenants' => 'dc',     'officialdeclaration' => 'od'
              ),
      'pgp' => array(
                  'josephsmithmatthew' => 'js_m', 'jsm' => 'js_m',                 'josephsmithhistory' => 'js_h',
                  'jsh' => 'js_h',                'abraham' => 'abr',              'articlesoffaith' => 'a_of_f',
                  'articleoffaith' => 'a_of_f',   'aoff' => 'a_of_f'
              ),
    );

    # Get books parent abbreviations
    $parent_abbr = "";
    foreach($abbr as $key => $value) {
      foreach ($value as $bok_name => $boks_abbr) {
        if($boks_abbr == $bok) {
          $bok = $boks_abbr;
          $parent_abbr = $key;
        }
      }
    }

    # Trim possible colon and whitespace from end of chapter.
    $chp = preg_replace(array('/:/', '/\s/'), array('', ''), $chp);

    # URL-encode colons, commas, and en-dashes and trim superfluous whitespace from verse(s).
    $ver = preg_replace(array('/:/', '/,/', '/(?:–|&ndash;|&\#0*150;|&\#0*8211;|&\#x0*2013;)/', '/\s*/'),
                        array('%3A', '%2C', '-', ''),
                        $ver);

    # Figure out which verse is first in the set of verses so we can use it as
    # an anchor in the URL.
    preg_match('/\d+/', $ver, $result);
    $anc = $result[0];

    # Smoosh all this together to produce the final URL.
    $link .= $parent_abbr."/".$vol.$bok.($chp ? "/$chp" : "").($ver ? ".$ver" : "").($anc ? "#$anc" : "");

    # Pass back the shiny new hyperlink.
    return "<a href=\"$link\" title=\"LDS Scriptures Internet Edition: $psg\"" . ( get_option('ldslinker_open_links_in_new_window') ? " target=\"_$vol$bok$chp$ver\"" : "" ) . ">$psg</a>";
  }


  /**
   * Linkify a text string.
   *
   * @param text Original text to be linkified.
   * @return Linkified text.
   */
  function ldslinkify($text) {

      $callback = function($match){
        return $this->addLinkCallback($match);
      };
      
      # We don't want to linkify passages that are already hyperlinked
      # or surrounded in <pre> or <code> tags, so we strip those out by
      # splitting the string each time we encounter one of those.
      $text = preg_split("/(<a href.*?<\/a>|<code.*?<\/code>|<pre.*?<\/pre>|<.+?>)/ms", $text, -1, PREG_SPLIT_DELIM_CAPTURE);

      # Build a new array composed of text chunks by parsing each element of
      # the array we just built above. This is where we call the code that
      # actually creates the scripture passage links.
      $newtext = array();
      foreach ($text as $chunk) {
          if (!preg_match('/<.*>/', $chunk)) {
              $chunk = $this->addLink($chunk, $callback);
          }
          $newtext[] = $chunk;
      }

      # Attach all the array elements back together into a single
      # string and return the new string.
      return implode('', $newtext);
  }

  /**
   * Function to encode entities. Works like htmlentities but a little less sloppy.
   * I lifted this from an anonymous comment at http://www.php.net/htmlentities.
   * I mostly needed it so D&C references wouldn't show up as D&amp;C.
   *
   * @param string String containing text to me encoded
   * @param quote_style Flag to determine style of quotes
   * @return Encoded text
   */
  function xmlentities($string, $quote_style=ENT_QUOTES) {
     static $trans;
     if (!isset($trans)) {
         $trans = get_html_translation_table(HTML_ENTITIES, $quote_style);
         foreach ($trans as $key => $value)
             $trans[$key] = '&#'.ord($key).';';  # don't translate the '&' in case it is part of &xxx;
         $trans[chr(38)] = '&';
     }
     # after the initial translation, _do_ map standalone '&' into '&amp;'
     return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/","&amp;" , strtr($string, $trans));
  }


}

$lds_linker = new LDSLinker();

register_activation_hook( __FILE__, array( 'LDSLinker', 'lds_plugin_install' ) );
