<?php

namespace App\Helpers;
use Symfony\Component\HttpFoundation\File\File;

class TmpFile extends File {

    private $handle;

    public function __construct(string $contents)
    {
        $this->handle = tmpfile();
        fwrite($this->handle, $contents);

        parent::__construct(stream_get_meta_data($this->handle)['uri']);
    }

}
