<?php

namespace AppBundle\Messaging;

use TYPO3Fluid\Fluid\View\TemplateView;

class Message extends \Swift_Message
{
    /**
     * @var TemplateView
     */
    protected $view;

    /**
     * @var string
     */
    protected $templatePath = 'app/Resources/views/Messages/@message.html';

    /**
     * @var string
     */
    protected $partialRootPath = 'app/Resources/views/Partials';

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @param TemplateView $view
     */
    public function setTemplateView(TemplateView $view)
    {
        $this->view = $view;
    }

    public function __construct($subject = null, $body = null, $contentType = null, $charset = null)
    {
        if ($contentType === null) {
            $contentType = 'text/html';
        }
        $this->contentType = $contentType;
        parent::__construct($subject, $body, $contentType, $charset);
    }

    public function setMessage($message)
    {
        $replacements = array(
            '@message' => $message,
        );
        $templatePaths = $this->view->getTemplatePaths();

        $template = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->templatePath
        );
        $templatePaths->setTemplatePathAndFilename($template);

        $partialRootPath = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->partialRootPath
        );
        $templatePaths->setPartialRootPaths([$partialRootPath]);
        return $this;
    }

    public function renderBody()
    {
        $this->setBody($this->render(), $this->contentType);
//        $this->setOptionsByViewHelper();
    }

//    public function setOptionsByViewHelper()
//    {
//        $viewHelperVariableContainer = $this->view->getViewHelperVariableContainer();
//        $settings = array('to', 'from', 'subject');
//        foreach ($settings as $setting) {
//            if ($viewHelperVariableContainer->exists('Famelo\Messaging\ViewHelpers\MessageViewHelper', $setting)) {
//                $value = $viewHelperVariableContainer->get('Famelo\Messaging\ViewHelpers\MessageViewHelper', $setting);
//                ObjectAccess::setProperty($this, $setting, $value);
//                $viewHelperVariableContainer->remove('Famelo\Messaging\ViewHelpers\MessageViewHelper', $setting);
//            }
//        }
//    }

    public function render()
    {
        return $this->view->render();
    }

    public function assign($key, $value)
    {
        $this->view->assign($key, $value);

        return $this;
    }

    public function assignMultiple(array $values)
    {
        foreach ($values as $key => $value) {
            $this->assign($key, $value);
        }

        return $this;
    }

}