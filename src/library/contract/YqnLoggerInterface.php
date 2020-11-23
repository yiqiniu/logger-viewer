<?php


namespace yiqiniu\logger\library\contract;



interface YqnLoggerInterface
{


    /**
     * 初始化配置
     * @param array $option
     * @return mixed
     */
    public function initOption(array $option): bool;

    /**
     * 写日志
     * @param string $content
     * @return mixed
     */
    public function write(string $content): bool;


    /**
     * 获取根列表
     * @param string $fileID
     * @return array
     */
    public function treelist(): array;

    /**
     * 获取日志列表
     * @param string $fileID
     * @return array
     */
    public function filelist(string $fileID = ''): array;

    /**
     * 获取文件内容
     * @param string $fileID
     * @return string
     */
    public function read(string $fileID): string;

    /**
     * 删除文件
     * @param string $fileID
     * @return bool
     */
    public function delete(string $fileID,bool $isdir): bool;

}