# Upload

Handle file uploading via standart HTML multipart form or via XHR data stream.

## Usage

Upload can be used alone as any class or as a package for Laravel 4 framework
(if you'd like to add support to any other frameworks, write to the issues or
make a pull request with required changes).

First, add following line to the list of requirements in composer.json:

````js
    "cohensive/upload": "dev-master"
````

Run `composer install` or `composer update` to download it and autoload.

### Laravel 4

Get package config file - not required, but maybe handy if you have a lot of
various upload fields and you want to change default Upload options.

````php
php artisan config:publish cohensive/upload
````

In `providers` array you need to add new package:

````php
'providers' => array(
    //...
    'Cohensive\Upload\UploadServiceProvider',
    //...
)
````

In aliases add Upload facade:

````php
'aliases' => array(
    //...
    'Upload' => 'Cohensive\Upload\Facades\Laravel\Upload'
    //...
)
````

#### Using in the code

````php
// Create new Upload instance.
// Both params are optional arrays. Description of allowed array items below.
$upload = Upload::make($options, $rules);
if ($upload->passes()) {
    // If file is valid, receive and store it in the uploads (set in options) directory.
    $upload->receive();
} else {
    // Get array of errors - simple list of failed validation rules.
    $upload->getErrors();
}
````

What to do with uploaded files next is out of the scope of Upload.

-------

### Standalone

As a standalone package you'll have to instantiate several classes that Upload
depends on. Namely:

* Validator - will validate files agains a set of rules.

* Sanitizer - will keep filename safe for web (transliteration to ascii). It has
one required parameter - instantiated class that will do the file sanitization.
Various framework has own versions of such class often called Transliterator or
Inflector. You just use its instance as a first argument and as a second
parameter include method name that will be used for sanitization.
Alternatively, you can create a wrapper for you Inflector which implements
\Cohensive\Upload\Sanitizer\SanitizerInterface

* FileHandlerFactory - will create FileHandlers based on the method of uploading

````php
$validator = new \Cohensive\Upload\Validator([
    'maxSize' => 2097152 // Set maximum allowed filesize to 2MB (set in bytes)
]);

$sanitizer = new \Cohensive\Upload\Sanitizer\AbstractSanitizer($sanitizerClass, $sanitizerMethod);

$fileFactory = new \Cohensive\Upload\FileHandlerFactory();

$options = [...]; // An array of options for Upload, such as upload directory etc.
$upload = new \Cohensive\Upload\Upload($validator, $sanitizer, $fileFactory, $options);
````

#### Using in the code

````php
$rules = [...]; // An array of validation rules. Optional.
if ($upload->passes($rules)) {
    // If file is valid, receive and store it in the uploads (set in options) directory.
    $upload->receive();
} else {
    // Get array of errors - simple list of failed validation rules.
    $errors = $validator->getErrors();
}
````

### Options

Uploader accets an array of options on instantiation:

````php
    'uploadDir'   => 'uploads/',     // Folder where all uploaded files will be saved to.
    'tmpDir'      => 'uploads/tmp/', // Folder to keep files temporary for operations.
    'param'       => 'file',         // Parameter to access the file on.
    'name'        => '',             // Set new filename. Blank to use original name.
    'nameLength'  => 32,             // Set maximum length of the name. Will be cut if longer.
    'prefix'      => '',             // Add prefix to the filename..
    'suffix'      => '',             // Add suffix to the filename.
    'case'        => '',             // Convert file name to the case: 'lower', 'upper' or falsy to keep original.
    'overwrite'   => false,          // If file already exists, overwrite it.
    'autoRename'  => true,           // In case if file with the same name exists append counter to the new file.
    'randomize'   => false,          // Generate random filename. Boolean or integer for string length. Default length is 10.
    'sanitize'    => true            // Sanitize filename - remove whitespaces and convert utf8 to ascii.
````

#### Rules

An array of rules that could be used while validation uploaded files:

````php
    'minSize'   => 0,        // Minimum filesize.
    'maxSize'   => 10485760, // Maximum filesize: 10MB.
    'maxWidth'  => 0,        // Maximum image width if file is an image.
    'maxHeight' => 0,        // Maximum image height if file is an image.
    'minWidth'  => 0,        // Minimum image width if file is an image.
    'minHeight' => 0,        // Minimum image height if file is an image.
    'width'     => [],       // Image must have exact width (use array to set multiple).
    'height'    => [],       // Image must have exact height (use array to set multiple).
    'whiteExt'  => ['jpg', 'jpeg', 'gif', 'png'], // Array of allowed extensions.
    'blackExt'  => []                             // Array of disallowed extensions.
````
