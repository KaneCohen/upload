<?php

namespace Cohensive\Upload\Sanitizer;

use \Illuminate\Support\Str;

class LaravelStrSanitizer implements SanitizerInterface
{
    public function sanitize($string, $separator = '_')
    {
        return Str::slug($string, $separator);
    }
}
