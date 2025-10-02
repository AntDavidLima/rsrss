<?php

declare(strict_types=1);

namespace OCA\RsRss\Service;

class XmlService {
	public function fetchXml(string $url) {
		$curlHandler = curl_init($url);
		curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);

		$data = curl_exec($curlHandler);
		curl_close($curlHandler);

		$xml = simplexml_load_string($data);
		return $xml;
	}
}
