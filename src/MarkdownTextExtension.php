<?php
# (C) 2021 Tim Burt <tim@biffbangpow.com>
#
#    This file is part of silverstripe-markdowntext-extension.
#
#    silverstripe-markdowntext-extension is free software: you can 
#    redistribute it and/or modify it under the terms of the GNU 
#    General Public License as published by the Free Software 
#    Foundation, either version 3 of the License, or (at your 
#    option) any later version.
#
#    silverstripe-markdowntext-extension is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with silverstripe-markdowntext-extension.  If not, see <https://www.gnu.org/licenses/>.

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
