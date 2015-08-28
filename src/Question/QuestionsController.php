<?php

namespace Anax\Question;

/**
 * A controller for Questions.
 *
 */
class QuestionsController implements \Anax\DI\IInjectionAware
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
        $this->questions = new \Anax\Question\Question();
        $this->questions->setDI($this->di);
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
        $this->answer = new \Anax\Answers\Answer();
        $this->answer->setDI($this->di);
    }

    public function indexAction()
    {
        $questions = $this->questions->query('*, phpmvc_question.id AS qid')
            ->join('user', 'phpmvc_question.userid = phpmvc_user.id')
            ->join('tag','phpmvc_question.id = phpmvc_tag.questionid')
            ->groupBy('title')
            ->orderBy('qid DESC')
            ->limit(5)
            ->execute();

            $tags = $this->di->dispatcher->forward([
                'controller' => 'tags',
                'action'     => 'tags',
                'params'     => [],
                ]);

            $totalAnswers = $this->di->dispatcher->forward([
                'controller' => 'answers',
                'action' => 'answers',
                'params'     => [],
                ]);


        foreach ($questions as $question)
        {  
            $ans = array();

            foreach ($totalAnswers as $answer) {
                if($answer->questionid == $question->qid)
                {
                    $ans = $this->di->dispatcher->forward([
                    'controller' => 'answers',
                    'action' => 'answers',
                    'params'     => [$answer->questionid],
                    ]);
                     
                }
                
            }

            $question->svar = $ans;
        }


            foreach ($questions as $question) {
             $tagArray = array();
            foreach($tags as $tag)
            {

            if($tag->questionid == $question->qid) {
                    $tagArray[] = $tag;
                }
            }
                $question->tags = $tagArray;
            }

        $this->theme->setTitle('Latest Questions');
        $this->views->add('questions/main', [
            'questions' => $questions,
            'title' => isset($question->title) ? $question->title : null,

        ]);
    }


    public function voteAction($id = null, $values = null)
    {
        if(!$this->users->loggedIn())
        {
            $this->di->flashmessage->addError('You must login first!');
            $this->redirectTo('users/login');
            exit();
            
        }

        $this->questions->find($id);
        if(!$this->questions->count())
        {
            $this->di->flashmessage->addError('Question does not exist!');
            $this->redirectTo('');

        }

        elseif($values == 'upvote')
        {
            $question = $this->questions->selecta('*','vote')
            ->where('anscomID = ?')
            ->andWhere('type = ?')
            ->andWhere('userid = ?')
            ->execute([$id,'question',$this->di->session->get('user')[0]->id]);
            if(!$this->questions->count())
            {
                $test = $this->questions->insert([
                'userid' => $this->di->session->get('user')[0]->id,
                'anscomID' => $id,
                'type' => 'question',
            ],'vote');
                
                $this->di->flashmessage->addSuccess('Voted successfully');
                $this->response->redirect($_SERVER['HTTP_REFERER']);
            }


            else {

                $this->di->flashmessage->addError('You have Already Voted!');
                 $this->response->redirect($_SERVER['HTTP_REFERER']);
            }


        }

        elseif($values == 'downvote')
        {
            $deleteID = $this->questions->selecta('*','vote')
            ->where('anscomID = ?')
            ->andWhere('type = ?')
            ->andWhere('userid = ?')
            ->execute([$id,'question',$this->di->session->get('user')[0]->id]);
            if($this->questions->count())
            {
                $this->questions->delete($deleteID[0]->id,'vote');
                $this->di->flashmessage->addError('Voted down successfully!');
                 $this->response->redirect($_SERVER['HTTP_REFERER']);
            }
            elseif(empty($deleteID))
            {
                 $this->di->flashmessage->addError('You cannot do this');
                 $this->response->redirect($_SERVER['HTTP_REFERER']);
            }
            else {

                $this->di->flashmessage->addError('You have already Voted!');
                 $this->response->redirect($_SERVER['HTTP_REFERER']);
            }


        }

        return $values;
    }

    public function voteAnsAction($id = null, $values = null)
    {
        if(!$this->users->loggedIn())
        {
            $this->di->flashmessage->addError('You must login first!');
            $this->redirectTo('users/login');
            exit();
            
        }

        $this->answer->find($id);
        if(!$this->answer->count())
        {
            $this->di->flashmessage->addError('Question does not exist!');
            $this->redirectTo('');

        }

        elseif($values == 'upvote')
        {
            $this->questions->selecta('*','vote')
            ->where('anscomID = ?')
            ->andWhere('type = ?')
            ->andWhere('userid = ?')
            ->execute([$id,'answer',$this->di->session->get('user')[0]->id]);
            if(!$this->questions->count())
            {
                $this->questions->insert([
                'userid' => $this->di->session->get('user')[0]->id,
                'anscomID' => $id,
                'type' => 'answer',
            ],'vote');
                
                $this->di->flashmessage->addSuccess('Voted successfully');
                $this->response->redirect($_SERVER['HTTP_REFERER']);
            }

            else {
                $this->di->flashmessage->addError('You have Already Voted!');
               $this->response->redirect($_SERVER['HTTP_REFERER']);
            }


        }

        elseif($values == 'downvote')
        {
            $deleteID = $this->questions->selecta('*','vote')
            ->where('anscomID = ?')
            ->andWhere('type = ?')
            ->andWhere('userid = ?')
            ->execute([$id,'answer',$this->di->session->get('user')[0]->id]);
            if($this->questions->count())
            {
                $this->questions->delete($deleteID[0]->id,'vote');
                $this->di->flashmessage->addError('Voted down successfully');
                $this->response->redirect($_SERVER['HTTP_REFERER']);
            }

            elseif(empty($deleteID))
            {
                 $this->di->flashmessage->addError('You cannot do this');
                 $this->response->redirect($_SERVER['HTTP_REFERER']);
            }
            else {
                $this->di->flashmessage->addError('You have already Voted!');
                $this->response->redirect($_SERVER['HTTP_REFERER']);;
            }


        }

        return $values;


    }
    /**
     * List all Questions.
     *
     */
    public function listAction()
    {
        $questions = $this->questions->query('*, phpmvc_question.id AS qid')
            ->join('user', 'phpmvc_question.userid = phpmvc_user.id')
            ->join('tag','phpmvc_question.id = phpmvc_tag.questionid')
            ->groupBy('title')
            ->orderBy('qid DESC')
            ->limit(5)
            ->execute();

            $tags = $this->di->dispatcher->forward([
                'controller' => 'tags',
                'action'     => 'tags',
                'params'     => [],
                ]);

            $totalAnswers = $this->di->dispatcher->forward([
                'controller' => 'answers',
                'action' => 'answers',
                'params'     => [],
                ]);


        foreach ($questions as $question)
        {  

            $ans = array();

            foreach ($totalAnswers as $value) {
                if($value->questionid == $question->qid)
                {
                    $ans = $this->di->dispatcher->forward([
                    'controller' => 'answers',
                    'action' => 'answers',
                    'params'     => [$value->questionid],
                    ]);
                     
                }
                
            }

            $question->svar = $ans;


        }


            foreach ($questions as $question) {
             $tagArray = array();
            foreach($tags as $tag)
            {

            if($tag->questionid == $question->qid) {
                    $tagArray[] = $tag;
                }
            }
                $question->tags = $tagArray;
            }

            return $questions;  
    }



    /**
     * List question with id.
     *
     * @param int $id of question to display
     *
     * @return void
     */
    public function idAction($id = null)
    {
        
        $question = $this->questions->query('*,phpmvc_question.id AS questionid ')
            ->join('user', 'phpmvc_question.userid = phpmvc_user.id')
            ->where('phpmvc_question.id = ?')
            ->execute([$id]);

        //tags
        $tags = $this->di->dispatcher->forward([
                'controller' => 'tags',
                'action'     => 'tags',
                'params'     => [$id]
                ]);
        //comments
        $comments = $this->di->dispatcher->forward([
                'controller' => 'comments',
                'action'     => 'list',
                'params'     => [$id,'comments']
                ]);
    //answercomments
    $answercomments = $this->di->dispatcher->forward([
                'controller' => 'comments',
                'action'     => 'listac',
                'params'     => [$id,'answers']
                ]);
        //answers
        $answer = $this->di->dispatcher->forward([
                'controller' => 'answers',
                'action'     => 'list',
                'params'     => [$id]
                ]);

        //answers counter
        $answercounter = $this->di->dispatcher->forward([
                    'controller' => 'answers',
                    'action' => 'answers',
                    'params'     => [$id],
                    ]);


        //votes counter
        $questionVotes = $this->questions->selecta('COUNT(anscomID) AS Count','vote')
        ->where('anscomID = ?')
        ->andWhere('type = ?')
        ->execute([$id,'question']);

        foreach($answer as $answers)
        {   $ans = array();
            $answerVotes = $this->questions->selecta('COUNT(anscomID) AS Count','vote')
            ->where('anscomID = ?')
            ->andWhere('type = ?')
            ->execute([$answers->answerID,'answer']);
            foreach ($answerVotes as $value) {
                $ans[] = $value;
            }

            $answers->svar = $ans;


        }

        // Form
        $form = $this->form;

        $form = $form->create([], [
            'answer' => [
            'type'        => 'textarea',
            'class' => 'form-control',
            'label'       => 'Answer:',
            'required'    => true,
            'validation'  => ['not_empty'],
            'value' => isset($_SESSION['form-save']['answer']['value']) ? $_SESSION['form-save']['answer']['value'] : null,
        ],
            'submit' => [
                'type'      => 'submit',
                'class'      => 'btn btn-primary',
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
            $answers = $this->di->dispatcher->forward([
                'controller' => 'answers',
                'action'     => 'add',
                'params'     => [$_SESSION['form-save']['answer']['value'],$this->di->session->get('user')[0]->id,$id]
                ]);
           
               if(!$answers)
               {
                    $this->di->flashmessage->addError('Failed!');
               }
               else {
                $this->di->flashmessage->addSuccess('Success!');
                unset($_SESSION['form-save']);
              }

            $url = $this->url->create('questions/id/' . $id);
            $this->response->redirect($url);
        } 
        
     
        $this->theme->setTitle($question[0]->title);
        $this->views->add('questions/view', [
            'question' => $question[0],
            'title' => $question[0]->title,
            'comments' => $comments,
            'tags' => $tags,
            'answers' => $answer,
            'answercomments' => $answercomments,
            'content' => $form->getHTML(),
            'loggedIn' => $this->users->loggedIn(),
            'qVotes' => $questionVotes[0]->Count,
            'totalanswers' => $answercounter[0]->Count,
        ]);
    }

 public function addAction() {
        $this->di->theme->setTitle('Add Question');

       if($this->di->session->get('user') == null) {

            $this->redirectTo('users/login');
        }

        date_default_timezone_set('Europe/Stockholm');
        $now = date('Y-m-d H:i:s');
 $form = $this->form;
        $form = $this->form->create([], [
            'title' => [
                'type'        => 'text',
                'label'       => 'Title',
                'class' => 'form-control',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['title']['value']) ? $_SESSION['form-save']['title']['value'] : null,
            ],
            'tags' => [
                'type'        => 'text',
                'label'       => 'Tags',
                'class' => 'form-control',
                'placeholder' => 'Tag 1,Tag 2,Tag 3',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['tags']['value']) ? $_SESSION['form-save']['tags']['value'] : null,
            ],
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Content',
                'class' => 'form-control',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['content']['value']) ? $_SESSION['form-save']['content']['value'] : null,
            ],
            'Submit' => [
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
        $this->views->add('questions/form', [
            'header' => 'Add Question',
            'main' => $form->getHTML(),
        ], 'main');

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {

            $tags = $this->di->dispatcher->forward([
            'controller' => 'tags',
            'action'     => 'processtags',
            'params'     => [
            $_SESSION['form-save']['tags']['value'],
            ],
            ]);

            if($tags == null) {
                $this->di->flashmessage->addError('tags not saved!');
                $this->redirectTo();
            }
      
            $this->questions->save([
                'userid' => $this->di->session->get('user')[0]->id,
                'title' => $_SESSION['form-save']['title']['value'],
                'content' => $this->di->textFilter->doFilter($_SESSION['form-save']['content']['value'], 'shortcode, markdown'),
                'qcreated' => $now,
            ]);

                $this->di->dispatcher->forward([
                'controller' => 'tags',
                'action'     => 'settags',
                'params'     => [
                    $tags,
                    $this->di->session->get('user')[0]->id,
                    $this->questions->id,
                ],
            ]);

            $this->di->flashmessage->addSuccess('Success!');
            unset($_SESSION['form-save']);
                                    
            $url = $this->url->create('questions/id/' . $this->questions->id);
            $this->response->redirect($url);

        } else if ($status === false) {   
            $this->redirectTo();
        }
    }
        public function findAnswersAction($id)
    {     
        $finduser = $this->users->find($id);
        $answer = $this->answer->findAnswersByUsers($id);
     
        $this->theme->setTitle("Answers");
        $this->views->add('users/answers', [
            'title' => "Answers",
            'answer' => $answer,
            'email' => isset($answer[0]->email) ? $answer[0]->email : $finduser->email,
            'username' => isset($answer[0]->username) ? $answer[0]->username : $finduser->username ,
            'loggedInId' => $id,
        ]);
    }
    public function findQuestionsAction($id)
    {     
        $finduser = $this->users->find($id);
        $question = $this->questions->findQuestionsByUsers($id);
        $this->theme->setTitle("Questions");
        $this->views->add('users/answers', [
            'title' => 'Questions',
            'question' => $question,
            'email' => isset($question[0]->email) ? $question[0]->email : $finduser->email,
            'username' => isset($question[0]->username) ? $question[0]->username : $finduser->username ,
            'loggedInId' => $id,
        ]);
    }

}


