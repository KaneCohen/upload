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
    ],
    'rules' => [
        'minSize'   => 0,                // Minimum filesize.
        'maxSize'   => (2*1024*1024),    // Maximum filesize: 2MB.
        'maxWidth'  => 0,                // Maximum image width if file is an image.
        'maxHeight' => 0,                // Maximum image height if file is an image.
        'minWidth'  => 0,                // Minimum image width if file is an image.
        'minHeight' => 0,                // Minimum image height if file is an image.
        'width'     => [],               // Image must have exact width (use array to set multiple).
        'height'    => [],               // Image must have exact height (use array to set multiple).
        'whiteExt'  => ['jpg', 'jpeg', 'gif', 'png'], // Array of allowed extensions.
        'blackExt'  => []                             // Array of disallowed extensions.
    ]
];
