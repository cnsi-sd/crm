<?php

namespace App\Helpers;

class EmailAttachementNormalized
{
    protected string $name;
    protected TmpFile $tmpFile;

    /**
     * @param string $name
     * @param TmpFile $tmpFile
     */
    public function __construct(string $name, TmpFile $tmpFile)
    {
        $this->name = $name;
        $this->tmpFile = $tmpFile;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return TmpFile
     */
    public function getTmpFile(): TmpFile
    {
        return $this->tmpFile;
    }

    /**
     * @param TmpFile $tmpFile
     */
    public function setTmpFile(TmpFile $tmpFile): void
    {
        $this->tmpFile = $tmpFile;
    }


}
