<?php

declare(strict_types=1);

namespace GeneralTest\ValueObject\Link;

use General\ValueObject\Link\Link;
use General\ValueObject\Link\LinkDecoration;
use General\ValueObject\Link\LinkRoute;
use General\ValueObject\Link\LinkText;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Laminas\Router\RouteStackInterface;

/**
 * Class LinkTest
 * @package GeneralTest\ValueObject\Link
 */
class LinkTest extends TestCase
{
    public function testLink(): void
    {
        $route          = 'some/fine/route';
        $text           = 'test';
        $serverUrl      = 'https://itea3.org';
        $generatedLink  = '<a href="https://itea3.org/some/fine/route.html" title="test">test</a>';

        /** @var RouteStackInterface|MockObject $routerMock */
        $routerMock = $this->getMockBuilder(RouteStackInterface::class)
            ->getMock();

        $routerMock->expects($this->exactly(2))
            ->method('assemble')
            ->with([], ['name' => $route, 'query' => null, 'fragment' => null])
            ->willReturn('/some/fine/route.html');

        $linkRoute      = new LinkRoute($route);
        $linkText       = new LinkText($text);
        $linkDecoration = new LinkDecoration(LinkDecoration::SHOW_TEXT, $linkText);

        $link         = new Link($linkRoute, $linkDecoration);
        $instanceLink = $link->parse($routerMock, $serverUrl);
        $this->assertEquals($generatedLink, $instanceLink);

        $link      = Link::fromArray([
            'route' => $route,
            'text'  => $text
        ]);
        $arrayLink = $link->parse($routerMock, $serverUrl);
        $this->assertEquals($generatedLink, $arrayLink);
    }
}
