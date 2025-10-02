<?php

declare(strict_types=1);

namespace OCA\RsRss\Controller;

use OCA\RsRss\AppInfo\Application;
use OCA\RsRss\Db\FeedMapper;
use OCA\RsRss\Service\XmlService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\FrontpageRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\Attribute\OpenAPI;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\IRequest;

/**
 * @psalm-suppress UnusedClass
 */
class PageController extends Controller {
	public function __construct(
		string $appName,
		IRequest $request,
		private IInitialState $initialStateService,
		private FeedMapper $feedMapper,
		private ?string $userId,
		private XmlService $xmlService,
	) {
		parent::__construct($appName, $request);
	}

	#[NoCSRFRequired]
	#[NoAdminRequired]
	#[OpenAPI(OpenAPI::SCOPE_IGNORE)]
	#[FrontpageRoute(verb: 'GET', url: '/')]
	public function index(): TemplateResponse {
		$feedsUrls = $this->feedMapper->getFeedsOfUser($this->userId);

		$feeds = array_map(fn($feed) => $this->xmlService->fetchXml($feed->getUrl()), $feedsUrls);

		$items = [];

		foreach ($feeds as $feed) {
			foreach ($feed->channel->item as $item) {
				array_push($items, ['origin' => (string)$feed->channel->title, 'item' => $item]);
			}
		}

		$this->initialStateService->provideInitialState(
			'feeds',
			$items
		);

		return new TemplateResponse(
			Application::APP_ID,
			'index',
		);
	}
}
