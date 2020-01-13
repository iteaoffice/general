<?php

declare(strict_types=1);

namespace GeneralTest\ValueObject\Link;

use General\ValueObject\Link\LinkDecoration;
use General\ValueObject\Link\LinkText;
use PHPUnit\Framework\TestCase;

/**
 * Class LinkDecorationTest
 * @package GeneralTest\ValueObject\Link
 */
class LinkDecorationTest extends TestCase
{
    public function testLinkDecoration(): void
    {
        $linkText = new LinkText('test');

        $linkDecoration = new LinkDecoration();
        $this->assertEquals('<a href="%s"></a>', $linkDecoration->parse());

        $linkDecoration = new LinkDecoration(LinkDecoration::SHOW_RAW);
        $this->assertEquals('%s', $linkDecoration->parse());

        $linkDecoration = new LinkDecoration(LinkDecoration::SHOW_TEXT, $linkText);
        $this->assertEquals('<a href="%s" title="test">test</a>', $linkDecoration->parse());

        $linkDecoration = new LinkDecoration(LinkDecoration::SHOW_ICON, $linkText, null, 'fa-plus');
        $this->assertEquals(
            '<a href="%s" title="test"><i class="fa fa-plus fa-fw"></i></a>',
            $linkDecoration->parse()
        );

        $linkDecoration = new LinkDecoration(LinkDecoration::SHOW_ICON_AND_TEXT, $linkText, null, 'fa-plus');
        $this->assertEquals(
            '<a href="%s" title="test"><i class="fa fa-plus fa-fw"></i> test</a>',
            $linkDecoration->parse()
        );

        $linkDecoration = new LinkDecoration(LinkDecoration::SHOW_BUTTON, $linkText, 'new');
        $this->assertEquals(
            '<a href="%s" title="test" class="btn btn-primary"><i class="fa fa-plus fa-fw"></i> test</a>',
            $linkDecoration->parse()
        );

        $linkDecoration = new LinkDecoration(LinkDecoration::SHOW_ICON, $linkText, 'edit');
        $this->assertEquals(
            '<a href="%s" title="test"><i class="fa fa-pencil-square-o fa-fw"></i></a>',
            $linkDecoration->parse()
        );

        $linkDecoration = new LinkDecoration(LinkDecoration::SHOW_ICON, $linkText, 'delete');
        $this->assertEquals(
            '<a href="%s" title="test"><i class="fa fa-trash fa-fw"></i></a>',
            $linkDecoration->parse()
        );

        $linkDecoration = LinkDecoration::fromArray([
            'show'   => LinkDecoration::SHOW_BUTTON,
            'text'   => 'test',
            'action' => 'new'
        ]);
        $this->assertEquals(
            '<a href="%s" title="test" class="btn btn-primary"><i class="fa fa-plus fa-fw"></i> test</a>',
            $linkDecoration->parse()
        );
    }
}
