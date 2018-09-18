<?php

/**
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 * @copyright Copyright (c) 2016 Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * Two-factor TOTP
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\TwoFactorTOTP\Test\Unit\Activity;

use ChristophWurst\Nextcloud\Testing\TestCase;
use InvalidArgumentException;
use OCA\TwoFactorTOTP\Activity\Provider;
use OCP\Activity\IEvent;
use OCP\IL10N;
use OCP\ILogger;
use OCP\IURLGenerator;
use OCP\L10N\IFactory;

class ProviderTest extends TestCase {

	private $l10n;
	private $urlGenerator;
	private $logger;

	/** @var Provider */
	private $provider;

	protected function setUp() {
		parent::setUp();

		$this->l10n = $this->createMock(IFactory::class);
		$this->urlGenerator = $this->createMock(IURLGenerator::class);
		$this->logger = $this->createMock(ILogger::class);

		$this->provider = new Provider($this->l10n, $this->urlGenerator, $this->logger);
	}

	public function testParseUnrelated() {
		$lang = 'ru';
		$event = $this->createMock(IEvent::class);
		$event->expects($this->once())
			->method('getApp')
			->will($this->returnValue('comments'));
		$this->expectException(InvalidArgumentException::class);

		$this->provider->parse($lang, $event);
	}

	public function subjectData() {
		return [
				['totp_enabled_subject'],
				['totp_disabled_subject'],
				[null],
		];
	}

	/**
	 * @dataProvider subjectData
	 */
	public function testParse($subject) {
		$lang = 'ru';
		$event = $this->createMock(IEvent::class);
		$l = $this->createMock(IL10N::class);

		$event->expects($this->once())
			->method('getApp')
			->will($this->returnValue('twofactor_totp'));
		$this->l10n->expects($this->once())
			->method('get')
			->with('twofactor_totp', $lang)
			->will($this->returnValue($l));
		$this->urlGenerator->expects($this->once())
			->method('imagePath')
			->with('core', 'actions/password.svg')
			->will($this->returnValue('path/to/image'));
		$this->urlGenerator->expects($this->once())
			->method('getAbsoluteURL')
			->with('path/to/image')
			->will($this->returnValue('absolute/path/to/image'));
		$event->expects($this->once())
			->method('setIcon')
			->with('absolute/path/to/image');
		$event->expects($this->once())
			->method('getSubject')
			->will($this->returnValue($subject));
		if (is_null($subject)) {
			$event->expects($this->never())
				->method('setSubject');
		} else {
			$event->expects($this->once())
				->method('setSubject');
		}

		$this->provider->parse($lang, $event);
	}

}
