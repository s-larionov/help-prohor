## Init project
```bash
git clone git@github.com:s-larionov/help-prohor.git

# clone submodules
cd /path/to/project
git submodule init
git submodule update
```

## Prerun steps
### Create runtime directory with r/w permissions for php-process
```bash
cd /path/to/project
mkdir -m 777 protected/runtime
```

## Multiple configuration files
For different environment variable APPLICATION_MODE you may create config file.
For example: @see protected/configs/dev_larry.php

## Run migration tool
```bash
# Set enviroment variable. In not set then run in production mode
export APPLICATION_MODE=dev_larry
# Run migration
cd /path/to/project/protected
./yiic migrate
# For silent mode (without any questions) run
./yiic migrate --interactive=false
```

## Configure nginx
### club.vladmama.ru.conf
```nginx
server {
	listen 80;
	server_name club.vladmama.ru;

	access_log log/nginx/club.vladmama.ru-access.log;
	error_log log/nginx/club.vladmama.ru-error.log;

	set $www_root /opt/club.vladmama.ru/public;
	set $app_mode production;

	include include/yii.conf;
}
```
### include/yii.conf
```nginx
root $www_root;

charset utf-8;
set $bootstrap index.php;
index index.html $bootstrap;

location ~ /\. {
	deny all;
}

location ~ ^(.+\.(js|css|jpg|gif|png|ico|swf|mp3|html|eot|woff|ttf|svg))$ {
	try_files $uri /$bootstrap?$args;
}

# rewrite ^(.*)$ /$bootstrap?$1 last;
# try_files $uri $uri/ /$bootstrap?$args;

location ~ .* {
	set $fsn /$bootstrap;
	if (-f $document_root$fastcgi_script_name){
		set $fsn $fastcgi_script_name;
	}

	include fastcgi.conf;
	fastcgi_pass    php-fpm;

	fastcgi_param   SCRIPT_FILENAME         $document_root$fsn;
	fastcgi_param   PATH_INFO               $fastcgi_path_info;
	fastcgi_param   PATH_TRANSLATED         $document_root$fsn;
	fastcgi_param   APPLICATION_MODE        $app_mode;
}
```
