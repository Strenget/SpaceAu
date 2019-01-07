<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 31.12.2018
 * Time: 12:47
 */

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class SignPresenter extends Nette\Application\UI\Presenter
{

    protected function createComponentSignInForm()
    {
        $form = new Form;
        $form->addText('email', 'Email: ')
            ->setRequired('Please enter your email');
        $form->addPassword('password_hash', 'Password')
            ->setRequired('Please enter your password');

        $form->addSubmit('send', 'Sign in');

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];

        return $form;
    }

    public function signInFormSucceeded(Form $form, \stdClass $values)
    {
        try {
            $this->getUser()->login($values->email, $values->password_hash);
            $this->redirect('Homepage:');
        }
        catch (Nette\Security\AuthenticationException $e)
        {

            $form->addError('Nespravne prihlasovaci jmeno nebo heslo');
        }
    }

}