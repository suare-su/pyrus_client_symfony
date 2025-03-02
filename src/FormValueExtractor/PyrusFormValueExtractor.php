<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormValueExtractor;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Task\FormTaskCreateField;
use Symfony\Component\Form\FormInterface;

/**
 * Interface for object that can extract value from Symfony form and convert it to FormTaskCreateField.
 *
 * @psalm-api
 */
interface PyrusFormValueExtractor
{
    /**
     * Extract values from Symfony form to array of FormTaskCreateField.
     *
     * @return FormTaskCreateField[]
     */
    public function extract(FormInterface $symfonyForm, Form $pyrusForm): array;
}
