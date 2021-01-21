<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace GeneralTest\ValueObject\Link;

use General\ValueObject\Link\LinkText;
use PHPUnit\Framework\TestCase;

/**
 * Class LinkTextTest
 * @package GeneralTest\ValueObject\Link
 */
class LinkTextTest extends TestCase
{
    public function testLinkText(): void
    {
        $text  = 'test test test test test test test test test';
        $title = 'link title';

        $linkText = new LinkText($text);
        $this->assertEquals($text, $linkText->getText());
        $this->assertEquals($text, $linkText->getTitle());
        $this->assertEquals($text, $linkText->parse());

        $linkText = new LinkText($text, $title);
        $this->assertEquals($text, $linkText->getText());
        $this->assertEquals($title, $linkText->getTitle());
        $this->assertEquals($text, $linkText->parse());

        $linkText = new LinkText($text, $title, 40);
        $this->assertEquals($text, $linkText->getText());
        $this->assertEquals($title, $linkText->getTitle());
        $this->assertEquals('test test test test test test test test&hellip;', $linkText->parse());

        $linkText = LinkText::fromArray([]);
        $this->assertEquals('', $linkText->getText());
        $this->assertEquals('', $linkText->getTitle());
        $this->assertEquals('', $linkText->parse());

        $linkText = LinkText::fromArray(['text' => $text]);
        $this->assertEquals($text, $linkText->getText());
        $this->assertEquals($text, $linkText->getTitle());
        $this->assertEquals($text, $linkText->parse());

        $linkText = LinkText::fromArray(['text' => $text, 'title' => $title]);
        $this->assertEquals($text, $linkText->getText());
        $this->assertEquals($title, $linkText->getTitle());
        $this->assertEquals($text, $linkText->parse());

        $linkText = LinkText::fromArray(['text' => $text, 'title' => $title, 'maxLength' => 40]);
        $this->assertEquals($text, $linkText->getText());
        $this->assertEquals($title, $linkText->getTitle());
        $this->assertEquals('test test test test test test test test&hellip;', $linkText->parse());
    }
}
