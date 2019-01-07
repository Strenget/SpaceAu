<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 06.01.2019
 * Time: 18:38
 */

namespace App\Presenters;


use Nette\Application\UI\Presenter;

class EditPresenter extends Presenter
{

    /**
     * @var \Nette\Database\Context
     * Value for connection to db
     */
    private $database;

    public function __construct(\Nette\Database\Context $database, \Nette\Http\Request $request)
    {
        $this->database = $database;
    }

    public function renderDefault()
    {
        $this->template->user = $this->getUser()->getIdentity();
    }

    public function createComponentFormEditProfile()
    {

        if ($this->getUser()->getIdentity() != null)
        {
            $form = new \Nette\Application\UI\Form();

            $form->addText('last_name', 'Last name')->setRequired();
            $form->addText('first_name', 'First_name')->setRequired();
            $form->addText('nickname', 'nickName')->setRequired();
            $form->addText('date_of_birth')->setType('date');
            $form->addProtection('d');
            $form->addSubmit('save', 'dsd');

            $form->onSuccess[] = function() use ($form) {
                $values = $form->getValues();
                $existDescription = $this->database->fetch('SELECT * FROM "user_description" WHERE "id_user" = ?', $this->getUser()->getIdentity()->getId());
                if ($existDescription == null)
                {
                    $lastId = (int)$this->database->fetch('SELECT MAX("id") FROM "user"')['max'] + 1;
                    $this->database->table('user_description')->insert([
                        'id' => $lastId,
                        'id_user' => $this->getUser()->getIdentity()->getId(),
                        'first_name' => $values->first_name,
                        'last_name' => $values->last_name,
                        'nickname' => $values->nickname,
                        'date_of_birth' => $values->date_of_birth
                    ]);
                }
                else
                {
                    $id = $existDescription['id'];
                    $this->database->query('UPDATE "user_description" SET', [
                        'id_user' => $this->getUser()->getIdentity()->getId(),
                        'first_name' => $values->first_name,
                        'last_name' => $values->last_name,
                        'nickname' => $values->nickname,
                        'date_of_birth' => $values->date_of_birth
                    ], 'WHERE id = ?', $id);
                }
            };
        $form->onSuccess[] = function() {
            $this->redirect('Homepage:default');
        };

        return $form;
        }
    }
}