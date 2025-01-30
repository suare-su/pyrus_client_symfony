<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter;

use SuareSu\PyrusClient\Entity\Form\Form;

/**
 * Helper calss with usefull methods common for form converter.
 *
 * @psalm-api
 */
final class PyrusFormConverterHelper
{
    /**
     * @psalm-suppress UnusedConstructor
     */
    private function __construct()
    {
    }

    /**
     * Extracts form name for html tag from form object.
     */
    public static function getHtmlName(Form $form): string
    {
        return "form_{$form->id}";
    }

    /**
     * Extracts form id from html name.
     */
    public static function getIdFromHtmlName(?string $htmlName): ?int
    {
        if (null !== $htmlName && preg_match('/^form_([0-9]+)$/', $htmlName, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    /**
     * Extracts form label for html tag from form object.
     */
    public static function getHtmlLabel(Form $form): string
    {
        return $form->name;
    }

    /**
     * Extracts default options from Pyrus form to pass to Symfony form.
     *
     * @return array<string, mixed>
     */
    public static function getDefaultOptions(Form $form): array
    {
        return [
            'label' => self::getHtmlLabel($form),
        ];
    }
}
