<?php

declare(strict_types=1);

namespace App\Model\Facades;

use Exception;
use Nette\Database\Explorer;
use Nette\Database\ResultSet;
use Nette\Database\Table\Selection;

class AdminFacade
{
    private Explorer $database;
    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }


    // Images
    // public function showImages(int $itemsPerPage = 10, int $offset = 0)
    // {
    //     $query = $this->database->table('images');  // Adjust for your table name
        
    //     // Apply pagination if parameters are provided
    //     return $query->limit($itemsPerPage, $offset);
    // }

    public function showImages(int $limit, int $offset): ResultSet
{
    return $this->database->query('
			SELECT * FROM images
			ORDER BY uploaded_at DESC
			LIMIT ?
			OFFSET ?',
			$limit, $offset,
		);
}
    public function getTotalImagesCount(): int
    {
        return $this->database->fetchField('SELECT COUNT(*) FROM images');
    }

    public function insertImage($data)
    {
        try {
            $this->database->table('images')->insert($data);
        } catch (Exception $e) {
            throw new Exception('An error occurred: ' . $e->getMessage());
        }
    }

    public function updateImage($data) {
        try {
            $this->database->table('images')->update($data);
        } catch (Exception $e) {
            throw new Exception('An error occurred: ' . $e->getMessage());
        }
    }

    public function deleteImage($id): bool
    {
        try {
            $this->database->table('images')->where('id', $id)->update(['deleted_at' => new \DateTime()]);
            return true;
        } catch (Exception $e) {
            throw new Exception('An error occurred during deletion: ' . $e->getMessage());
        }
    }

    // Categories
    public function showCategories()
    {
        return $this->database->table('categories');
        }
    public function insertCategory($data)
    {
        try {
            $this->database->table('categories')->insert($data);
        } catch (Exception $e) {
            throw new Exception('An error occurred: ' . $e->getMessage());
        }
    }

    public function updateCategory($data) {
        try {
            $this->database->table('images')->update($data);
        } catch (Exception $e) {
            throw new Exception('An error occurred: ' . $e->getMessage());
        }
    }

    public function deleteCategory($id): bool
    {
        try {
            $this->database->table('images')->where('id', $id)->update(['deleted_at' => new \DateTime()]);
            return true;
        } catch (Exception $e) {
            throw new Exception('An error occurred during deletion: ' . $e->getMessage());
        }
    }
}
