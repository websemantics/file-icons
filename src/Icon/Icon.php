<?php namespace Websemantics\FileIcons\Icon;

/**
 * Icon: Immutable hash of icon data.
 *
 * A port of Atom File-icons, https://github.com/file-icons/atom
 *
 * @link      https://github.com/file-icons/atom
 * @author    Daniel Brooker, <dan@nocturnalcode.com>
 * @author    Adnan M.Sagar, <adnan@websemantics.ca>
 */

class Icon
{
    /** @var int, Index of the icon's appearance in the enclosing array */
    protected $index;

    /** @var string, Icon's CSS class (e.g., "js-icon") */
    protected $icon;

    /** @var Array, Icon's colour classes */
    protected $colour;

    /** @var string/RegExp, Pattern for matching names or pathnames */
    public $match;

    /** @var int, priority that determined icon's order of appearance */
    protected $priority;

    /** @var Boolean, Match against system path instead of basename */
    protected $matchPath;

    /** @var string/RegExp Match executable names in hashbangs */
    public $interpreter;

    /** @var string/RegExp, Match grammar scope-names */
    public $scope;

    /** @var string/RegExp, Match alias patterns */
    public $lang;

    /** @var string/RegExp, Match file signatures */
    protected $signature;

    /**
     * Construct.
     *
     * @param {Number}  index - Index of the icon's appearance in the enclosing array
     * @param {Array}   data - icon's data points that contains the following,
     */
    public function __construct($index, $data)
    {
        $this->index = $index;
        $this->icon = $data[0];
        $this->colour = $data[1];
        $this->match = $data[2];
        $this->priority = isset($data[3]) ? $data[3] : 1;
        $this->matchPath = isset($data[4]) ? $data[4] : false;
        $this->interpreter = isset($data[5]) ? $data[5] : null;
        $this->scope = isset($data[6]) ? $data[6] : null;
        $this->lang = isset($data[7]) ? $data[7] : null;
        $this->signature = isset($data[8]) ? $data[8] : null;
    }

    /**
     * Return the CSS classes for displaying the icon.
     *
     * @param {Number|null} colourMode
     * @param {Boolean} asArray
     *
     * @return {String}
     */
    public function getClass($colourMode = null, $asArray = false)
    {
        // No colour needed or available
        if ($colourMode === null || $this->colour[0] === null) {
            return $asArray ? [$this->icon] : $this->icon;
        }

        return $asArray
            ? [$this->icon, $this->colour[$colourMode]]
            : $this->icon . ' ' .$this->colour[$colourMode];
    }
}
