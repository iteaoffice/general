<?php
declare(strict_types=1);

namespace GeneralTest\ValueObject\Link;

use General\ValueObject\Link\Link;
use General\ValueObject\Link\LinkDecoration;
use General\ValueObject\Link\LinkRoute;
use General\ValueObject\Link\LinkText;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Zend\Router\RouteStackInterface;

/**
 * Class LinkRouteTest
 * @package GeneralTest\ValueObject\Link
 */
class LinkRouteTest extends TestCase
{
    public function testLinkRoute(): void
    {
        $route          = 'some/fine/route';
        $serverUrl      = 'https://itea3.org';
        $generatedLink1 = 'https://itea3.org/some/fine/route.html';
        $generatedLink2 = 'https://itea3.org/some/fine/route/1.html';
        $generatedLink3 = 'https://itea3.org/some/fine/route/1.html?x=y';
        $generatedLink4 = 'https://itea3.org/some/fine/route/1.html?x=y#test';
        $generatedLink5 = 'https://itea3.org';

        /** @var RouteStackInterface|MockObject $routerMock */
        $routerMock = $this->getMockBuilder(RouteStackInterface::class)->getMock();

        $map = [
            [[], ['name' => '', 'query' => null, 'fragment' => null], ''],
            [[], ['name' => $route, 'query' => null, 'fragment' => null], '/some/fine/route.html'],
            [['id' => 1], ['name' => $route, 'query' => null, 'fragment' => null], '/some/fine/route/1.html'],
            [['id' => 1], ['name' => $route, 'query' => ['x' => 'y'], 'fragment' => null], '/some/fine/route/1.html?x=y'],
            [['id' => 1], ['name' => $route, 'query' => ['x' => 'y'], 'fragment' => 'test'], '/some/fine/route/1.html?x=y#test'],
        ];

        $routerMock->expects($this->exactly(9))
            ->method('assemble')
            ->will($this->returnValueMap($map));

        $linkRoute = new LinkRoute($route);
        $this->assertEquals($generatedLink1, $linkRoute->parse($routerMock, $serverUrl));

        $linkRoute = new LinkRoute($route, ['id' => 1]);
        $this->assertEquals($generatedLink2, $linkRoute->parse($routerMock, $serverUrl));

        $linkRoute = new LinkRoute($route, ['id' => 1], ['x' => 'y']);
        $this->assertEquals($generatedLink3, $linkRoute->parse($routerMock, $serverUrl));

        $linkRoute = new LinkRoute($route, ['id' => 1], ['x' => 'y'], 'test');
        $this->assertEquals($generatedLink4, $linkRoute->parse($routerMock, $serverUrl));

        $linkRoute = LinkRoute::fromArray(['route' => $route]);
        $this->assertEquals($generatedLink1, $linkRoute->parse($routerMock, $serverUrl));

        $linkRoute = LinkRoute::fromArray(['route' => $route, 'routeParams' => ['id' => 1]]);
        $this->assertEquals($generatedLink2, $linkRoute->parse($routerMock, $serverUrl));

        $linkRoute = LinkRoute::fromArray([
            'route' => $route,
            'routeParams' => ['id' => 1],
            'queryParams' => ['x' => 'y']
        ]);
        $this->assertEquals($generatedLink3, $linkRoute->parse($routerMock, $serverUrl));

        $linkRoute = LinkRoute::fromArray([
            'route'       => $route,
            'routeParams' => ['id' => 1],
            'queryParams' => ['x' => 'y'],
            'fragment'    => 'test'
        ]);
        $this->assertEquals($generatedLink4, $linkRoute->parse($routerMock, $serverUrl));

        $linkRoute = LinkRoute::fromArray([]);
        $this->assertEquals($generatedLink5, $linkRoute->parse($routerMock, $serverUrl));
    }
}
