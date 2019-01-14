<?php

namespace App\Presenters;

use App\Model\ArticleRepository;
use Nette;
use Nette\Application\UI\Form;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{


    /** @var ArticleRepository */
    private $articleRepository;


   public function __construct(ArticleRepository $arcticleRepository)
   {
       $this->articleRepository = $arcticleRepository;
   }


   /** render some templates and set paginator for homepage */
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

    }
}
