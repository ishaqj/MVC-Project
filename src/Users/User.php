<?php

namespace Anax\Users;

/**
 * Model for Users
 *
 */
class User extends \Anax\MVC\CDatabaseModel
{

    /**
     * checks if  users is logged in
     *
     * @return void
     */
      public function loggedIn()
      {
        if($this->di->session->get('user'))
        {
            return true;
        }
        return false;
      }
}
