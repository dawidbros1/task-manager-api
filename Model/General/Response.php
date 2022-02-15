<?php

namespace Model\General;

class Response
{
   public function success(?object $data = null)
   {
      $this->status = 200;
      $this->data = $data;
      $this->send();
   }

   public function error($status, $description = "")
   {
      $this->status = $status;
      $this->description = $description;
      $this->send();
   }

   public function validateError($validateMessages, $description = "")
   {
      $this->status = 403;
      $this->validateMessages = $validateMessages;
      $this->description = $description;
      $this->send();
   }

   public function send()
   {
      echo json_encode($this);
      exit();
   }
}
