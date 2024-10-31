<?php

namespace NSScottyPlugin\WPBones\Html;

class Html
{
    protected static $htmlTags = [
    'a'        => '\NSScottyPlugin\WPBones\Html\HtmlTagA',
    'button'   => '\NSScottyPlugin\WPBones\Html\HtmlTagButton',
    'checkbox' => '\NSScottyPlugin\WPBones\Html\HtmlTagCheckbox',
    'datetime' => '\NSScottyPlugin\WPBones\Html\HtmlTagDatetime',
    'fieldset' => '\NSScottyPlugin\WPBones\Html\HtmlTagFieldSet',
    'form'     => '\NSScottyPlugin\WPBones\Html\HtmlTagForm',
    'input'    => '\NSScottyPlugin\WPBones\Html\HtmlTagInput',
    'label'    => '\NSScottyPlugin\WPBones\Html\HtmlTagLabel',
    'optgroup' => '\NSScottyPlugin\WPBones\Html\HtmlTagOptGroup',
    'option'   => '\NSScottyPlugin\WPBones\Html\HtmlTagOption',
    'select'   => '\NSScottyPlugin\WPBones\Html\HtmlTagSelect',
    'textarea' => '\NSScottyPlugin\WPBones\Html\HtmlTagTextArea',
  ];

    public static function __callStatic($name, $arguments)
    {
        if (in_array($name, array_keys(self::$htmlTags))) {
            $args = (isset($arguments[ 0 ]) && ! is_null($arguments[ 0 ])) ? $arguments[ 0 ] : [];

            return new self::$htmlTags[ $name ]($args);
        }
    }
}
