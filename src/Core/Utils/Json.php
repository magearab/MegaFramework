<?php

namespace MageArab\MegaFramework\Utils;

use MageArab\MegaFramework\Exceptions;
use MageArab\MegaFramework\Traits;


/**
 * JSON encoder and decoder.
 */
final class Json
{
	use Traits\StaticClass;

	public const FORCE_ARRAY = 0b0001;

	public const PRETTY = 0b0010;


    /**
     * Returns the JSON representation of a value. Accepts flag Json::PRETTY.
     * @param $value
     * @param int $flags
     * @return string
     * @throws Exceptions\JsonException
     */
	public static function encode($value, int $flags = 0): string
	{
		$flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
			| ($flags & self::PRETTY ? JSON_PRETTY_PRINT : 0)
			| (defined('JSON_PRESERVE_ZERO_FRACTION') ? JSON_PRESERVE_ZERO_FRACTION : 0); // since PHP 5.6.6 & PECL JSON-C 1.3.7

		$json = json_encode($value, $flags);
		if ($error = json_last_error()) {
			throw new Exceptions\JsonException(json_last_error_msg(), $error);
		}
		return $json;
	}


    /**
     * Decodes a JSON string. Accepts flag Json::FORCE_ARRAY.
     * @param string $json
     * @param int $flags
     * @return mixed
     * @throws Exceptions\JsonException
     */
	public static function decode(string $json, int $flags = 0)
	{
		$forceArray = (bool) ($flags & self::FORCE_ARRAY);
		$value = json_decode($json, $forceArray, 512, JSON_BIGINT_AS_STRING);
		if ($error = json_last_error()) {
			throw new Exceptions\JsonException(json_last_error_msg(), $error);
		}
		return $value;
	}
}
