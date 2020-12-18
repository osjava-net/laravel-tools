<?php

return [
    'source_folder' => [
        app_path('Http/Controllers'),
        app_path('Services'),
        app_path('Models'),
        app_path('Support'),
        app_path('Exceptions'),
    ],

    /*
     * Exclude some files.
     */
    'excludes' => [
        app_path('Support/helpers.php'),
        app_path('Support/constants.php'),
    ],

    /*
     * Static output folder: HTML documentation and assets will be generated in this folder.
     */
    'doc_folder' => resource_path('docs'),

    'html_folder' => public_path('docs'),

    'app_alias' => 'App',

    'class_suffix' => '.php',

    'doc_file_ext' => '.adoc',
];
