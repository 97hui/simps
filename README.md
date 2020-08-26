 ### 镜像构建

```
# 切换到项目目录，执行下面的构建镜像命令语句
docker build -t 镜像名称 .
```

### 环境变量说明
```
ENV_FILE 环境变量对应的文件
AUTORELOAD_PROGRAMS 要自动加载重启的服务
```

### 调试模式

```
# 部署web服务
docker run --rm -t -p 9501:9501 -v 项目代码路径:/var/www/ -e ENV_FILE=.env -e AUTORELOAD_PROGRAMS=http-talent --name 容器名 镜像名称
# 部署rpc服务
docker run --rm -t -p 9502:9501 -v 项目代码路径:/var/www/ -e ENV_FILE=.env.rpc -e AUTORELOAD_PROGRAMS=http-talent --name 容器名 镜像名称
```

> 调试模式初始时，需要自己执行一下 composer install

### 生产模式

```
# 部署web服务
docker run --rm -t -p 9501:9501 -e ENV_FILE=.env  --name 容器名 镜像名称
# 部署rpc服务
docker run --rm -t -p 9502:9501 -e ENV_FILE=.env.rpc  --name 容器名 镜像名称
```

> 访问url http://{ip}:9501/
