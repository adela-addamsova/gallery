<?php

declare(strict_types=1);

namespace App\Model\Facades;

use Exception;
use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

class AdminFacade
{
    private Explorer $database;
    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }


    // Images
    public function showImages(): Selection
    {
        return $this->database->table('images');
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
