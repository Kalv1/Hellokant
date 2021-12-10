<?php

namespace Hellokant\Model;

class Categorie extends Model
{
    protected static string $table = 'categorie';
    protected static string $idColumn = 'id';

    public function articles()
    {
        return $this->has_many('Article', 'id_categ');
    }
}