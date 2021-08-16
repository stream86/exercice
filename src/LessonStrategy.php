<?php

namespace App;

use App\Entity\Instructor;
use App\Entity\Learner;
use App\Entity\Lesson;
use App\Repository\InstructorRepository;
use App\Repository\LessonRepository;
use App\Repository\MeetingPointRepository;
use App\Contract\NotifyStrategyInterface;

class LessonStrategy implements NotifyStrategyInterface
{ 
    public function notify(string $text, Lesson $lesson): string 
    {
        if ($lesson)
        {
            $_lessonFromRepository = LessonRepository::getInstance()->getById($lesson->id);
            $usefulObject = MeetingPointRepository::getInstance()->getById($lesson->meetingPointId);
            $instructorOfLesson = InstructorRepository::getInstance()->getById($lesson->instructorId);

            if(strpos($text, '[lesson:instructor_link]') !== false){
                $text = str_replace('[instructor_link]',  'instructors/' . $instructorOfLesson->id .'-'.urlencode($instructorOfLesson->firstname), $text);
            }

            $containsSummaryHtml = strpos($text, '[lesson:summary_html]');
            $containsSummary     = strpos($text, '[lesson:summary]');

            if ($containsSummaryHtml !== false || $containsSummary !== false) {
                if ($containsSummaryHtml !== false) {
                    $text = str_replace(
                        '[lesson:summary_html]',
                        Lesson::renderHtml($_lessonFromRepository),
                        $text
                    );
                }
                if ($containsSummary !== false) {
                    $text = str_replace(
                        '[lesson:summary]',
                        Lesson::renderText($_lessonFromRepository),
                        $text
                    );}}

            (strpos($text, '[lesson:instructor_name]') !== false) and $text = str_replace('[lesson:instructor_name]',$instructorOfLesson->firstname,$text);
        }

        if ($lesson->meetingPointId) {
            if(strpos($text, '[lesson:meeting_point]') !== false)
                $text = str_replace('[lesson:meeting_point]', $usefulObject->name, $text);
        }

        if(strpos($text, '[lesson:start_date]') !== false)
            $text = str_replace('[lesson:start_date]', $lesson->start_time->format('d/m/Y'), $text);

        if(strpos($text, '[lesson:start_time]') !== false)
            $text = str_replace('[lesson:start_time]', $lesson->start_time->format('H:i'), $text);

        if(strpos($text, '[lesson:end_time]') !== false)
            $text = str_replace('[lesson:end_time]', $lesson->end_time->format('H:i'), $text);


            if (isset($data['instructor'])  and ($data['instructor']  instanceof Instructor))
                $text = str_replace('[instructor_link]',  'instructors/' . $data['instructor']->id .'-'.urlencode($data['instructor']->firstname), $text);
            else
                $text = str_replace('[instructor_link]', '', $text);

        return $text;
    }
}