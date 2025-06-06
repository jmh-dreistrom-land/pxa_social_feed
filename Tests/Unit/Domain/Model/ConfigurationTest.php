<?php

namespace Pixelant\PxaSocialFeed\Tests\Unit\Domain\Model;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pixelant\PxaSocialFeed\Domain\Model\Token;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class \Pixelant\PxaSocialFeed\Domain\Model\Configuration.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ConfigurationTest extends UnitTestCase
{
    /**
     * @var \Pixelant\PxaSocialFeed\Domain\Model\Configuration
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new \Pixelant\PxaSocialFeed\Domain\Model\Configuration();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function initialValueOfPid(): void
    {
        self::assertEquals(0, $this->subject->getPid());
    }

    /**
     * @test
     */
    public function canSetPid(): void
    {
        $value = 123;

        $this->subject->setPid($value);
        self::assertEquals($value, $this->subject->getPid());
    }

    /**
     * @test
     */
    public function initialValueOfHidden(): void
    {
        self::assertFalse($this->subject->isHidden());
    }

    /**
     * @test
     */
    public function canSetHidden(): void
    {
        $value = true;

        $this->subject->setHidden($value);
        self::assertEquals($value, $this->subject->isHidden());
    }

    /**
     * @test
     */
    public function initialValueOfName(): void
    {
        self::assertEquals('', $this->subject->getName());
    }

    /**
     * @test
     */
    public function canSetName(): void
    {
        $value = 'name';

        $this->subject->setName($value);

        self::assertEquals($value, $this->subject->getName());
    }

    /**
     * @test
     */
    public function initialValueOfSocialId(): void
    {
        self::assertEquals('', $this->subject->getSocialId());
    }

    /**
     * @test
     */
    public function canSetSocialId(): void
    {
        $value = 'social id';

        $this->subject->setSocialId($value);

        self::assertEquals($value, $this->subject->getSocialId());
    }

    /**
     * @test
     */
    public function initialValueOfMaxItems(): void
    {
        self::assertEquals(0, $this->subject->getMaxItems());
    }

    /**
     * @test
     */
    public function canSetMaxItems(): void
    {
        $value = 1000;

        $this->subject->setMaxItems($value);

        self::assertEquals($value, $this->subject->getMaxItems());
    }

    /**
     * @test
     */
    public function initialValueOfStorage(): void
    {
        self::assertEquals(0, $this->subject->getStorage());
    }

    /**
     * @test
     */
    public function canSetStorage(): void
    {
        $value = 12;

        $this->subject->setStorage($value);

        self::assertEquals($value, $this->subject->getStorage());
    }

    /**
     * @test
     */
    public function initialValueOfToken(): void
    {
        self::assertNull($this->subject->getToken());
    }

    /**
     * @test
     */
    public function canSetToken(): void
    {
        $token = new Token();

        $this->subject->setToken($token);

        self::assertSame($token, $this->subject->getToken());
    }

    /**
     * @test
     */
    public function initValueOfBeGroup(): void
    {
        self::assertInstanceOf(ObjectStorage::class, $this->subject->getBeGroup());
    }

    /**
     * @test
     */
    public function canSetBeGroup(): void
    {
        $beGroup = new ObjectStorage();

        $this->subject->setBeGroup($beGroup);

        self::assertSame($beGroup, $this->subject->getBeGroup());
    }
}
