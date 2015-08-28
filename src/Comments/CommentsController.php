<?php

namespace Anax\Comments;

/**
 * A controller for comments.
 *
 */
class CommentsController implements \Anax\DI\IInjectionAware
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
        $this->comments = new \Anax\Comments\Comment();
        $this->comments->setDI($this->di);
    }

     /**
     * View all comments.
     *
     * @return void
     */
    public function listAction($id,$type)
    {     
        $comments = $this->comments->findBycomments($id,$type);
     
        return $comments;
    }

     /**
     * View all answers comments.
     *
     * @return void
     */
    public function listacAction($id,$type)
    {     
        $comments = $this->comments->findByanswers($id,$type);
     
        return $comments;
    }

    //adding comments to comments and answers.
     public function addAction($type=null,$questionid=null,$answerid=null)
    {
        $this->di->theme->setTitle('Add Comment');

       if(!$this->di->session->get('user')) {

            $this->redirectTo('users/login');
            exit();
        }
        date_default_timezone_set('Europe/Stockholm');
        $now = date('Y-m-d H:i:s');
         // Form
        $form = $this->form;
        $form = $form->create([], [
            'comment' => [
            'type'        => 'textarea',
            'class' => 'form-control',
            'label'       => 'Comment:',
            'required'    => true,
            'validation'  => ['not_empty'],
            'value' => isset($_SESSION['form-save']['comment']['value']) ? $_SESSION['form-save']['comment']['value'] : null,
        ],
            'submit' => [
                'type'      => 'submit',
                'class'     => 'btn btn-primary',
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
            if($type == 1)
             $comments = $this->comments->save([
                'comment' => $this->di->textFilter->doFilter($_SESSION['form-save']['comment']['value'], 'shortcode, markdown'),
                'userid' => $this->di->session->get('user')[0]->id,
                'caID' => $questionid,
                'type' => 'comments',
                'added' => $now,
            ]);
            else if($type == 2)
            {
             $comments = $this->comments->save([
                'comment' => $this->di->textFilter->doFilter($_SESSION['form-save']['comment']['value'], 'shortcode, markdown'),
                'userid' => $this->di->session->get('user')[0]->id,
                'caID' => $answerid,
                'type' => 'answers',
                'added' => $now,
            ]);
            }
           
               if(!$comments)
               {
                    $this->di->flashmessage->addError('Failed! We could not add your comment. ');
               }
               else {
                $this->di->flashmessage->addSuccess('Success! Comment added!');
                unset($_SESSION['form-save']);
            }
            
            $url = $this->url->create('questions/id/'. $questionid);
            $this->response->redirect($url);
        } 
     
        $this->views->add('questions/form', [
            'main' => $form->getHTML(),
            'header' => 'Add comment',
        ]);


    }
}
