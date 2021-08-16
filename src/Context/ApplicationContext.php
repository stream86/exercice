<?php

namespace App\Context;

use App\Entity\Lesson;
use App\Entity\Learner;
use App\Helper\SingletonTrait;
use App\Contract\NotifyStrategyInterface;

class ApplicationContext
{
    use SingletonTrait;

    /**
     * @var Learner
     */
    private Learner $currentUser;
    private NotifyStrategyInterface $notificationStrategy;

    protected function __construct()
    {

    }

    /**
     * @param NotifyStrategyInterface $notificationStrategy
     */
    public function setStrategy(NotifyStrategyInterface $notificationStrategy): void
    {
        $this->notificationStrategy = $notificationStrategy;
    }

    /**
     * @param string $text
     * @param Lesson $lesson
     */
    public function notifyUser(string $text, Lesson $lesson): string
    {
        return $this->notificationStrategy->notify($text, $lesson);
    }

    /**
     * @return Learner
     */
    public function getCurrentUser(): Learner
    {
        return $this->currentUser;
    }

    /**
     * @param Learner $currentUser
     */
    public function setCurrentUser(Learner $currentUser): void
    {
        $this->currentUser = $currentUser;
    }
}
