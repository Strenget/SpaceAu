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

    /** render template user */
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
    }
}