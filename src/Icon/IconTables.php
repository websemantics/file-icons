<?php namespace Websemantics\FileIcons\Icon;

/**
 * IconTables: Interface providing access to the icons's databases.
 *
 * A port of Atom File-icons, https://github.com/file-icons/atom
 *
 * @link      https://github.com/file-icons/atom
 * @author    Daniel Brooker, <dan@nocturnalcode.com>
 * @author    Adnan M.Sagar, <adnan@websemantics.ca>
 */

class IconTables
  {
      /** @var Array, Icons to match directory-type resources */
      protected $directoryIcons;

      /** @var Array, Icons to match file resources */
      protected $fileIcons;

      /** @var Icon, Icon for binary files */
      protected $binaryIcon;

      /** @var Icon, Icon for executables */
      protected $executableIcon;

      /** @var Array, Cahce for Icons */
      protected $cache = [
        'directoryName' => [],
        'directoryPath' => [],
        'fileName' =>      [],
        'filePath' =>      [],
        'interpreter' =>   [],
        'scope' =>         [],
        'language' =>      [],
        'signature' =>     []
      ];

      /**
       * Construct
       *
       * @param {Array}   icondb - icons data
       */
      public function __construct($icondb)
      {
        $this->directoryIcons = $this->read($icondb[0]);
      	$this->fileIcons      = $this->read($icondb[1]);
      	$this->binaryIcon     = $this->matchScope("source.asm");
      	$this->executableIcon = $this->matchInterpreter("bash");
      }

      /**
       * Test regular expression pattern
    	 *
       * @param {RegExp} pattern
     	 * @return {String} subject
    	 * @return {Boolean}
    	 */
      protected function test($pattern, $subject)
      {
        if(($p = substr($pattern, strlen($pattern) - 2)) === '/g'){
          return @preg_match_all("$p/", $subject);
        }
        return @preg_match($pattern, $subject);
      }

      /**
       * Populate icon-lists from a icons data table.
    	 *
       * @param {Array} table
     	 * @return {Object}
    	 * @return {Array}
    	 */
      public function read($table)
      {
        $icons = $table[0];
        $indexes = $table[1];

        array_walk($icons, function(&$icon, $index) { $icon = new Icon($index, $icon); });

        // Dereference Icon instances from their stored offset
        $indexes = array_map(function ($index) use ($icons){
          return array_map(function($offset) use ($icons) { return $icons[$offset]; }, $index);
        }, $indexes);

        return [
          'byName' => $icons,
          'byInterpreter' => $indexes[0],
          'byLanguage' => $indexes[1],
          'byPath' => $indexes[2],
          'byScope' => $indexes[3],
          'bySignature' => $indexes[4]
        ];
      }

    	/**
    	 * Match an icon using a resource's basename.
    	 *
    	 * @param {String} name - Name of filesystem entity
    	 * @param {Boolean} [directory=false] - Match folders instead of files
    	 * @return {Icon}
    	 */
    	public function matchName($name, $directory = false) {

        $cachedIcons = $directory ? $this->cache['directoryName'] : $this->cache['fileName'];
        $icons = $directory ? $this->directoryIcons['byName'] : $this->fileIcons['byName'];

        if(isset($cachedIcons[$name])) {
          return $cachedIcons[$name];
        }

        foreach ($icons as $icon) {
          if($this->test($icon->match, $name)){
            	return $cachedIcons[$name] = $icon;
          }
        }
        return null;
      }

    	/**
       * Match an icon using a resource's system path.
       *
       * @param {String} path - Full pathname to check
       * @param {Boolean} [directory=false] - Match folders instead of files
       * @return {Icon}
       */
    	public function matchPath($path, $directory = false) {

        $cachedIcons = $directory ? $this->cache['directoryName'] : $this->cache['fileName'];
        $$icons = $directory ? $this->directoryIcons['byPath'] : $this->fileIcons['byPath'];

        if(isset($cachedIcons[$name])) {
          return $cachedIcons[$path];
        }

        foreach ($icons as $icon) {
          if($this->test($icon->match, $path)){
            	return $cachedIcons[$path] = $icon;
          }
        }
        return null;
      }

    	/**
       * Match an icon using the human-readable form of its related language.
       *
       * Typically used for matching modelines and Linguist-language attributes.
       *
       * @example IconTables.matchLanguage("JavaScript")
       * @param {String} name - Name/alias of language
       * @return {Icon}
       */
    	public function matchLanguage($name) {

        if(isset($this->cache['language'][$name])) {
          return $this->cache['language'][$name];
        }

        foreach ($this->fileIcons['byLanguage'] as $icon) {
          if($this->test($icon->lang, $name)){
            	return $cachedIcons[$name] = $icon;
          }
        }
        return null;
      }

    	/**
       * Match an icon using the grammar-scope assigned to it.
       *
       * @example IconTables.matchScope("source.js")
       * @param {String} name
       * @return {Icon}
       */
    	public function matchScope($name) {

        if(isset($this->cache['scope'][$name])) {
          return $this->cache['scope'][$name];
        }

        foreach ($this->fileIcons['byScope'] as $icon) {
          if($this->test($icon->scope, $name)){
            	return $this->cache['scope'][$name] = $icon;
          }
        }
        return null;
      }

    	/**
       * Match an icon using the name of an interpreter which executes its language.
       *
       * Used for matching interpreter directives (a.k.a., "hashbangs").
       *
       * @example IconTables.matchInterpreter("bash")
       * @param {String} name
       * @return {Icon}
       */
    	public function matchInterpreter($name) {

        if(isset($this->cache['interpreter'][$name])) {
          return $this->cache['interpreter'][$name];
        }

        foreach ($this->fileIcons['byInterpreter'] as $icon) {
          if($this->test($icon->interpreter, $name)){
            return $this->cache['interpreter'][$name] = $icon;
          }
        }
        return null;
      }

      	/**
         * Match an icon using a resource's file signature.
         *
         * @example IconTables.matchSignature("\x1F\x8B")
         * @param {String} data
         * @return {Icon}
         */
      	public function matchSignature($data) {}

    }
