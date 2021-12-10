<?php

namespace Hellokant\Model;

class Article extends Model
{
    protected static string $table = 'article';
    protected static string $idColumn = 'id';

    public function categorie()
    {
        return $this->belongs_to('Categorie', 'id_categ');
    }
}