<?php


namespace yiqiniu\logger\library\drives;


use yiqiniu\logger\library\contract\YqnLoggerInterface;

class File implements YqnLoggerInterface
{


    public function write(string $content): bool
    {
        // TODO: Implement write() method.
    }

    public function list(string $fileID = ''): array
    {
        // TODO: Implement list() method.
    }

    public function read(string $fileID): string
    {
        // TODO: Implement read() method.
    }

    public function delete(string $fileID): bool
    {
        // TODO: Implement delete() method.
    }
}