<?php

namespace App\Contract;

use App\Entity\Lesson;

interface NotifyStrategyInterface
{
    public function notify(string $text, Lesson $lesson): string; 
}