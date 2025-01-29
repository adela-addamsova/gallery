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
                  WHERE category_id = ? AND images.deleted_at is NULL
                  ORDER BY uploaded_at DESC 
                  LIMIT 1";

        return $this->database->query($query, $category_id)->fetch();
    }

    public function getImagesByCategory(string $category_name, int $limit, int $offset)
    {
        $query = "SELECT 
        images.id AS image_id, images.thumb_path AS image_thumb, images.uploaded_at AS image_uploaded_at, categories.name AS category_name, images.description AS description, images.path AS image_path, categories.background_path as background
        FROM images 
                  LEFT JOIN categories ON categories.id = images.category_id 
                  WHERE images.deleted_at is NULL AND categories.name = ?
                  ORDER BY uploaded_at DESC
                  LIMIT ? OFFSET ?";

        return $this->database->query($query, $category_name, $limit, $offset)->fetchAll();
    }

    public function countImagesByCategory(string $category_name): int
{
    $query = "SELECT COUNT(*) AS total 
              FROM images 
              LEFT JOIN categories ON categories.id = images.category_id 
              WHERE categories.name = ? AND images.deleted_at is NULL";

    return $this->database->query($query, $category_name)->fetchField();
}

}
