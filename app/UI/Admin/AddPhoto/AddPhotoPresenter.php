<?php

declare(strict_types=1);

namespace App\UI\Admin\AddPhoto;

use App\Components\PhotoForm\PhotoForm;
use App\Model\Facades\AdminFacade;
use App\Model\Interfaces\PhotoFormFactory;
use App\UI\Admin\BasePresenter;
use Exception;
use Nette\Http\FileUpload;

class AddPhotoPresenter extends BasePresenter
{
    /**
     * Constructor for AddPhotoPresenter
     * 
     * @param PhotoFormFactory $photoFormFactory - Factory for creating photo form components
     */
    public function __construct(
        private PhotoFormFactory $photoFormFactory
    ) {}

    /** @var AdminFacade @inject */
    public $adminFacade;

    function createThumbnail($sourceImagePath, $thumbnailPath, $thumbnailWidth = 1678, $thumbnailHeight = 944)
    {
        list($originalWidth, $originalHeight, $imageType) = getimagesize($sourceImagePath);

        $thumbnail = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($sourceImagePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($sourceImagePath);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($sourceImagePath);
                break;
            case IMAGETYPE_WEBP:
                $sourceImage = imagecreatefromwebp($sourceImagePath);
                break;
            default:
                throw new Exception("Unsupported image type");
        }

        $webpPath = preg_replace('/\.[^.]+$/', '.webp', $sourceImagePath); 
        imagewebp($sourceImage, $webpPath);

        imagedestroy($sourceImage);

        $sourceImage = imagecreatefromwebp($webpPath);

        imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $originalWidth, $originalHeight);

        imagewebp($thumbnail, $thumbnailPath);

        imagedestroy($sourceImage);
        imagedestroy($thumbnail);
    }

    /**
     * Creates the photo form component
     * 
     * @return PhotoForm
     */
    protected function createComponentPhotoForm(): PhotoForm
    {
        $photoForm = $this->photoFormFactory->create();

        $photoForm->setEditMode(false);

        $photoForm->onSave[] = function (PhotoForm $photoForm, $data): void {
            /** @var FileUpload $image */
            $image = $data->image;
            $category = $this->adminFacade->getCategory($data->category_id);

            $uploadDir = 'img/' . strtolower($category['name']) . '/';
            $thumbnailDir = 'img/' . strtolower($category['name']) . '/thumbs/';

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            $filename = $image->getSanitizedName();
            $filenameWebp = preg_replace('/\.[^.]+$/', '.webp', $filename);


            $imagePath = $uploadDir . $filenameWebp;
            $thumbnailPath = $thumbnailDir . $filenameWebp;

            $tempPath = $uploadDir . $filename;
            $image->move($tempPath);

            $this->createThumbnail($tempPath, $thumbnailPath);

            unlink($tempPath);

            $data = [
                'description' => $data->description,
                'category_id' => $data->category_id,
                'path' => $uploadDir . $filenameWebp,
                'thumb_path' => $thumbnailDir . $filenameWebp,
            ];

            $this->adminFacade->insertImage($data);
            $this->flashMessage('Photo was added successfully!', 'success');
        };

        return $photoForm;
    }
}
