<?php
return array(
    'zfctwig' => array(
        'disable_zf_model' => false,
        'extensions'       => array(
            'string_date' => 'Application\Twig\StringDateExtension',
            'dump'        => 'Twig_Extension_Debug',
        ),
    ),
);
