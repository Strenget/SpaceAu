<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 05.01.2019
 * Time: 20:15
 */

namespace App\Presenters;


use Nette\Application\ApplicationException;
use Nette\Application\UI\Presenter;

class ProfilePresenter extends Presenter
{
    /**
     * @var \Nette\Http\Request
     * Request for get cookie
     */
    private $httpRequest;

    /**
     * @var \Nette\Database\Context
     * Value for connection to db
     */
    private $database;

    public function __construct(\Nette\Database\Context $database, \Nette\Http\Request $request)
    {
        $this->httpRequest = $request;
        $this->database = $database;
    }

    /**
     * @return \Nette\Application\UI\Form
     * @throws ApplicationException
     */
    protected function createComponentFormRegistration()
    {
        $cookie = $this->httpRequest->getCookie('language');
        if ($cookie)
        {
            $text = new TextManager($cookie);
        }
        else {
            $text = new TextManager('en');
        }

        $form = new \Nette\Application\UI\Form();







        //        $form->addText('firstName', 'First Name')->setRequired();
//        $form->addText('lastName', 'Last Name')->setRequired();
//        $form->addText('nickname', 'nickname')->setRequired();
//        $form->addText('date', 'Date')->setType('date');
//        $form->addProtection('Chyba');
//
//
//
//        $form->onSuccess[] = function() use ($form) {
//            $values = $form->getValues();
//            $lastId = (int)$this->database->fetch('SELECT MAX("id") FROM "user"')['max'] + 1;
//            $this->database->table('user')->insert([
//                'id' => $lastId,
//                'email' => $values->email,
//                'password_hash' => \Nette\Security\Passwords::hash($values->pwd),
//            ]);
//        };

//        $form->onSuccess[] = function() {
//            $this->redirect('Homepage:default');
//        };

        return $form;
    }

    public function renderDefault()
    {


        if ($this->getUser()->getIdentity() == null)
        {
            $this->template->user = null;
        }
        else
        {
            $idUser = $this->getUser()->getIdentity()->getId();
            $user = $this->database->fetch('SELECT * FROM "user_description" WHERE "id_user" = ?', $idUser);
            $this->template->user = $user;
        }

//        dump($this->template->user);
//        exit();
    }


}