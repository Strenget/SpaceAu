<?php

namespace App\Presenters;

use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
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


    public function renderDefault()
    {
        $limit = 5;

        $this->template->news = $this->database->table('post')
            ->order(
                'date'
            )->limit($limit);



    }

}
