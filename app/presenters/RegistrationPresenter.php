<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 30.12.2018
 * Time: 21:37
 */

namespace App\Presenters;

use Nette\Application\ApplicationException;
use Nette\Application\UI\Form;
use TextManager;

/**
 * Class RegistrationPresenter
 * Registration page
 * @package App\Presenters
 */
class RegistrationPresenter extends \Nette\Application\UI\Presenter
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
        /** set cookie from browser */
        $cookie = $this->httpRequest->getCookie('language');
        if ($cookie)
        {
            $text = new TextManager($cookie);
        }
        else {
            $text = new TextManager('en');
        }

        $form = new \Nette\Application\UI\Form();

        /** array of emails from db */
        $existEmails = $this->database->fetchAll('SELECT "email" FROM "user" ');
        $emailInString = [];

        foreach ($existEmails as $email){
            array_push($emailInString, $email['email']);
        }

        /** add email form with rule, that new email is not in db */
        $form->addEmail('email', 'Email')
            ->addRule(Form::IS_NOT_IN, 'This mail was register before', $emailInString);
        $passwordInput = $form->addPassword('pwd', $text->registrationText[2])->setRequired($text->registrationText[4]);
        $form->addPassword('pwd2', $text->registrationText[5])->setRequired($text->registrationText[6])->addRule($form::EQUAL, $text->registrationText[7], $passwordInput);
        $form->addSubmit('register', $text->registrationText[8]);
        $form->addProtection($text->registrationText[9]);


        $form->onSuccess[] = function() use ($form) {
            $values = $form->getValues();

            /** get last id from db */
            $lastId = (int)$this->database->fetch('SELECT MAX("id") FROM "user"')['max'] + 1;

            /** insert new user to db */
            $this->database->table('user')->insert([
                'id' => $lastId,
                'email' => $values->email,
                'password_hash' => \Nette\Security\Passwords::hash($values->pwd),
            ]);
        };

        /** redirect to homepage */
        $form->onSuccess[] = function() {
            $this->redirect('Homepage:default');
        };

        return $form;
    }
}