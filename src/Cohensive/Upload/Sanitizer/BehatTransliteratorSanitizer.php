<?php

namespace Cohensive\Upload\Sanitizer;

use \Behat\Transliterator;

class BehatTransliteratorSanitizer implements SanitizerInterface
{
	public function sanitize($string, $separator = '_')
	{
		return Transliterator::transliterate($string, $separator);
	}
}
