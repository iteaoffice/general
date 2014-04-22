<?php
return array(
    'zfctwig' => array(
        'disable_zf_model' => false,
        'extensions'       => array(
            'datauri'     => 'DataURI\TwigExtension',
            'string_date' => 'Application\Twig\StringDateExtension',
            'dump'        => 'Twig_Extension_Debug'
        ),
    ),
);
