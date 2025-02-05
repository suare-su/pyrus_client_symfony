<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\FormField;

/**
 * Helper calss with usefull methods common for all converters.
 *
 * @psalm-api
 */
final class PyrusFieldConverterHelper
{
    /**
     * @psalm-suppress UnusedConstructor
     */
    private function __construct()
    {
    }

    /**
     * Extracts field name for html tag from field object.
     */
    public static function getHtmlName(FormField $field): string
    {
        return "field_{$field->id}";
    }

    /**
     * Extracts field label for html tag from field object.
     */
    public static function getHtmlLabel(FormField $field): string
    {
        return $field->name;
    }

    /**
     * Extracts default options from Pyrus field to pass to Symfony form.
     *
     * @return array<string, mixed>
     */
    public static function getDefaultOptions(FormField $field): array
    {
        return [
            'label' => self::getHtmlLabel($field),
            'required' => false,
        ];
    }
}
