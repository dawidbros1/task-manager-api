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
      $input = $this->getData(['name', 'description', 'project_id'], true);
      $this->task->authorize($input->user_id, $input->project_id);

      [$validateStatus, $validateMessages] = $this->validate((array) $input, $this->rules);

      if ($validateStatus) {
         $task = $this->task->create((array) $input);
         $this->response->success($this->createObject([$task], ['task']));
      }

      $this->response->validateError($validateMessages);
   }

   public function updateAction()
   {
      $input = $this->getData(['id', 'name', 'description'], true);
      $task = $this->task->get($input->id, $input->user_id);

      [$validateStatus, $validateMessages] = $this->validate((array) $input, $this->rules);

      if ($validateStatus) {
         $this->task->update((array) $input);
         $this->response->success();
      }

      $this->response->validateError($validateMessages);
   }
}
