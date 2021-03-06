<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 05.01.2019
 * Time: 13:19
 */

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Presenter;

class AdministrationPresenter extends Presenter
{

    /**@var Nette\Database\Context */
    private $database;


    /**
     * HomepagePresenter constructor.
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    /**
     * Create form for page administration
     * @return Nette\Application\UI\Form
     */
    protected function createComponentCreatePost()
    {

        $form = new \Nette\Application\UI\Form();

        /** Create array for select topic */
        $topics = ['Space', 'Earth', 'Sun'];

        $form->addText('title', 'Title')->setRequired('text');

        $form->addSelect('topic', 'Topic', $topics);

        /** Create text area */
        $form->addTextArea('postCz', 'Text')
            ->setAttribute('class', 'mceEditor');

        $form->addSubmit('createPost', 'Create post');



        if ($this->getUser()->getIdentity() ==  null)
        {
            $authorName = "Unknown user";
        }
        else
        {
            try
            {
                $authorName = $this->database->fetch('SELECT "nickname" FROM "user_description" WHERE "id_user" = ?', $this->getUser()->getIdentity()->getId())['nickname'];
            }
            catch (\Exception $exception)
            {
                $authorName = "Unknown user";
            }
        }

        if (!$authorName)
        {
            $authorName = $this->getUser()->getIdentity()->getData()['email'];
        }

        $form->onSuccess[] = function() use ($form, $authorName) {
            $values = $form->getValues();
            $lastId = (int)$this->database->fetch('SELECT MAX("id") FROM "posts"')['max'] + 1;
            $this->database->table('posts')->insert([
                'id' => $lastId,
                'content' => $values->postCz,
                'author' => $authorName,
                'date' => date('Y-m-d H:i:s'),
                'topic' => $values->topic,
                'title' => $values->title
            ]);
        };

        return $form;
    }

}