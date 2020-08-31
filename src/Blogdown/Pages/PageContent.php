<?php 

namespace RTNatePHP\Blogdown\Pages;

use RTNatePHP\Blogdown\Files\FileInterface;
use RTNatePHP\Blogdown\Files\TextFile;
use RTNatePHP\Util\Arr;
use RTNatePHP\Util\Interfaces\GetterAndSetter;
use RTNatePHP\Util\Interfaces\MemberArrayIterator;
use RTNatePHP\Util\Traits\GettableNotSettable;
use RTNatePHP\Util\Traits\HasMemberArrayIterator;

class PageContent implements GetterAndSetter
{
    use GettableNotSettable;
    protected $contentName;
    protected $pageData = [];
    protected $bodies;

    /**
     * Construct a new PageData object
     * 
     * @param string $title The page's title (short indentifier)
     * @param PageBody $body The page's body 
     * @param array $additionalData Additional data variables to set on the page as an
     *                  associate array.  Some special purpose variables include:
     *                  - description: The pages description (a short tagline, optional)
     */
    public function __construct(string $name, PageBody $body = null, array $additionalData = [])
    {
        $this->contentName = $name;
        $this->pageData = $additionalData;
        $title = Arr::get($additionalData, 'title', $name);
        $this->pageData['title'] = $title;
        $this->pageData['description'] = Arr::get($additionalData, 'description', $title);
        $this->pageData['layout'] = Arr::get($additionalData, 'description', 'default');
        $this->bodies = new PageBodyArray($body);
    }

    public function getPageBodies()
    {
        return $this->bodies;
    }

    public function getName()
    {
        return $this->contentName;
    }

    public function get($key, $default = null)
    {
        if ($key == 'bodies') return $this->getPageBodies();
        if ($key == 'content_more' || $key == 'body_more') return $this->bodies->concatenateMore();
        if ($key == 'content' || $key == 'body') return $this->bodies->concatentate();
        if ($key == 'excerpt') return $this->bodies->concatentateExcerpt();
        if (array_key_exists($key, $this->pageData)) return $this->pageData[$key];
        return '';
    }

    public function has($key)
    {
        if ($key == 'bodies') return true;
        if ($key == 'content_more' || $key == 'body_more') return true;
        if ($key == 'content' || $key == 'body') return true;
        if ($key == 'excerpt') return true;
        if (array_key_exists($key, $this->pageData)) return true;
        return false;
    }

    public function data(): array
    {
        return $this->pageData;
    }

    public function addItem(string $key, $value)
    {
        $this->pageData[$key] = $value;
    }

    public function addItems(array $items)
    {
        $this->pageData = array_merge_recursive($this->pageData, $items);
    }

    public function mergeWith(PageContent $page)
    {
        $this->addBodies($page->getPageBodies());
        $this->addItems($page->data());
    }

    public function addBody(PageBody $body)
    {
        array_push($this->bodies, $body);
    }

    public function addBodies($bodies)
    {
        foreach($bodies as $body)
        {
            if (!($body instanceof PageBody)) continue;
            $this->addBody($body);
        }
    }

    // protected function getIteratorArray(): array
    // {
    //     return [];
    // }
    
    static public function loadFromFile(FileInterface $file, bool $parseSafe = true)
    {
        $data = $file->data();
        $content = $file->content();
        $type = $file->type();
        $filename = $file->filename();
        $separator = Arr::get($data, 'excerpt_separator', '');
        $content_type = PageBody::CONTENT_STRING;
        if ($type == TextFile::FILE_TYPE_MARKDOWN) $content_type = PageBody::CONTENT_MARKDOWN;
        else if ($type == TextFile::FILE_TYPE_HTML) $content_type = PageBody::CONTENT_HTML;
        $body = new PageBody($content, $content_type, ['use_safe_mode' => $parseSafe, 'excerpt_separator' => $separator]); 
        return new static($filename, $body, $data);
    }

}