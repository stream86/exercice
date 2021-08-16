<?php
namespace App;

use App\Context\ApplicationContext;
use App\Entity\Lesson;
use App\Entity\Learner;
use App\Entity\Template;
use App\LessonStrategy;

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        $APPLICATION_CONTEXT = ApplicationContext::getInstance();
        /*
         * USER
         * [user:*]
         */
        $_user  = (isset($data['user'])  and ($data['user']  instanceof Learner))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();
        if($_user) {
            (strpos($text, '[user:first_name]') !== false) and $text = str_replace('[user:first_name]'       , ucfirst(strtolower($_user->firstname)), $text);
        }

        $lesson = (isset($data['lesson']) and $data['lesson'] instanceof Lesson) ? $data['lesson'] : null;
        if ($lesson) {
            $strategy = new LessonStrategy();
        }
        $APPLICATION_CONTEXT->setStrategy($strategy);

        return $APPLICATION_CONTEXT->notifyUser($text, $lesson);
    }
}
