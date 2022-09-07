<?php


namespace yiqiniu\logger\drives;


use yiqiniu\logger\library\contract\YqnLoggerInterface;

class Database implements YqnLoggerInterface
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

    public function initOption(array $option): bool
    {
        // TODO: Implement initOption() method.
    }

    public function treelist(): array
    {
        // TODO: Implement treelist() method.
    }

    public function filelist(string $fileID = ''): array
    {
        // TODO: Implement filelist() method.
    }

    public function page(string $fileID, $page, int $page_size = 200): string
    {
        // TODO: Implement page() method.
    }
}