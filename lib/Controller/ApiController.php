<?php

declare(strict_types=1);

namespace OCA\RsRss\Controller;

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\ApiRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;

/**
 * @psalm-suppress UnusedClass
 */
class ApiController extends OCSController {
	/**
	 * An example API endpoint
	 *
	 * @return DataResponse<Http::STATUS_OK, array{message: string}, array{}>
	 *
	 * 200: Data returned
	 */
	#[NoAdminRequired]
	#[ApiRoute(verb: 'GET', url: '/api')]
	public function index(string $url): DataResponse {
		$feed = $this->fetchRss($url);

		return new DataResponse(
			["feed" => $feed]
		);
	}

	private function fetchRss(string $url) {
		$curlHandler = curl_init($url);
		curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);

		$data = curl_exec($curlHandler);
		curl_close($curlHandler);

		$xml = simplexml_load_string($data);
		return $xml;
	}
}
