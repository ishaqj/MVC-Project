<?php

namespace Ishaq\Youtube;

/**
 * A controller for users and admin related events.
 *
 */
class YoutubeController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->youtube = new \Ishaq\Youtube\Youtube();
        $this->youtube->setDI($this->di);
    }


    /**
     * List all Videos.
     *
     * @return void
     */
    public function videosAction($video = null)
    {
     
        $all = $this->youtube->findAll();

        if (!isset($video)) {
            $form = $this->form;

            $form = $form->create([], [
                'title' => [
                    'type'        => 'text',
                    'label'       => 'Search Title:',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                ],
                'search' => [
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
                $video = $_SESSION['form-save']['title']['value'];
                session_unset($_SESSION['form-save']);
                $url = $this->url->create('youtube/search/' . $video);
                $this->response->redirect($url);
                
            }

        $this->theme->setTitle("List all Videos");
        $this->views->add('youtube/list-videos', [
            'videos' => $all,
            'title' => "Latest Videos",
            'search' => $form->getHTML(),
        ], 'main');

        } 
         
    }


    /**
     * List videos with id.
     *
     * @param int $id of video to display
     *
     * @return void
     */
    public function idAction($id = null)
    {

        if(is_numeric($id)) {
            $youtube = $this->youtube->find($id);
        } else {
            $youtube = $this->youtube->findByName($id);
        }
     
        $this->theme->setTitle("View video");
        $this->views->add('youtube/view', [
            'videos' => $youtube
        ]);
    }

    /**
     * List video with video name.
     *
     * @param int $video to search
     *
     * @return void
     */
    public function searchAction($video = null)
    {
        $video = str_replace('%20', ' ', $video); 
        $youtube = $this->youtube->searchByName($video);
        $this->views->add('youtube/list-videos', [
                'videos' => $youtube,
                'title' => "Results for ".$video."",
            ]);
    }


    /**
     * Add new user.
     *
     * @param string $acronym of user to add.
     *
     * @return void
     */
    public function addAction($video = null,$description = null, $ytid = null)
    {
        //$this->initialize();
        $this->theme->setTitle("Add Video");

        if (!isset($video)) {
            $form = $this->form;

            $form = $form->create([], [
                'title' => [
                    'type'        => 'text',
                    'label'       => 'Video Title:',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                ],
                'ytid' => [
                    'type'        => 'text',
                    'label'       => 'Youtube Video ID:',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                    'placeholder' => 'Example: UtF6Jej8yb4',
                ],
                 'description' => [
                    'type'        => 'textarea',
                    'label'       => 'Video Description:',
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
                $video = $_SESSION['form-save']['title']['value'];
                $description = $_SESSION['form-save']['description']['value'];
                $ytid = $_SESSION['form-save']['ytid']['value'];
                session_unset($_SESSION['form-save']);

                if(isset($video))
                {
                    $now = date("Y-m-d h:i:s");
                    $this->youtube->save([
                    'title' => $video,
                    'ytid' => $ytid,
                    'description' => $description,
                    'created' => $now
                    ]);
                    
                    $url = $this->url->create('youtube/id/' . $this->youtube->id);
                    $this->response->redirect($url);
                }
            }

            $this->views->add('youtube/page', [
                'content' => $form->getHTML(),
            ]);

        } 
    }


    /**
     * Delete video.
     *
     * @param integer $id of video to delete.
     *
     * @return void
     */
    public function deleteAction($id = null)
    {
        $id = $this->youtube->find($id)->id;
        $res = $this->youtube->delete($id);
        $url = $this->url->create('youtube/videos');
        $this->response->redirect($url);
        
    }


   
    /**
     * Update video.
     *
     * @param integer $id of video to update.
     *
     * @return void
     */
    public function updateAction($id = null)
    {

        if (isset($id)) {
            $youtube = $this->youtube->find($id);
            $form = $this->form;
            $form = $form->create([], [
                'id' => [
                    'type' => 'hidden',
                    'label' => 'id',
                    'required' => true,
                    'validation' => ['not_empty'],
                    'value' => isset($youtube->id) ? $youtube->id : '',
                ],
                'title' => [
                    'type' => 'text',
                    'label' => 'Title',
                    'required' => true,
                    'validation' => ['not_empty'],
                    'value' => isset($youtube->title) ? $youtube->title : '',
                ],
                'ytid' => [
                    'type' => 'text',
                    'label' => 'Youtube Video ID',
                    'required' => true,
                    'validation' => ['not_empty'],
                    'value' => isset($youtube->ytid) ? $youtube->ytid : '',
                ],
                'description' => [
                    'type' => 'textarea',
                    'label' => 'Description',
                    'required' => true,
                    'validation' => ['not_empty'],
                    'value' => isset($youtube->description) ? $youtube->description : '',
                ],

                'submit' => [
                    'type' => 'submit',
                    'callback' => function ($form) {
                        $form->saveInSession = true;
                        return true;
                    }
                ],

            ]);

            $status = $form->check();
            if ($status === true) {

                $title = $_SESSION['form-save']['title']['value'];
                $ytid = $_SESSION['form-save']['ytid']['value'];
                $description = $_SESSION['form-save']['description']['value'];

                $youtube->title = $title;
                $youtube->ytid = $ytid;
                $youtube->description = $description;
                $youtube->save();

                session_unset($_SESSION['form-save']);
                $url = $this->url->create('youtube/id/' . $id);
                $this->response->redirect($url);
            }



        }
            $this->theme->setTitle("Update video");
            $this->views->add('youtube/update', [
                'youtube'  => $youtube,
                'title' => "<b>Edit</b> - $youtube->title",
                'content' => $form->getHTML(),
            ], 'main'); 
    }
    
    
}
