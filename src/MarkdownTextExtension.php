<?php

namespace BiffBangPow\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\ORM\FieldType\DBText;
use SilverStripe\ORM\FieldType\DBVarchar;
use SilverStripe\View\ArrayData;

class MarkdownTextExtension extends Extension
{
    /**
     * @var array
     */
    private $allowedTypes = [
        DBVarchar::class,
        DBText::class,
        'Varchar(255)',
    ];

    /**
     * @return string
     */
    public function getMarkdownText()
    {
        $db = $this->owner->config()->get('db');

        $convertedStrings = [];

        foreach ($db as $key => $type) {

            if (in_array($type, $this->allowedTypes)) {

                if ($this->owner->$key !== null) {
                    $convertedStrings[$key] = $this->convertMarkdownToString($this->owner->$key);
                }

            }

        }

        return new ArrayData($convertedStrings);
    }

    /**
     * @param string $markdown
     * @return string
     */
    public function convertMarkdownToString(string $markdown)
    {
        $subs = array(
            '/\*(.+)\*/Ui' => '<strong>$1</strong>',
            '/_(.+)_/Ui' => '<em>$1</em>',
        );

        $markdown = preg_replace(array_keys($subs), array_values($subs), $markdown);

        return $markdown;

    }
}
