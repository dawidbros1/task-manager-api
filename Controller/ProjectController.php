<?php

namespace Controller;

use Model\Project;
use Rules\ProjectRules;

class ProjectController extends Controller
{
   public function __construct($action)
   {
      parent::__construct($action);
      $this->rules = new ProjectRules();
      $this->project = new Project();
   }

   public function getAction()
   {
      $data = $this->getData(['id'], true);
      // ID | USER_ID | SIDEKEY

      $project = $this->project->get($data->id, $data->user_id);
      $tasks = $this->project->getTasks($data->id);

      $this->response->success($this->createObject(
         [$project, $tasks],
         ['project', 'tasks']
      ));
   }

   public function getAllAction()
   {
      $data = $this->getData(['user_id']);
      $projects = $this->project->getAll($data->user_id);
      $this->response->success($this->createObject([$projects], ['projects']));
   }

   public function createAction()
   {
      $data = $this->getData(['name', 'description'], true);

      [$validateStatus, $validateMessages] = $this->validate((array) $data, $this->rules);

      if ($validateStatus) {
         $project = $this->project->create((array) $data);
         $this->response->success($this->createObject([$project], ['project']));
      }

      $this->response->validateError($validateMessages);
   }

   public function updateAction()
   {
      $data = $this->getData(['id', 'name', 'description'], true);

      if ($this->project->get($data->id, $data->user_id)) {
         $this->project->update((array) $data);
         $this->response->success();
      }
   }

   public function deleteAction()
   {
      $data = $this->getData(['id'], true);

      if ($this->project->get($data->id, $data->user_id)) {
         $this->project->delete((array) $data);
         $this->response->success();
      }
   }
}
