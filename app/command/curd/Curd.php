<?php
declare (strict_types=1);

namespace app\command\curd;

use app\common\constant\ErrorNums;
use app\common\exception\AppException;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class Curd extends Command
{
    protected $type;

    protected function configure() {
        // 指令配置
        $this->setName('curd')
            ->setDefinition([
                new Option('name', null, Option::VALUE_REQUIRED, "控制器名称"),
            ])
            ->setDescription('the curd command');
    }

    protected function execute(Input $input, Output $output) {
        $name = ucfirst(trim($input->getOption('name')));
        // 1、build controller
        $this->buildController($name, $output);
        // 2、build model
        $this->buildModel($name, $output);
        // 3、build validate
        $this->buildValidate($name, $output);
        // 4、build service
        $this->buildService($name, $output);
        return true;
    }

    /**
     * 生成controller文件
     * @param $name
     * @param $output
     * @throws AppException
     */
    private function buildController($name, $output) {
        $pathname = $this->getPathName('controller/' . $name);
        $this->buildFile($name, $pathname, 'controller', $output);
    }

    /**
     * 生成模型文件
     * @param $name
     * @param $output
     * @throws AppException
     */
    private function buildModel($name, $output) {
        $pathname = $this->getPathName('model/' . $name);
        $this->buildFile($name, $pathname, 'model', $output);
    }

    /**
     * 生成校验文件
     * @param $name
     * @param $output
     * @throws AppException
     */
    private function buildValidate($name, $output) {
        $pathname = $this->getPathName('validate/' . $name);
        $this->buildFile($name, $pathname, 'validate', $output);
    }

    /**
     * 生成service文件
     * @param $name
     * @param $output
     * @throws AppException
     */
    private function buildService($name, $output) {
        $pathname = $this->getPathName('service/' . $name);
        $this->buildFile($name, $pathname, 'service', $output);
    }

    /**
     * 生成文件
     * @param $classname
     * @param $pathname
     * @param $type
     * @param $output
     * @throws AppException
     */
    private function buildFile($classname, $pathname, $type, $output) {
        if (is_file($pathname)) {
            throw new AppException(ErrorNums::PARAM_ILLEGAL, $classname . 'already exists!');
        }
        if (!is_dir(dirname($pathname))) {
            mkdir(dirname($pathname), 0755, true);
        }
        file_put_contents($pathname, $this->buildClass($classname, $type));
        $output->writeln('<info>' . $pathname . ' created successfully.</info>');
    }

    protected function buildClass(string $name, $type): string {
        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        $class = str_replace($namespace . '\\', '', $name);
        $stub = file_get_contents($this->getStub($type));

        return str_replace(['{%className%}'], [
            $class,
        ], $stub);
    }

    protected function getStub($type): string {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . $type . '.stub';
    }

    protected function getNamespace(string $app): string {
        return 'app' . ($app ? '\\' . $app : '');
    }

    protected function getPathName(string $name): string {
        $name = str_replace('app\\', '', $name);
        return $this->app->getBasePath() . ltrim(str_replace('\\', '/', $name), '/') . '.php';
    }
}
