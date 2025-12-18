<?php

namespace App\Services;

use App\Jobs\SendEmailForAssignedTask;

class TaskAssignmentService
{
    public function sendNotificaionToAssingedUser($task)
    {
        SendEmailForAssignedTask::dispatch($task->assignedTo->email, $task->title, $task->description);
    }
}
