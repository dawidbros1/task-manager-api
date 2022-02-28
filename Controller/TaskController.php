<?php

namespace Controller;

use Model\Task;
use Rules\TaskRules;

class TaskController extends Controller
{
   public function __construct($action)
   {
      parent::__construct($action);
      $this->rules = new TaskRules();
      $this->task = new Task();
   }

   public function createAction()
   {
      $data = $this->getData(['name', 'description', 'project_id'], true);
      $this->task->authorize($data->user_id, $data->project_id);

      [$validateStatus, $validateMessages] = $this->validate((array) $data, $this->rules);

      if ($validateStatus) {
         $task = $this->task->create((array) $data);
         $this->response->success($this->createObject([$task], ['task']));
      }

      $this->response->validateError($validateMessages);
   }
}
