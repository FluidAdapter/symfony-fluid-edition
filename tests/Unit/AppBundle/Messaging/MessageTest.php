<?php

namespace Tests\AppBundle\Messaging;

use AppBundle\Messaging\Message;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TYPO3Fluid\Fluid\View\TemplatePaths;
use TYPO3Fluid\Fluid\View\TemplateView;

class MessageTest extends KernelTestCase
{

    /**
     * @test
     */
    public function testMessageCreationDefaults()
    {
        $message = new Message();
        $this->assertEquals('text/html', $message->getContentType());

        $message = new Message('some subject', 'some body', 'text/foo');
        $this->assertEquals('some subject', $message->getSubject());
        $this->assertEquals('some body', $message->getBody());
        $this->assertEquals('text/foo', $message->getContentType());
    }

    /**
     * @test
     */
    public function testTemplatePaths() {
        $templateView = $this->createMock(TemplateView::class);
        $templatePaths = $this->createMock(TemplatePaths::class);
        $templateView->expects($this->once())->method('getTemplatePaths')->wilLReturn($templatePaths);
        $templatePaths->expects($this->once())->method('setTemplatePathAndFilename')->with('app/Resources/views/Messages/Woot.html');
        $templatePaths->expects($this->once())->method('setPartialRootPaths')->with(['app/Resources/views/Partials']);

        $message = new Message();
        $message->setTemplateView($templateView);
        $message->setMessage('Woot');
        $message->render();
    }
}
