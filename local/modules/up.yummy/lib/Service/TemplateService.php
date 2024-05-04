<?php

namespace Up\Yummy\Service;

class TemplateService
{
	public static function truncate(string $text, ?int $maxLength = null): string
	{
		if ($maxLength === null)
		{
			return $text;
		}
		return mb_strimwidth($text, 0, $maxLength, '...');
	}
}