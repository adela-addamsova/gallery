<?php

declare(strict_types=1);

namespace App\Components\PhotoForm;

use App\Model\Facades\AdminFacade;
use Exception;
use GdImage;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Nette\Security\Resource;
use Nette\Utils\ArrayHash;

class PhotoForm extends Control
{
    public array $onSave = [];
    private bool $isEditMode;
    private $imageData = null;
    private AdminFacade $adminFacade;

    public function __construct(AdminFacade $adminFacade)
    {
        $this->adminFacade = $adminFacade;
    }

    /**
     * Set edit mode for the form
     * 
     * @param bool $isEditMode 
     * @param mixed|null $imageData 
     */
    public function setEditMode(bool $isEditMode, $imageData = null): void
    {
        $this->isEditMode = $isEditMode;
        $this->imageData = $imageData;
    }

    /**
     * Create and return the form component
     *
     * @return Form 
     */
    protected function createComponentForm(): Form
    {
        $form = new Form;

        $form->addText('description', 'Description')
            ->setHtmlAttribute('placeholder', 'Description');

        $form->addText('category_id', 'Category ID')
            ->setHtmlAttribute('placeholder', 'Category_ID')
            ->setRequired();

        $form->addUpload('image', 'Image')
            ->setRequired($this->isEditMode ? false : 'Please upload an image.');

        $form->addSubmit('send', $this->isEditMode ? 'Update' : 'Save');

        if ($this->isEditMode && $this->imageData) {
            $form->setDefaults([
                'description' => $this->imageData['description'],
                'category_id' => $this->imageData['category_id'],
            ]);
        }

        $form->onSuccess[] = [$this, 'handleFormSuccess'];

        return $form;
    }

    /**
     * Handle form submission, process data and image upload
     * 
     * @param ArrayHash $values The form values
     */
    public function handleFormSuccess(ArrayHash $values): void
    {
        $category = $this->adminFacade->getCategory($values->category_id);

        if ($category === null) {
            throw new Exception("Category not found.");
        }

        $uploadDir = 'img/' . strtolower($category['name']) . '/';
        $thumbnailDir = $uploadDir . 'thumbs/';

        if (!file_exists($uploadDir)) {
            throw new Exception("File not found.");
        }

        if (!file_exists($thumbnailDir)) {
            throw new Exception("File not found.");
        }

        $imageUpdated = false;

        if ($values->image->isOk() && $values->image->isImage()) {
            $imageUpdated = true;
            /** @var FileUpload $image */
            $image = $values->image;
            $filename = $image->getSanitizedName();
            $filenameWebp = preg_replace('/\.[^.]+$/', '.webp', $filename);
            $imagePath = $uploadDir . $filenameWebp;
            $thumbnailPath = $thumbnailDir . $filenameWebp;
            $tempPath = $uploadDir . $filename;

            $image->move($tempPath);
            $this->convertToWebp($tempPath, $imagePath);
            $this->createThumbnail($imagePath, $thumbnailPath);
            unlink($tempPath);

            $data['path'] = $imagePath;
            $data['thumb_path'] = $thumbnailPath;
        }

        $data = [
            'description' => $values['description'],
            'category_id' => $values['category_id'],
            'path' => $imageUpdated ? $imagePath : $this->imageData['path'],
            'thumb_path' => $imageUpdated ? $thumbnailPath : $this->imageData['thumb_path'],
        ];

        if (!$this->isEditMode) {
            $this->adminFacade->insertImage($data);
        } else {
            $data['id'] = $this->getPresenter()->getParameter('id');
            $this->adminFacade->updateImage($data, $data['id']);
        }

        foreach ($this->onSave as $callback) {
            $callback($this, $values);
        }
    }

    /**
     * Load an image based on its type
     *
     * @param string $imagePath
     * @param int $imageType
     * @return bool|GdImage|Resource
     */
    private function loadImage($imagePath, $imageType): bool|GdImage|Resource
    {
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($imagePath);
            case IMAGETYPE_PNG:
                return imagecreatefrompng($imagePath);
            case IMAGETYPE_GIF:
                return imagecreatefromgif($imagePath);
            case IMAGETYPE_WEBP:
                return imagecreatefromwebp($imagePath);
            default:
                throw new Exception("Unsupported image type");
        }
    }

    /**
     * Create a thumbnail from the source image
     *
     * @param string $sourceImagePath Path to the source image
     * @param string $thumbnailPath Path where the thumbnail should be saved
     * @param int $thumbnailWidth Width of the thumbnail
     * @param int $thumbnailHeight Height of the thumbnail
     */
    private function createThumbnail($sourceImagePath, $thumbnailPath, $thumbnailWidth = 1678, $thumbnailHeight = 944): void
    {
        list($originalWidth, $originalHeight, $imageType) = getimagesize($sourceImagePath);
        $sourceImage = $this->loadImage($sourceImagePath, $imageType);

        $thumbnail = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

        imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $originalWidth, $originalHeight);

        imagewebp($thumbnail, $thumbnailPath);

        imagedestroy($sourceImage);
        imagedestroy($thumbnail);
    }

    private function convertToWebp($sourceImagePath, $webpPath): void
    {
        list($originalWidth, $originalHeight, $imageType) = getimagesize($sourceImagePath);
        $sourceImage = $this->loadImage($sourceImagePath, $imageType);

        imagewebp($sourceImage, $webpPath);

        imagedestroy($sourceImage);
    }

    /**
     * Render the form template
     */
    public function render(): void
    {
        $this->template->isEditMode = $this->isEditMode;
        $this->template->setFile(__DIR__ . '/photoForm.latte');
        $this->template->render();
    }
}
