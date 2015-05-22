<?php

return [
    'options' => [
        'uploadDir'   => 'uploads',     // Folder where all uploaded files will be saved to.
        'tmpDir'      => 'uploads/tmp', // Folder to keep files temporary for operations.
        'param'       => 'file',         // Parameter to access the file on.
        'name'        => '',             // Set new filename. Blank to use original name.
        'nameLength'  => 40,             // Set maximum length of the name. Will be cut if longer.
        'prefix'      => '',             // Add prefix to the filename..
        'suffix'      => '',             // Add suffix to the filename.
        'case'        => '',             // Convert file name to the case: 'lower', 'upper' or ''.
        'overwrite'   => false,          // If file already exists, overwrite it.
        'autoRename'  => true,           // In case if file with the same name exists append counter to the new file.
        'randomize'   => true,           // Generate random filename. Boolean or integer for string length. Default length is 10.
        'sanitize'    => true            // Sanitize filename - remove whitespaces and convert utf8 to ascii.
    ]
];
