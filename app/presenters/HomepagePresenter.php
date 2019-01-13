<?php

namespace App\Presenters;

use App\Model\ArticleRepository;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{


    /** @var ArticleRepository */
    private $articleRepository;


   public function __construct(ArticleRepository $arcticleRepository)
   {
       $this->articleRepository = $arcticleRepository;
   }

    public function renderDefault($page = 1)
    {

        $newsCount = $this->articleRepository->getNewsCount();


        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($newsCount);
        $paginator->setItemsPerPage(1);
        $paginator->setPage($page);

        $news = $this->articleRepository->getNews($paginator->getLength(), $paginator->getOffset());

        $this->template->news = $news;
        $this->template->paginator = $paginator;

        $limit = 5;

//        $this->template->news = $this->database->table('posts')
//            ->order(
//                'date'
//            )->limit($limit);

    }

}
