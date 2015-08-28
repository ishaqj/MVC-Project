<?php

namespace Anax\Users;

/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
        use \Anax\DI\TInjectable,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
        $this->answer = new \Anax\Answers\Answer();
        $this->answer->setDI($this->di);
    }


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

    /**
     * List all users.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->initialize();
     
        $all = $this->users->findAll();
     
        $this->theme->setTitle("List all users");
        $this->views->add('users/list-all', [
            'users' => $all,
        ], 'main');
    }


    /**
     * List user with id.
     *
     * @param int $id of user to display
     *
     * @return void
     */
    public function idAction($id = null)
    {
        $user = $this->users->find($id);

        $questions = $this->users->query()
        ->join('question', 'phpmvc_user.id = phpmvc_question.userid')
        ->where('phpmvc_user.id = ?')
        ->orderBy('phpmvc_question.id DESC')
        ->limit(2)
        ->execute([$id]);

        $answers = $this->users->query()
        ->join('answer', 'phpmvc_user.id = phpmvc_answer.userid')
        ->join('question', 'phpmvc_answer.questionid = phpmvc_question.id')
        ->where('phpmvc_user.id = ?')
        ->orderBy('phpmvc_answer.id DESC')
        ->limit(2)
        ->execute([$id]);

        $findByUser = $this->answer->findAnswersByUsers($id);

        foreach ($questions as $question)
        {
            $totalAnswers = $this->di->dispatcher->forward([
                'controller' => 'answers',
                'action' => 'answers',
                'params'     => [$question->id],
                ]);

            $question->svar = $totalAnswers;
        }

        foreach ($answers as $answer)
        {
            $totalAnswers = $this->di->dispatcher->forward([
                'controller' => 'answers',
                'action' => 'answers',
                'params'     => [$answer->questionid],
                ]);
            $answer->svar = $totalAnswers;


        }
     
        $this->theme->setTitle(isset($user->username) ? $user->username : 'no user found');
        $this->views->add('users/view', [
            'question' => $questions,
            'answer' => $answers,
            'user' => $user,
            'loggedInId' => isset($id) ? $id : null,
        ]);

    }


    public function popularAction() {

        $all = $this->users->query('*,COUNT(*) AS count,phpmvc_user.id AS uid')
            ->join('question','phpmvc_user.id = phpmvc_question.userid')
            ->groupBy('phpmvc_user.id')
            ->limit(10)
            ->orderBy('count DESC')
            ->execute();

         return $all;   

    }


 public function registerAction() {
        $this->di->theme->setTitle('Register');

       if($this->di->session->get('user') != null) {
            $url = $this->url->create('users/logout');
            $this->response->redirect($url);
        }
 $form = $this->form;
        $form = $this->form->create([], [
            'username' => [
                'type'        => 'text',
                'label'       => 'Username',
                'class' => 'form-control',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['username']['value']) ? $_SESSION['form-save']['username']['value'] : null,
            ],
            'email' => [
                'type'        => 'email',
                'class' => 'form-control',
                'label'       => 'E-mail Address',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => isset($_SESSION['form-save']['email']['value']) ? $_SESSION['form-save']['email']['value'] : null,
            ],
            'emailCnfrm' => [
                'type'        => 'email',
                'class' => 'form-control',
                'label'       => 'Retype E-mail Address',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => isset($_SESSION['form-save']['emailCnfrm']['value']) ? $_SESSION['form-save']['emailCnfrm']['value'] : null,
            ],
            'password' => [
                'type'        => 'password',
                'class' => 'form-control',
                'label'       => 'Password',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['password']['value']) ? $_SESSION['form-save']['password']['value'] : null,
            ],
            'passwordCnfrm' => [
                'type'        => 'password',
                'class' => 'form-control',
                'label'       => 'Retype Password',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['passwordCnfrm']['value']) ? $_SESSION['form-save']['passwordCnfrm']['value'] : null,
            ],
            'register' => [
                'type'      => 'submit',
                'class'     => 'btn btn-primary',
                'callback'  => function($form) {
                    $form->saveInSession = true;
                    return true;
                }
            ],
            'reset' => [
                'type'      => 'reset',
                'class'     => 'btn btn-danger',
            ]
        ]);

        // Prepare the page content
        $this->views->add('users/form', [
            'header' => 'Register',
            'register' => $form->getHTML(),
        ]);

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {

            if($this->users->findBy('username', $_SESSION['form-save']['username']['value'])) {
                $this->di->flashmessage->addError('The username is already in use!');
                $this->redirectTo();
            }
            else if($_SESSION['form-save']['email']['value'] != $_SESSION['form-save']['emailCnfrm']['value']) {
                $this->di->flashmessage->addError('The e-mail addresses did not match!');
                $this->redirectTo();
            }
            else if(strlen($_SESSION['form-save']['password']['value']) < 6) {
                $this->di->flashmessage->addError('The password is too short!');
                $this->redirectTo();
            }
            else if($_SESSION['form-save']['password']['value'] != $_SESSION['form-save']['passwordCnfrm']['value']) {
                $this->di->flashmessage->addError('The passwords did not match!');
                $this->redirectTo();
            }

                date_default_timezone_set('Europe/Stockholm');
                $now = date("Y-m-d H:i:s");
            
            $res = $this->users->save([
                'username' => $_SESSION['form-save']['username']['value'],
                'email' => $_SESSION['form-save']['email']['value'],
                'password' => md5(strtolower(trim($_SESSION['form-save']['password']['value']))),
                'created' => $now,
            ]);

            if($res) {
                $this->di->flashmessage->addSuccess('Success! Your account has been created. You may now login.');
                unset($_SESSION['form-save']);
            }

            $this->redirectTo('');
        } else if ($status === false) {   
            $this->di->flashmessage->addError('We were unable to process your registration. Please try again');
            $this->redirectTo();
        }
    }

   public function loginAction() {
        $this->di->theme->setTitle('Login');
        if($this->di->session->get('user') != null) {
            $url = $this->url->create('users/logout');
            $this->response->redirect($url);
        }
        $form = $this->form->create(['role' => 'form'], [
            'username' => [
                'type'        => 'text',
                'class'     => 'form-control',
                'label'       => 'Username',
                'required'    => true,
                'autofocus'   => true,
                'validation'  => ['not_empty'],
            ],
            'password' => [
                'type'        => 'password',
                'class'     => 'form-control',
                'label'       => 'Password',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'login' => [
                'type'      => 'submit',
                'class'     => 'btn btn-primary',
                'callback'  => function($form) {
                    $form->saveInSession = true;
                    return true;
                }
            ]
        ]);
        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {
            $user = $this->users->query()
                ->where('username = ?')
                ->andWhere('password = ?')
                ->execute([$_SESSION['form-save']['username']['value'], md5(strtolower(trim($_SESSION['form-save']['password']['value'])))]);
            if(!$this->users->count()) {
                $this->di->flashmessage->addError('Incorrect username and/or password');
                $this->redirectTo();
            }
            else {
            $this->di->session->set('user', $user);
            unset($_SESSION['form-save']);
             $this->redirectTo('');
           }
        } else if ($status === false) {   
            $this->di->flashmessage->addError('Unable to login. Please try again.');
            $this->redirectTo('');
        }
        // Prepare the page content
        $this->views->add('users/form', [
            'header' => 'Login',
            'login' => $form->getHTML(),
        ]);
    }
    public function logoutAction() {
        if($this->di->session->get('user') != null) {
            $this->di->session->set('user', null);
        }
        $this->redirectTo('');
    }




    /**
     * Add new user.
     *
     * @param string $acronym of user to add.
     *
     * @return void
     */
    public function addAction($acronym = null)
    {
        $this->initialize();
        $this->theme->setTitle("Add user");

        if (!isset($acronym)) {
            $form = $this->form;

            $form = $form->create([], [
                'acronym' => [
                    'type'        => 'text',
                    'label'       => 'Username:',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                ],
                'submit' => [
                    'type'      => 'submit',
                    'callback'  => function($form) {
                        $form->saveInSession = true;
                        return true;
                    }
                ],
            ]);

            // Check the status of the form
            $status = $form->check();

            if ($status === true) {
                // What to do if the form was submitted?
                $acronym = $_SESSION['form-save']['acronym']['value'];
                session_unset($_SESSION['form-save']);
                $url = $this->url->create('users/add/' . $acronym);
                $this->response->redirect($url);
            }

            $this->views->add('me/page', [
                'content' => $form->getHTML(),
            ]);

        } else {
     
        date_default_timezone_set('Europe/Stockholm');
        $now = date("Y-m-d H:i:s");
         
            $this->users->save([
                'acronym' => $acronym,
                'email' => $acronym . '@mail.se',
                'name' => 'Mr/Mrs ' . $acronym,
                'password' => password_hash($acronym, PASSWORD_DEFAULT),
                'created' => $now,
                'active' => $now,
            ]);
            
            $url = $this->url->create('users/id/' . $this->users->id);
            $this->response->redirect($url);
        }
    }


    /**
     * Delete user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function deleteAction($id = null)
    {
        $this->initialize();
        $this->theme->setTitle("Delete user");

        if (!isset($id)) {
            $form = $this->form;

            $form = $form->create([], [
                'id' => [
                    'type'        => 'text',
                    'label'       => 'Username or ID:',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                ],
                'submit' => [
                    'type'      => 'submit',
                    'callback'  => function($form) {
                        $form->saveInSession = true;
                        return true;
                    }
                ],
            ]);

            // Check the status of the form
            $status = $form->check();

            if ($status === true) {
                // What to do if the form was submitted?
                $id = $_SESSION['form-save']['id']['value'];
                session_unset($_SESSION['form-save']);
                $url = $this->url->create('users/delete/' . $id);
                $this->response->redirect($url);
            }

            $this->views->add('me/page', [
                'content' => $form->getHTML(),
            ]);

        } else {

            if(!is_numeric($id)) {
                $user = $this->users->findByName($id);
                
                if(!is_numeric($id)) {
                    $id = $this->users->findByName($id)->id;
                }
            }
     
            $res = $this->users->delete($id);
     
            $url = $this->url->create('users/list');
            $this->response->redirect($url);
        }
    }


    /**
     * List all active and not deleted users.
     *
     * @return void
     */
    public function activeAction()
    {
        $this->initialize();

        $all = $this->users->query()
            ->where('active IS NOT NULL')
            ->andWhere('deleted is NULL')
            ->execute();
     
        $this->theme->setTitle("Users that are active");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Users that are active",
        ]);
    }


    /**
     * List all inactive and deleted users.
     *
     * @return void
     */
    public function inactiveAction()
    {
        $this->initialize();

        $all = $this->users->query()
            ->where('deleted IS NOT NULL')
            ->execute();
     
        $this->theme->setTitle("Users that are inactive");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Users that are inactive",
        ]);
    }


    /**
     * Delete (soft) user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function softDeleteAction($id = null)
    {
        $this->initialize();

        if (!isset($id)) {
            die("Missing id");
        }
     
        $now = date("Y-m-d h:i:s");
     
        $user = $this->users->find($id);
     
        $user->deleted = $now;
        $user->save();
     
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }

    /**
     * Undo (soft) user.
     *
     * @param integer $id of user to undo.
     *
     * @return void
     */
    public function softUndoAction($id = null)
    {
        $this->initialize();

        if (!isset($id)) {
            die("Missing id");
        }
     
        $now = date("Y-m-d h:i:s");
     
        $user = $this->users->find($id);
     
        $user->deleted = NULL;
        $user->active = $now;
        $user->save();
     
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }

    /**
     * Update user.
     *
     * @param integer $id of user to update.
     *
     * @return void
     */
    public function updateAction()
    {

        if (!$this->loggedIn()) {
            $url = $this->url->create('users/login/');
            $this->response->redirect($url);
        }

        $user = $this->users->find($this->di->session->get('user')[0]->id);


            $form = $this->form;
            $form = $form->create([], [
                'id' => [
                    'type' => 'hidden',
                    'label' => 'id',
                    'class' => 'form-control',
                    'required' => true,
                    'validation' => ['not_empty'],
                    'value' => $user->id,
                ],
                'username' => [
                    'type' => 'text',
                    'label' => 'Username',
                    'class' => 'form-control',
                    'required' => true,
                    'validation' => ['not_empty'],
                    'value' => $user->username,
                ],
                'email' => [
                    'type' => 'text',
                    'label' => 'Email',
                    'class' => 'form-control',
                    'required' => true,
                    'validation' => ['not_empty'],
                    'value' => $user->email,
                ],
                'password' => [
                    'type' => 'password',
                    'label' => 'Password',
                    'class' => 'form-control',
                    'required' => false,
                    'placeholder' => 'Optional',
                ],

                'submit' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'callback' => function ($form) {
                        $form->saveInSession = true;
                        return true;
                    }
                ],

            ]);

            $status = $form->check();
            if ($status === true) {
            if($this->users->query()->where('username = ?')->andWhere('id <> ?')->execute([$_SESSION['form-save']['username']['value'],$this->di->session->get('user')[0]->id])) {
                $this->di->flashmessage->addError('The username is already in use!');
                $this->redirectTo();
                exit();
            }
            elseif($_SESSION['form-save']['password']['value'] != null)
            {
                if(strlen($_SESSION['form-save']['password']['value']) < 6)
                {
                $this->di->flashmessage->addError('Password is too short!');
                $this->redirectTo();
                exit();
                }
            }                
                if($_SESSION['form-save']['password']['value'] != null)
                {
                    $user->username = $_SESSION['form-save']['username']['value'];
                    $user->email = $_SESSION['form-save']['email']['value'];
                    $user->password = md5(strtolower(trim($_SESSION['form-save']['password']['value'])));
                    $user->save();
                    unset($_SESSION['form-save']);

                if($this->users->count())
                {
                    $this->di->flashmessage->addSuccess('Success! Settings saved.');
                    $this->redirectTo();
                    
                }
                else {
                    $this->di->flashmessage->addError('Settings not saved. Change something first');
                    $this->redirectTo();
                }

                }
                else {
                    $user->username = $_SESSION['form-save']['username']['value'];
                    $user->email = $_SESSION['form-save']['email']['value'];
                    $user->save();
                    unset($_SESSION['form-save']);

                if($this->users->count())
                {
                    $this->di->flashmessage->addSuccess('Success! Settings saved.');
                    $this->redirectTo();
                 
                }


                }

                
            }

            $this->theme->setTitle("Update " .$user->username);
            $this->views->add('users/update', [
                'user'  => $user,
                'content' => $form->getHTML(),
            ]);

    }

    /**
     * Save user info.
     *
     * @return void
     */
    public function saveAction()
    {
        $this->initialize();

        $isPosted = $this->request->getPost('doSave');

        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $id = $this->request->getPost('id');
        $email = $this->request->getPost('email');
        $name = $this->request->getPost('name');

        $user = $this->users->find($id);
     
        $now = date("Y-m-d h:i:s");
        
        $user->email = $email;
        $user->name = $name;
        $user->updated = $now;
        $user->save();
     
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }
}
