<?php

namespace Anax\Question;

/**
 * Model for Questions
 *
 */
class Question extends \Anax\MVC\CDatabaseModel
{

    public function findQuestionsByUsers($id)
    {
        $this->db->select('*, phpmvc_question.id as qId')
        ->from($this->getSource())
        ->join('user', 'phpmvc_question.userid = phpmvc_user.id')
        ->where("phpmvc_question.userid = ?")
        ->orderBy('phpmvc_question.id DESC');
     
        $this->db->execute([$id]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }


}
