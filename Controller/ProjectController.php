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

   public function createAction()
   {
      $input = $this->getData(['name', 'description'], true);

      [$validateStatus, $validateMessages] = $this->validate((array) $input, $this->rules);

      if ($validateStatus) {
         $project = $this->project->create((array) $input);
         $this->response->success($this->createObject([$project], ['project']));
      }

      $this->response->validateError($validateMessages);
   }

   public function getAction()
   {
      $project = $this->getProject();
      $tasks = $this->project->getTasks($project['id']);

      $this->response->success($this->createObject(
         [$project, $tasks],
         ['project', 'tasks']
      ));
   }

   public function getAllAction()
   {
      $input = $this->getData(['user_id'], true);
      $projects = $this->project->getAll($input->user_id);
      $this->response->success($this->createObject([$projects], ['projects']));
   }


   public function updateAction()
   {
      $this->getProject();
      $input = $this->getData(['id', 'name', 'description'], false);
      $this->project->update((array) $input);
      $this->response->success();
   }

   public function deleteAction()
   {
      $project = $this->getProject();
      $this->project->delete((int) $project['id']);
      $this->response->success();
   }

   // ===== ===== ===== //

   private function getProject()
   {
      $input = $this->getData(['id'], true);

      if (!$project = $this->project->get($input->id, $input->user_id)) {
         $this->response->error(400, "Zasób o podanym ID nie istnieje");
      }

      return $project;
   }
}
