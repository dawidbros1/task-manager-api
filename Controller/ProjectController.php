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
      $data = $this->getData(['user_id']);
      $projects = $this->project->get($data->user_id);
      $this->response->success($projects);
   }

   public function createAction()
   {
      $data = $this->getData(['name', 'description'], true);

      [$validateStatus, $validateMessages] = $this->validate((array) $data, $this->rules);

      if ($validateStatus) {
         $this->project->create((array) $data);
         $this->response->success();
      }

      $this->response->error(403, $validateMessages);
   }

   // public function updateAction()
   // {
   // }

   // public function deleteAction()
   // {
   // }
}
