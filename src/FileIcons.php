<?php namespace Websemantics\FileIcons;

use Websemantics\FileIcons\Icon\Icon;
use Websemantics\FileIcons\Icon\IconTables;

/**
 * FileIcons
 *
 * File specific icons for PHP.
 *
 * @author Adnan M.Sagar, <adnan@websemantics.ca>
 * @link   https://github.com/websemantics/file-icons
 */

class FileIcons
{
    /* This is probably for the right way to do it! */
    private static $css  =  '/vendor/websemantics/file-icons/assets/css/file-icons.css';

    /**
     * File icons database helper class instance.
     *
     * @var Array
     */
    protected $db;

    /**
     * constructer.
     *
     * @var Array, string $files, a list of less / scss files
     */
    function __construct() {
        
        $icondb = require __DIR__ . '/icondb.php';
        $this->db = new IconTables($icondb);
    }

    /**
     * Incude file icons styles.
     *
     */
    public static function includeCss() {
        return '<link rel="stylesheet" href="'.self::$css.'">';
    }

    /**
     * Get icon class name of the provided filename. If not found, default to text icon.
     *
     * @var string $filename, file name
     */
    public function getClass($filename) {
        return ($match = $this->db->matchName($filename)) ? $match->getClass() : 'text-icon';
    }

    /**
     * Get icon class name of the provided filename with color. If not found, default to text icon.
     *
     * @var string $filename, file name
     */
    public function getClassWithColor($filename) {
        return ($match = $this->db->matchName($filename)) ? $match->getClass(1) : 'text-icon';
    }

}
