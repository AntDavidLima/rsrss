<?php

declare(strict_types=1);

namespace OCA\RsRss\Controller;

use OCA\RsRss\Db\FeedMapper;
use OCA\RsRss\Service\XmlService;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\ApiRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\AppFramework\Services\IInitialState;
use OCP\IRequest;

/**
 * @psalm-suppress UnusedClass
 */
class ApiController extends OCSController {
	public function __construct(
		string $appName,
		IRequest $request,
		private FeedMapper $feedMapper,
		private XmlService $xmlService,
		private ?string $userId,
		private IInitialState $initialStateService,
	) {
		parent::__construct($appName, $request);
	}

	#[NoAdminRequired]
	#[ApiRoute(verb: 'POST', url: '/api')]
	public function store(string $url): DataResponse {
		$this->feedMapper->createFeed($this->userId, $url);

		$feed = $this->xmlService->fetchXml($url);

		return new DataResponse(
			["feed" => $feed]
		);
	}

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
		$feedsUrls = $this->feedMapper->getFeedsOfUser($this->userId);

		$feeds = array_map(fn($feed) => $this->xmlService->fetchXml($feed->getUrl()), $feedsUrls);

		$items = [];

		foreach ($feeds as $feed) {
			foreach ($feed->channel->item as $item) {
				array_push($items, ['origin' => (string)$feed->channel->title, 'item' => $item]);
			}
		}

		return new DataResponse(
			["feed" => $items]
		);
	}
}
