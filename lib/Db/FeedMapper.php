<?php

declare(strict_types=1);

namespace OCA\RsRss\Db;

use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class FeedMapper extends QBMapper {
	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'rsrss_feeds', Feed::class);
	}

	/**
	 * @param string $userId
	 * @return Feed[]
	 * @throws Exception
	 */
	public function getFeedsOfUser(string $userId): array {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
			);

		return $this->findEntities($qb);
	}

	/**
	 * @param string $userId
	 * @param string $url
	 * @return Feed
	 * @throws Exception
	 */
	public function createFeed(string $userId, string $url): Feed {
		$feed = new Feed();
		$feed->setUserId($userId);
		$feed->setUrl($url);
		return $this->insert($feed);
	}
}
