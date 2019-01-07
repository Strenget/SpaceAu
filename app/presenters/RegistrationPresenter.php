<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 30.12.2018
 * Time: 21:37
 */

namespace App\Presenters;

use Nette\Application\ApplicationException;
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
        $cookie = $this->httpRequest->getCookie('language');
        if ($cookie)
        {
            $text = new TextManager($cookie);
        }
        else {
            $text = new TextManager('en');
        }

        $form = new \Nette\Application\UI\Form();

        $form->addEmail('email', 'Email')->setRequired($text->registrationText[3]);
        $passwordInput = $form->addPassword('pwd', $text->registrationText[2])->setRequired($text->registrationText[4]);
        $form->addPassword('pwd2', $text->registrationText[5])->setRequired($text->registrationText[6])->addRule($form::EQUAL, $text->registrationText[7], $passwordInput);
        $form->addSubmit('register', $text->registrationText[8]);
        $form->addProtection($text->registrationText[9]);

        $form->onSuccess[] = function() use ($form) {
            $values = $form->getValues();
            $lastId = (int)$this->database->fetch('SELECT MAX("id") FROM "user"')['max'] + 1;
            $this->database->table('user')->insert([
                'id' => $lastId,
                'email' => $values->email,
                'password_hash' => \Nette\Security\Passwords::hash($values->pwd),
            ]);
        };

        $form->onSuccess[] = function() {
            $this->redirect('Homepage:default');
        };

        return $form;
    }

}