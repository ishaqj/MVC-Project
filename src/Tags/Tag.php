<?php

namespace Anax\Tags;

/**
 * Model for Tags.
 *
 */
class Tag extends \Anax\MVC\CDatabaseModel {


    /**
     * Find and return specific from name.
     *
     * @return this
     */
    public function findByName($questionid,$tag)
    {
        $this->db->query()
                 ->where("questionid = ?")
                 ->andWhere('tag = ?');
     
        $this->db->execute([$questionid,$tag]);
        return $this->db->fetchInto($this);
    }

}