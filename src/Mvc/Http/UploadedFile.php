<?php

namespace Mvc\Http;

class UploadedFile
{
    /**
     * Original file name.
     * @var string
     */
    protected string $originalName;

    /**
     * File mime type.
     * @var string
     */
    protected string $mimeType;

    /**
     * File path.
     * @var string
     */
    protected string $path;

    /**
     * File upload error.
     * @var int
     */
    protected int $error;

    public function __construct(
        string $path, string $originalName, string $mimeType, int $error = UPLOAD_ERR_OK)
    {
        $this->path = $path;
        $this->originalName = $originalName;
        $this->mimeType = $mimeType;
        $this->error = $error;
    }

    /**
     * Gets the file path.
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Gets the original file name.
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * Gets the file mime type.
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Checks if the file was uploaded successfully.
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->error === UPLOAD_ERR_OK && is_uploaded_file($this->path);
    }

    /**
     * Gets the error message.
     * @return string
     */
    public function getErrorMessage(): string
    {
        return match($this->error) {
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        };
    }

    /**
     * Gets the file content.
     * @throws \Exception throws an exception if the file is not found.
     * @return string
     */
    public function getContent(): string
    {
        if (!$this->isValid()) {
            throw new \Exception("File \"{$this->originalName}\" not found.");
        }

        return file_get_contents($this->path);
    }

    /**
     * Moves the file to a new location with a new name.
     *
     * @param string $directory
     * @param string $fileName
     * @return bool
     * @throws \Exception throws an exception if the file is not found.
     */
    public function moveAs(string $directory, string $fileName): bool
    {
        if (!$this->isValid()) {
            throw new \Exception("File \"{$this->originalName}\" not found.");
        }

        return move_uploaded_file($this->path, "{$directory}/{$fileName}");
    }

    /**
     * Moves the file to a new location with the original name.
     *
     * @param string $directory
     * @param string $fileName
     * @return bool
     * @throws \Exception throws an exception if the file is not found.
     */
    public function move(string $directory, string $fileName): bool
    {
        return $this->moveAs($directory, $this->originalName);
    }
}
