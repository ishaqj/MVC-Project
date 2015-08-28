<?php

namespace Anax\Comments;

/**
 * Model for Comments.
 *
 */
class Comment extends \Anax\MVC\CDatabaseModel
{

    public function findBycomments($id,$type)
    {
        $this->db->select()
                 ->from($this->getSource())
                 ->join('user', 'phpmvc_comment.userid = phpmvc_user.id')
                 ->where("caID = ?")
                 ->andWhere('type= ?');

        $this->db->execute([$id,$type]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }

    public function findByanswers($id,$type)
    {
        $this->db->select()
                 ->from($this->getSource('*,phpmvc_comment.id AS commentID '))
                 ->join('user', 'phpmvc_comment.userid = phpmvc_user.id')
                 ->join('answer', 'phpmvc_comment.caID = phpmvc_answer.id')
                 ->where("phpmvc_answer.questionid = ?")
                 ->andWhere('type= ?');

        $this->db->execute([$id,$type]);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }
}
