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

      if (!$this->task->authorize($input->user_id, $input->project_id)) {
         $this->response->error(400, "Brak uprawnień do tego projektu");
      }

      [$validateStatus, $validateMessages] = $this->validate((array) $input, $this->rules);

      if ($validateStatus) {
         $task = $this->task->create((array) $input);
         $this->response->success($this->createObject([$task], ['task']));
      }

      $this->response->validateError($validateMessages);
   }

   public function updateAction()
   {
      $this->getTask();

      $input = $this->getData(['name', 'description', 'status']);
      [$validateStatus, $validateMessages] = $this->validate((array) $input, $this->rules);

      if ($validateStatus) {
         $this->task->update((array) $input);
         $this->response->success();
      }

      $this->response->validateError($validateMessages);
   }

   public function deleteAction()
   {
      $task = $this->getTask();
      $this->task->delete((int) $task['id']);
      $this->response->success();
   }

   private function getTask()
   {
      $input = $this->getData(['id'], true);

      if (!$task = $this->task->get($input->id)) {
         $this->response->error(400, "Zasób o podanym ID nie istnieje");
      }

      if (!$this->task->authorize($input->user_id, $task['project_id'])) {
         $this->response->error(400, "Brak uprawnień do tego projektu");
      }

      return $task;
   }
}
