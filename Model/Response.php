<?php

namespace Model;

class Response
{
   public function setStatus($status)
   {
      $this->status = $status;
   }

   public function setData($data)
   {
      $this->data = $data;
   }

   public function setMessage($message)
   {
      $this->message = $message;
   }

   public function success($data = null)
   {
      $this->setStatus(200);
      $this->setData($data);
   }

   public function error($status, $message)
   {
      $this->setStatus($status);
      $this->setMessage($message);
   }

   public function send()
   {
      echo json_encode($this);
      exit();
   }
}
