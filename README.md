# 安装
composer create-project jinlulu/tp6_api

# .env
```
CONFIG_SCENE = [local|dev|prod]
请选择其中一个，会根据.env中的该配置匹配config中对应的配置
```
---
# 生成curd基本框架
```
php think curd --name Test
```
---
# 上线前的优化

## 生成classmap，优化加载
```
composer dump-autoload --classmap-authoritative
```
## 生成路由缓存，减小路由解析开销
```
php think optimize:route
```
## 生成数据库表字段缓存
```
php think optimize:schema
```

基于tp6做一些微调

# config支持多场景配置
根据env的CONFIG_SCENE读取config目录下相应文件夹的配置，实际就是继承App，重写了getConfigPath

# Log调整
- 添加trace_id，方便追踪

# session
- 修改为httponly

# response
- 添加文件流输出，框架默认是需要保存在本地再提供下载

# 限流
- 添加令牌桶限流，目前tp官方已经有了更加全面的限流组件，可以改为官方的 https://github.com/top-think/think-throttle

# BaseModel
添加常用model
子类中需要设置 如下属性
```
protected $getListField;
protected $addAllowField;
protected $getDataField;
protected $editAllowField;
```

# PoPo
- 参考java的POJO编写，根据接口入参定义对象即可，将定义好的对象放到Controller中，框架会自动实例化，PoPo对象根据反射会将入参绑定到对相应的参数上
- 也可以通过`array_2_popo_obj`将数组转换为poPo对象，或者将对象转为数组`popo_obj_2_obj`
- popo还可以跟验证器绑定，自动执行验证，只需要配置继承PoPo的子类中设置$validates，也可以改为手动验证，修改$autoValidate即可
以下是提交地址接口的popo对象的使用
<details>
    <summary>点击查看</summary>

<pre>
// Controller中
public function submitAddress(SubmitAddressParam $param): Response {
    // 输出参数
    echo $param->getId() . PHP_EOL;
    echo $param->getName() . PHP_EOL;
    echo $param->getAddress() . PHP_EOL;
    echo $param->getMobile() . PHP_EOL;
    print_r($param->toArray());
    // 手动触发验证
    $param->validate();
    return $this->outputSuccess();
}

// PoPo参数对象
use app\validate\SubmitAddress;
use ChengYi\abstracts\PoPo;

class SubmitAddressParam extends PoPo
{
    /**
     * @var array $validates 验证器
     */
    protected $validates = [
        SubmitAddress::class => 'create',
    ];

    /**
     * @var int $id
     */
    private int $id = 0;

    /**
     * @var string $name
     */
    private string $name = '';

    /**
     * @var string $address
     */
    private string $address = '';

    /**
     * @var string $mobile
     */
    private string $mobile = '';

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAddress(): string {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getMobile(): string {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile(string $mobile): void {
        $this->mobile = $mobile;
    }
}
</pre>
</details>
