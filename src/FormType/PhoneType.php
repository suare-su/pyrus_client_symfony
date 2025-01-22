<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Symfony phone type for phone.
 *
 * @psalm-api
 */
final class PhoneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return TextType::class;
    }
}
