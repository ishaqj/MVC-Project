<?php

namespace Anax\Tags;
 
/**
 * A controller for tags.
 *
 */
class TagsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
        \Anax\MVC\TRedirectHelpers;
    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize() {
        $this->tags = new \Anax\Tags\Tag();
        $this->tags->setDI($this->di);
    }

public function indexAction() {
        $this->di->theme->setTitle('Tags');

        $all = $this->tags->query('tag,COUNT(*) AS count')
            ->groupBy('tag')
            ->orderBy('count DESC')
            ->execute();

        $this->views->add('tags/list-all', [
            'tags' => $all,
            'header' => "Tags",
        ], 'main');
    }

    public function popularTagsAction()
    {
        $tags = $this->tags->query('tag, COUNT(*) AS tagCount')
            ->groupBy('tag')
            ->orderBy('tagCount DESC')
            ->limit(10)
            ->execute();

            return $tags;
    }


    public function tagAction($slug = null)
    { 
        if(!$slug)
        {
            $this->redirectTo('');
        }

        $questions = $this->tags->query()
            ->join('user', 'phpmvc_tag.userid = phpmvc_user.id')
            ->join('question','phpmvc_tag.questionid = phpmvc_question.id')
            ->where('phpmvc_tag.tag = ?')
            ->execute([$slug]);

            $tags = $this->tags->findAll();

            foreach ($questions as $question) {
             $tagArray = array();
            foreach($tags as $tag)
            {

            if($tag->questionid == $question->id) {
                    $tagArray[] = $tag;
                }
            }
                $question->tags = $tagArray;
            }

            $this->theme->setTitle('Tags');
            $this->views->add('questions/tags', [
                'questions'  => $questions,
                'header' => 'Tagged Questions'
            ]);    

    }
    public function tagsAction($id = null)
    {
        if($id != null)
        {
            $tags = $this->tags->query('tag')
            ->where('questionid = ?')
            ->groupBy('tag')
            ->orderBy('id ASC')
            ->execute([$id]);

        }

        else {
            $tags = $this->tags->findAll();
        }

        return $tags;
    }

    public function latestAction()
    { 


        $questions = $this->tags->query()
            ->join('user', 'phpmvc_tag.userid = phpmvc_user.id')
            ->join('question','phpmvc_tag.questionid = phpmvc_question.id')
            ->groupBy('title')
            ->limit(5)
            ->execute();

            $tags = $this->tags->findAll();

            foreach ($questions as $question) {
             $tagArray = array();
            foreach($tags as $tag)
            {

            if($tag->questionid == $question->id) {
                    $tagArray[] = $tag;
                }
            }
                $question->tags = $tagArray;
            }

            $this->theme->setTitle('Tags');
            $this->views->add('questions/tags', [
                'questions'  => $questions,
                'header' => 'Tagged Questions'
            ]);    

    }
    public function processtagsAction($string = null)
    {
        if($string != null) {
            $string = strtolower($string);
            $rawTags = explode(',', $string);
            $tags = array();

            foreach($rawTags as $tag) {
                $tag = trim($tag);
                $tag = str_replace(' ', '-', $tag);
                $tags[] = $tag;
            }

            $tags = array_unique($tags);

            return $tags;
        }

        return false;
    }



    public function settagsAction($tags = null, $userId = null, $postId = null)
    {
        if($postId != null && $userId != null && $tags != null) {
            
            foreach($tags as $tag) {
                $this->tags->create([
                    'tag' => $tag,
                    'userid' => $userId,
                    'questionid' => $postId,
                ]);
            }

            return true;
        }

        return false;
    }

    /**
     * Reset and setup database tabel with default users.
     *
     * @return void
     */
    public function setupAction() 
    {
        // Enable the following line once you setup the user databse for the first time in order to protect it from getting restored by anyone.
        /*if($this->di->session->get('users')[0]->username != "Admin") {
            $this->di->informer->setMessage(['type' => 'warning', 'message' => 'Warning! You do not have permission to do that.']);
            $this->redirectTo('');
        }*/

        $table = [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'userId' => ['integer', 'not null'],
            'postId' => ['integer', 'not null'],
            'tag' => ['varchar(80)'],
        ];

        $res = $this->tags->setupTable($table);

        $this->informer->setMessage(array(
            'type' => 'success',
            'message' => 'Tag database has been wiped!'
        ));
     
        $url = $this->url->create('tags');
        $this->response->redirect($url);
    }
 
}
