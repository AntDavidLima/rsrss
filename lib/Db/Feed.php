<?php

declare(strict_types=1);

namespace OCA\RsRss\Db;

use OCP\AppFramework\Db\Entity;

/**
 * @method string getUrl()
 * @method void setUrl(string $url)
 * @method string getUserId()
 * @method void setUserId(string $url)
 */
class Feed extends Entity implements \JsonSerializable {

	/** @var string */
	protected $userId;
	/** @var string */
	protected $url;

	public function __construct() {
		$this->addType('userId', 'string');
		$this->addType('url', 'string');
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return [
			'id' => $this->id,
			'url' => $this->url,
			'user_id' => $this->userId,
		];
	}
}
