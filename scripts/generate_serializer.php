<?php

declare(strict_types=1);

use SuareSu\PyrusClientSymfony\Scripts\SerializerGenerator\SerializerGenerator;

require __DIR__ . '/../vendor/autoload.php';

(new SerializerGenerator())->generate(
    new SplFileInfo(__DIR__ . '/../vendor/suare-su/pyrus_client/src/Entity'),
    new SplFileInfo(__DIR__ . '/../src/PyrusSerializer.php'),
    "SuareSu\PyrusClientSymfony"
);
