<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity()
 */
class Upload
{
    /**
     * @var string
     * @ORM\Column(type="guid")
     * @ORM\Id()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column()
     */
    private $path;

    public function __construct(File $file)
    {
        try {
            $this->id = Uuid::uuid4()->toString();
        } catch (\Exception $e) {
        }
        $this->path = $file->getRealPath();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFile(): File
    {
        return new File($this->path);
    }
}