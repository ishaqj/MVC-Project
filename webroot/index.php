<?php 
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php'; 

// Create services and inject into the app.

$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/config_mysql.php');
    $db->connect();
    return $db;
});

$di->set('QuestionsController', function() use ($di) {
    $controller = new \Anax\Question\QuestionsController();
    $controller->setDI($di);
    return $controller;
});
$di->set('TagsController', function() use ($di) {
    $controller = new \Anax\Tags\TagsController();
    $controller->setDI($di);
    return $controller;
});
$di->set('CommentsController', function() use ($di) {
    $controller = new \Anax\Comments\CommentsController();
    $controller->setDI($di);
    return $controller;
});
$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});
$di->set('AnswersController', function() use ($di) {
    $controller = new \Anax\Answers\AnswersController();
    $controller->setDI($di);
    return $controller;
});

$di->set('form', '\Mos\HTMLForm\CForm');
$di->set('time', '\Anax\Time\Time');

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

// Get theme
$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');




// Routes
$app->session;
$messages = $app->flashmessage->getFlashMessages();
$app->views->addString($messages);
$app->router->add('', function() use ($app) {

    $app->theme->setTitle("Hem");

   $questions = $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'list',
    ]);
   

       $popularusers = $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'popular',
    ]);
   $tags = $app->dispatcher->forward([
        'controller' => 'tags',
        'action'     => 'popularTags',
    ]);
 

    $app->db->select('*')
    ->from('user')
    ->execute();
    $sql = $app->db->rowCount();

     $app->views->add('questions/index', [
        'questions' => $questions,
        'sql' => $sql,
    ]);

    $app->views->add('questions/index1', [
        'users' => $popularusers,
    ]);

        $app->views->add('questions/index2', [
        'tags' => $tags,
    ]);

});
 


$app->router->add('about',function() use ($app) {
$app->theme->setTitle("About Goal");
$app->views->addString('<h1>About Goal</h1> <hr>');
$app->views->addString('<p>På Goal kan du diskutera om allt som har med fotboll att göra, när matchen börjar, vilka som spelar i champions league,diskussioner  om Premier League,Serie A ,Allsvenskan, La liga, Bundesliga, UEFA och Champions League.</p>');
$app->views->addString('<h1>About Me </h1> <hr>');
$app->views->addString('<p>Mitt namn är Ishaq Jound och jag pluggar till webbprogramering. På fritiden brukar jag umgås med vänner,träna på gym, titta på fotboll och filmer.</p>');

});

$app->router->add('setup', function() use ($app) {

    $app->theme->setTitle("Setup");

    //$app->db->setVerbose();
 
    $app->db->dropTableIfExists('user')->execute();
 
    $app->db->createTable(
        'user',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'username' => ['varchar(20)', 'unique', 'not null'],
            'email' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'created' => ['datetime'],
        ]
    )->execute();

    $app->db->dropTableIfExists('question')->execute();
 
    $app->db->createTable(
        'question',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'userid' => ['integer', 'not null'],
            'title' => ['varchar(300)','not null'],
            'content' => ['text','not null'],
            'modified' => ['datetime','not null'],
            'qcreated' => ['datetime','not null'],
        ]
    )->execute();

    $app->db->dropTableIfExists('comment')->execute();
 
    $app->db->createTable(
        'comment',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'comment' => ['text', 'not null'],
            'userid' => ['integer', 'not null'],
            'caID' => ['integer', 'not null'],
            'type' => ['varchar(50)','not null'],
            'added' => ['datetime','not null'],
        ]
    )->execute();

    $app->db->dropTableIfExists('answer')->execute();
 
    $app->db->createTable(
        'answer',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'answer' => ['text', 'not null'],
            'userid' => ['integer', 'not null'],
            'questionid' => ['integer', 'not null'],
            'added' => ['datetime','not null'],
        ]
    )->execute();

    $app->db->dropTableIfExists('tag')->execute();
 
    $app->db->createTable(
        'tag',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'tag' => ['varchar(300)', 'not null'],
            'userid' => ['integer', 'not null'],
            'questionid' => ['integer', 'not null'],
        ]
    )->execute();

    $app->db->dropTableIfExists('vote')->execute();
 
    $app->db->createTable(
        'vote',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'userid' => ['integer', 'not null'],
            'anscomID' => ['integer', 'not null'],
            'type' => ['varchar(20)', 'not null'],

        ]
    )->execute();


    // one test user
    $app->db->insert(
        'user',
        ['username', 'email', 'password', 'created']
    );
    
    $now = date("Y-m-d h:i:s");
 
    $app->db->execute([
        'admin',
        'ishaq_jound@hotmail.com',
        md5(strtolower(trim('admin'))),
        $now
    ]);
 
    $app->flashmessage->addSuccess('Tables have been created. You can make account now!');
    $url = $app->url->create('');
    $app->response->redirect($url);
});


$app->router->handle();
$app->theme->render(); 