<?php

namespace Anax\Answers;

/**
 * A controller for answers.
 *
 */
class AnswersController implements \Anax\DI\IInjectionAware
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
        $this->answers = new \Anax\Answers\Answer();
        $this->answers->setDI($this->di);
        $this->users = new \Anax\Users\UsersController();
        $this->users->setDI($this->di);
    }


    /**
     * List answers by question id
     *
     * @return void
     */
    public function listAction($id)
    {     
        $question = $this->answers->findByQuestion($id);
        return $question;
    }

    public function findAnswersAction($id)
    {     
        $finduser = $this->users->find($id);
        $answer = $this->answers->findAnswersByUsers($id);
        $this->theme->setTitle("answers");
        $this->views->add('users/answers', [
            'findByUser' => $answer,
            'email' => isset($answer[0]->email) ? $answer[0]->email : $finduser->email,
            'username' => isset($answer[0]->username) ? $answer[0]->username : $finduser->username ,
            'loggedInId' => $id,
        ]);
    }

    public function answersAction($id = null)
    {
        if(isset($id))
        {
            $answers = $this->answers->query('COUNT(questionid) AS Count')
            ->join('question', 'phpmvc_answer.questionid = phpmvc_question.id')
            ->where('phpmvc_answer.questionid = ?')
            ->execute([$id]);
            return $answers;
        }

        $answers = $this->answers->query()
        ->join('question', 'phpmvc_answer.questionid = phpmvc_question.id')
        ->groupBy('questionid')
        ->execute();

        return $answers;
    }


 
    public function addAction($answer = null,$userid = null,$questionid = null)
    {
        date_default_timezone_set('Europe/Stockholm');
        $now = date("Y-m-d H:i:s");
        if(!$this->di->session->get('user')) {
            $this->redirectTo('login');
        }

        $sql = $this->answers->save([
                'answer' => $this->di->textFilter->doFilter($answer,'shortcode, markdown'),
                'userid' =>  $userid,
                'questionid' => $questionid,
                'added' => $now,
            ]);
     
        return $sql;

    }


    
}
