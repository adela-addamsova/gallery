<?php

declare(strict_types=1);

namespace App\Model\Facades;

use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\Selection;

class ImageFacade
{
    private Explorer $database;

    /**
     * ImageFacade constructor
     * 
     * @param Explorer $database - Database connection
     */
    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    /**
     * Get all image categories from the database, ordered by ID
     * 
     * @return Selection
     */
    public function getImageCategories(): Selection
    {
        return $this->database->table('categories')->where('deleted_at', NULL)
        ->order('id');
    }

    /**
     * Get images based on the provided image ID
     * 
     * @param mixed $id
     * @return Selection
     */
    public function getImages($id): Selection
    {
        return $this->database->table('images')->where($id);
    }

    /**
     * Get the latest image for a given category
     * 
     * @param int $category_id
     * @return mixed
     */
    public function getLatestImage($category_id): Row|null
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

    /**
     * Get images for a specific category with pagination
     * 
     * @param string $category_name
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getImagesByCategory(string $category_name, int $limit, int $offset): array
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

    /**
     * Count the number of images in a given category
     * 
     * @param string $category_name
     * @return int
     */
    public function countImagesByCategory(string $category_name): int
    {
        $query = "SELECT COUNT(*) AS total 
                  FROM images 
                  LEFT JOIN categories ON categories.id = images.category_id 
                  WHERE categories.name = ? AND images.deleted_at is NULL";

        return $this->database->query($query, $category_name)->fetchField();
    }
}
