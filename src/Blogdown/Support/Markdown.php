<?php 

namespace RTNatePHP\Blogdown\Support;

use Parsedown;

/**
 * Wrapper utility for parsing markdown from text with a simple syntax.
 * 
 * @see Parsedown.
 */
class Markdown
{
    /**
     * Parses the supplied string as markdown and returns HTML.
     * 
     * @param string $content The content string to parse.
     * @param bool $use_safe_mode (optional) If set to true (default)
     *             any unsafe HTML in the content string will be escaped.
     * 
     * @return string The parsed markdown as HTML.
     * 
     * @see Parsedown
     */
    static public function parse(string $content, bool $use_safe_mode = true): string
    {
        $parser = new Parsedown();
        $parser->setSafeMode($use_safe_mode);
        return $parser->text($content);
    }
}