<?php

declare(strict_types=1);

namespace App\Model\Facades;

use Exception;
use Nette\Database\Explorer;
use Nette\Database\ResultSet;
use Nette\Database\Row;
use Nette\Database\Table\Selection;

class AdminFacade
{
    private Explorer $database;

    /**
     * AdminFacade constructor
     * 
     * @param Explorer $database - Database connection
     */
    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    /**
     * Fetch images from the database with pagination
     * 
     * @param int $limit
     * @param int $offset
     * @return ResultSet
     */
    public function showImages(int $limit, int $offset): ResultSet
    {
        return $this->database->query(
            '
            SELECT * FROM images
            WHERE deleted_at is NULL
            ORDER BY uploaded_at DESC
            LIMIT ?
            OFFSET ?',
            $limit,
            $offset,
        );
    }

    /**
     * Get a single image by its ID
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getImage($id): Row|null
    {
        return $this->database->query(
            '
            SELECT *
            FROM images
            WHERE id = ?',
            $id
        )->fetch();
    }

    /**
     * Get the total count of non-deleted images
     * 
     * @return int - Total count of non-deleted images
     */
    public function getTotalImagesCount(): int
    {
        return $this->database->fetchField('SELECT COUNT(*) FROM images WHERE deleted_at is NULL');
    }

    /**
     * Insert a new image record into the database
     * 
     * @param array $data
     */
    public function insertImage($data): void
    {
        try {
            $this->database->table('images')->insert($data);
        } catch (Exception $e) {
            throw new Exception('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing image record by its ID
     * 
     * @param array $data
     * @param mixed $id
     */
    public function updateImage($data, $id): void
    {
        try {
            $this->database->table('images')->where('id', $id)->update($data);
        } catch (Exception $e) {
            throw new Exception('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete the image
     * 
     * @param mixed $id
     * @return bool
     */
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

    /**
     * Fetch all categories from the database
     * 
     * @return \Nette\Database\Table\Selection
     */
    public function showCategories(): Selection
    {
        return $this->database->table('categories')->where('deleted_at', NULL);
    }

    /**
     * Get a single category by its ID
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getCategory($id): Row|null
    {
        return $this->database->query(
            '
            SELECT *
            FROM categories
            WHERE id = ?',
            $id
        )->fetch();
    }

    /**
     * Insert a new category into the database
     * 
     * @param array $data
     */
    public function insertCategory($data)
    {
        try {
            $this->database->table('categories')->insert($data);
        } catch (Exception $e) {
            throw new Exception('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing category
     * 
     * @param array $data
     */
    public function updateCategory($data, $id): void
    {
        try {
            $this->database->table('categories')->where('id', $id)->update($data);
        } catch (Exception $e) {
            throw new Exception('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete a category
     * 
     * @param int $id
     * @return bool
     */
    public function deleteCategory(int $id): bool
    {
        try {
            $this->database->table('categories')->where('id', $id)->update(['deleted_at' => new \DateTime()]);
            return true;
        } catch (Exception $e) {
            throw new Exception('An error occurred during deletion: ' . $e->getMessage());
        }
    }
}
