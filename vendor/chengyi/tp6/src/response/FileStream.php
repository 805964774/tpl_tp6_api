<?php


namespace ChengYi\response;

use think\Response;

/**
 * 输出文件流，框架默认的是读取文件再输出，这种方式无需在服务器保存文件
 * 但是要保证输出之前无其他内容输出，不然输出的文件会有问题
 * Class FileStream
 * @package ChengYi\response
 */
class FileStream extends Response
{
    protected $expire = 360;
    protected $name;
    protected $mimeType;
    protected $isContent = true;
    protected $force     = true;

    public function __construct($data = '', int $code = 200)
    {
        $this->init($data, $code);
    }

    /**
     * 处理数据
     * @access protected
     * @param  mixed $data 要处理的数据
     * @return mixed
     * @throws \Exception
     */
    protected function output($data)
    {

        if (!empty($this->name)) {
            $name = $this->name;
        } else {
            $name = !$this->isContent ? pathinfo($data, PATHINFO_BASENAME) : '';
        }

        if ($this->isContent) {
            $mimeType = $this->mimeType;
        } else {
            $mimeType = $this->getMimeType($data);
        }

        $this->header['Pragma']                    = 'public';
        $this->header['Content-Type']              = $mimeType ?: 'application/octet-stream';
        $this->header['Cache-control']             = 'max-age=' . $this->expire;
        $this->header['Content-Disposition']       = ($this->force ? 'attachment; ' : '') . 'filename="' . $name . '"';
        $this->header['Content-Transfer-Encoding'] = 'binary';
        $this->header['Expires']                   = gmdate("D, d M Y H:i:s", time() + $this->expire) . ' GMT';

        $this->lastModified(gmdate('D, d M Y H:i:s', time()) . ' GMT');

        return $this->isContent ? $data : file_get_contents($data);
    }

    /**
     * 设置是否为内容 必须配合mimeType方法使用
     * @access public
     * @param  bool $content
     * @return $this
     */
    public function isContent(bool $content = true)
    {
        $this->isContent = $content;
        return $this;
    }

    /**
     * 设置有效期
     * @access public
     * @param  integer $expire 有效期
     * @return $this
     */
    public function expire(int $expire)
    {
        $this->expire = $expire;
        return $this;
    }

    /**
     * 设置文件类型
     * @access public
     * @param  string $filename 文件名
     * @return $this
     */
    public function mimeType(string $mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * 设置文件强制下载
     * @access public
     * @param  bool $force 强制浏览器下载
     * @return $this
     */
    public function force(bool $force)
    {
        $this->force = $force;
        return $this;
    }

    /**
     * 获取文件类型信息
     * @access public
     * @param  string $filename 文件名
     * @return string
     */
    protected function getMimeType(string $filename): string
    {
        if (!empty($this->mimeType)) {
            return $this->mimeType;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        return finfo_file($finfo, $filename);
    }

    /**
     * 设置下载文件的显示名称
     * @access public
     * @param  string $filename 文件名
     * @param  bool   $extension 后缀自动识别
     * @return $this
     */
    public function name(string $filename, bool $extension = true)
    {
        $this->name = $filename;

        if ($extension && false === strpos($filename, '.')) {
            $this->name .= '.' . pathinfo($this->data, PATHINFO_EXTENSION);
        }

        return $this;
    }
}
