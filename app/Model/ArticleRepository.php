<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 13.01.2019
 * Time: 17:43
 */

namespace App\Model;
use Nette;

class ArticleRepository
{


    use \Nette\SmartObject;

    /** @var Nette\Database\Connection */
    private $database;

    public function __construct(Nette\Database\Connection $database)
    {
        $this->database = $database;
    }

    public function getNews($limit, $offset )
    {
        return $this->database->query('
          SELECT * FROM "posts"
          ORDER BY "date" DESC 
          LIMIT ?
          OFFSET ?',
            $limit, $offset
        );
    }

    public function getNewsCount()
    {
        return $this->database->fetchField('SELECT COUNT(*) FROM posts');
    }
}