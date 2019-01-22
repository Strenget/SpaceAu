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

    /**
     * EditPresenter constructor.
     * @param \Nette\Database\Context $database
     * @param \Nette\Http\Request $request
     */
    public function __construct(\Nette\Database\Context $database, \Nette\Http\Request $request)
    {
        $this->database = $database;
    }

    /** Create template user for page Edit */
    public function renderDefault()
    {
        $this->template->user = $this->getUser()->getIdentity();
    }

    /** Create form */
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
            $form->addSubmit('save', 'Save');

            $form->onSuccess[] = function() use ($form) {
                $values = $form->getValues();
                $existDescription = $this->database->fetch('SELECT * FROM "user_description" WHERE "id_user" = ?', $this->getUser()->getIdentity()->getId());
                if ($existDescription == null)
                {
                    if ($values->date_of_birth === "")
                    {
                        $values->date_od_birth = date("Y-m-d H:i:s");
                    }
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
                    if ($values->date_of_birth === "")
                    {
                        $values->date_od_birth = date("Y-m-d H:i:s");
                    }
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