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

   public function setValidateMessages($validateMessages)
   {
      $this->validateMessages = $validateMessages;
   }

   public function success($data = null)
   {
      $this->setStatus(200);
      $this->setData($data);
      $this->send();
   }

   public function error($status, $validateMessages)
   {
      $this->setStatus($status);
      $this->setValidateMessages($validateMessages);
      $this->send();
   }

   public function send()
   {
      echo json_encode($this);
      exit();
   }
}
