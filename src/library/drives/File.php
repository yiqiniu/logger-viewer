<?php


namespace yiqiniu\logger\library\drives;


use yiqiniu\logger\library\contract\YqnLoggerInterface;

class File implements YqnLoggerInterface
{


    /**
     * @var
     */
    private $app;

    /**
     * @var string[]
     */
    private $_option = [
        'base_dir' => '',
        'log_dir' => [],
        'format' => [],
    ];

    /**
     * File constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }


    /**
     * 初始驱动
     * @param array $option
     * @return bool
     */
    public function initOption(array $option): bool
    {
        $this->_option = array_merge($this->_option, $option);
        if (empty($this->_option['base_dir'])) {
            return false;
        }
        //只能是当前项目的目录
        if (stripos($this->_option['base_dir'], $this->app->getRootPath()) === false) {
            return false;
        }
        return true;
    }

    public function write(string $content): bool
    {
        // TODO: Implement write() method.
    }


    /**
     * 生成目录树，最多支持三级
     * @param $dir
     * @param $result
     * @param $level
     * @return array
     */
    protected function treelistAction($dir, &$result, $level, $parentpath = '')
    {

        $files = scandir($dir);

        foreach ($files as $k => $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $filename = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filename) && $level < 3) {
                // 生成路径
                $path = empty($parentpath) ? $file : $parentpath . '|' . $file;
                $node = ['title' => $file, 'id' => $file, 'children' => [], 'path' => $path, 'spread' => true];
                $node_children =& $node['children'];
                $result[] = $node;
                $this->treelistAction($filename, $node_children, $level + 1, $path);
            }
        }
    }

    /**
     * 获取目录树
     * @return array
     */
    public function treelist(): array
    {


        $result = [];

        if (!empty($this->_option['base_dir'])) {
            if (!empty($this->_option['log_dir'])) {
                foreach ($this->_option['log_dir'] as $dir) {
                    $node = ['title' => $dir, 'id' => $dir, 'children' => [], 'path' => $dir, 'spread' => true];
                    $node_children =& $node['children'];
                    $result[] = $node;
                    $this->treelistAction($this->_option['base_dir'] . $dir, $node_children, 1, $dir);
                }

            } else {
                $this->treelistAction($this->_option['base_dir'], $result, 0);
            }

        }
        return $result;
    }

    /**
     * 获取目录文件
     * @param string $fileID
     * @return array
     */
    public function filelist(string $fileID = ''): array
    {

        $dest_dir = $this->_option['base_dir'];
        if ($fileID !== '') {
            $dest_dir .= DIRECTORY_SEPARATOR . str_replace('|', DIRECTORY_SEPARATOR, $fileID);
        }
        $result = [];
        if (file_exists($dest_dir)) {
            $files = scandir($dest_dir);
            foreach ($files as $k => $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $fileinfo = new \SplFileInfo($dest_dir . DIRECTORY_SEPARATOR . $file);
                //过滤不支持格式
                if (!$fileinfo->isDir()
                    && !empty($this->_option['format'])
                    && !in_array($fileinfo->getExtension(), $this->_option['format'], true)) {
                    continue;
                }
                $result[] = [
                    'filename' => $fileinfo->getFilename(),
                    'filesize' => $fileinfo->getSize(),
                    'is_dir' => $fileinfo->isDir() ? 1 : 0,
                    'last_time' => date('Y-m-d H:i:s', $fileinfo->getMTime()),
                    'path' => $fileID . '|' . $fileinfo->getFilename()
                ];

            }
        }
        return $result;
    }


    /**
     * 读取文件内容
     * @param string $fileID
     * @return string
     */
    public function read(string $fileID): string
    {
        $dest_dir = $this->_option['base_dir'];
        if ($fileID !== '') {
            $dest_dir .= DIRECTORY_SEPARATOR . str_replace('|', DIRECTORY_SEPARATOR, $fileID);
        }
        if (file_exists($dest_dir)) {
            return file_get_contents($dest_dir);
        }
        return '';
    }

    /**
     * 删除文件
     * @param string $fileID 多个文件以;分隔
     * @return bool
     */
    public function delete(string $fileID): bool
    {
        $dest_dir = $this->_option['base_dir'];
        //删除文件
        $files = explode(';', $fileID);
        foreach ($files as $file) {
            $file = $dest_dir .= DIRECTORY_SEPARATOR . str_replace('|', DIRECTORY_SEPARATOR, $file);
            if (file_exists($file)) {
                if (is_dir($dest_dir)) {
                    $this->deldir($dest_dir);
                } else {
                    //过滤不支持格式
                    if (!empty($this->_option['format'])
                        && !in_array($fileinfo->getExtension(), $this->_option['format'], true)) {
                        continue;
                    } else {
                        unlink($file);
                    }

                }
            }
        }
        return true;
    }

    /**
     * 删除文件夹
     * @param $dir
     * @return bool
     */
    private function deldir($dir)
    {
        //先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file !== '.' && $file !== '..') {
                $fullpath = $dir . DIRECTORY_SEPARATOR . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath);
                }
            }
        }

        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        }
        return false;
    }

}