<?php 

namespace RTNatePHP\Blogdown\Files;

use RTNatePHP\Util\Enum;

/**
 * File type enumeration
 * 
 * @method static FileType TEXT() A File with generic Text Type
 * @method static FileType HTML() A File with HTML Type
 * @method static FileType MARKDOWN() A File with Markdown Type
 * @method static FileType PHP() A File with PHP Type
 * @method static FileType JSON() A File with JSON Type
 * @method static FileType XML() A File with XML Type
 * @method static FileType YAML() A File with YAML Type
 * @method static FileType INVALID() An invalid file (doesn't exist)
 * @method static FileType UNKNOWN() Another unknown (but existing) file type
 */
class FileType extends Enum
{
    /**
     * A File with generic Text Type
     * @var string
     */
    private const TEXT= 'text';

    /**
     * A File with HTML Type
     * @var string
     */
    private const HTML = 'html';

    /**
     * A File with Markdown Type
     * @var string
     */
    private const MARKDOWN = 'markdown';

    /**
     * A File with PHP Type
     * @var string
     */
    private const PHP = 'php';

    /**
     * A File with JSON Type
     * @var string
     */
    private const JSON = 'json';

    /**
     * A File with XML Type
     * @var string
     */
    private const XML = 'xml';

    /**
     * A File with YAML Type
     * @var string
     */
    private const YAML = 'yaml';

    /**
     * An invalid file (doesn't exist)
     * @var string
     */
    private const INVALID = 'invalid';

    /**
     * Another unknown (but existing) file type
     * @var string
     */
    private const UNKNOWN = 'unknown';
}