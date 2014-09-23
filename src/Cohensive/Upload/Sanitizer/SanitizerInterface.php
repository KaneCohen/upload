<?php

namespace Cohensive\Upload\Sanitizer;

interface SanitizerInterface
{
	public function sanitize($string, $separator = '_');
}
