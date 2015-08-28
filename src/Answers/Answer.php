<?php

namespace Anax\Answers;

/**
 * Model for Users
 *
 */
class Answer extends \Anax\MVC\CDatabaseModel
{
    public function findByQuestion($id)
    {
        $this->db->select('*,phpmvc_answer.id AS answerID')
         ->from($this->getSource())
         ->join('user', 'phpmvc_answer.userid = phpmvc_user.id')
         ->where("questionid = ?");
          
        $this->db->execute([$id]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }

    public function findAnswersByUsers($id)
    {
        $this->db->select('*')
        ->from($this->getSource())
        ->join('user', 'phpmvc_answer.userid = phpmvc_user.id')
        ->join('question', 'phpmvc_answer.questionid = phpmvc_question.id')
        ->where("phpmvc_answer.userid = ?")
        ->orderBy('phpmvc_answer.id DESC');
     
        $this->db->execute([$id]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }

}
