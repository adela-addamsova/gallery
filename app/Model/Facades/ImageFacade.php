<?php

declare(strict_types=1);

namespace App\Model\Facades;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

class ImageFacade
{
    private Explorer $database;
    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    public function getImageCategories(): Selection
    {
        return $this->database->table('categories')
            ->order('id');
    }

    public function getImages($id): Selection
    {
        return $this->database->table('images')->where($id);
    }

    public function getLatestImage($category_id)
    {
        $query = "SELECT 
        images.id AS image_id, images.thumb_path AS image_thumb, images.uploaded_at AS image_uploaded_at, categories.name AS category_name
        FROM images 
                  LEFT JOIN categories ON categories.id = images.category_id 
                  WHERE category_id = ? 
                  ORDER BY uploaded_at DESC 
                  LIMIT 1";

        return $this->database->query($query, $category_id)->fetch();
    }
}
