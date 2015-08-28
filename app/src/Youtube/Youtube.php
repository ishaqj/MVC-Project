<?php

namespace Ishaq\Youtube;

/**
 * Model for Users
 *
 */
class Youtube extends \Anax\MVC\CDatabaseModel
{

    /**
     * Find and return specific from name.
     *
     * @return this
     */
    public function findByName($video)
    {
        $this->db->select()
                 ->from($this->getSource())
                 ->where("title = ?");
        $this->db->execute([$video]);
        return $this->db->fetchInto($this);
    }

        /**
     * Search videos by name.
     *
     * @return fetchAll
     */
    public function searchByName($video)
    {
        $this->db->select()
                 ->from($this->getSource())
                 ->where("title LIKE ?");
        $this->db->execute(['%'.$video.'%']);
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }

    /**
     * Save current object/row.
     *
     * @param array $values key/values to save or empty to use object properties.
     *
     * @return boolean true or false if saving went okey.
     */
    public function save($values = [])
    {
        $this->setProperties($values);
        $values = $this->getProperties();
     
        if (isset($values['id'])) {
            return $this->update($values);
        } else {
            return $this->create($values);
        }
    }
}
