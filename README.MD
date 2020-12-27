Должен быть установлен imagemagick и php-imagick

apt install imagemagick php-imagick

Развернуть на хосте:
* cd /var/www
* git clone https://github.com/jerrygacket/ws-test.git
* конфиг nginx для виртуального хоста

      server {
      #имя сервера:
      server_name ws-test;
      listen 45453;
      #логи
      access_log /var/log/nginx/ws-test.access.log;
      error_log  /var/log/nginx/ws-test.error.log;
    
      # корневая директория
      root /var/www/ws-test/;
      index demo.php;
    
      location ~ \.php$ {
      try_files $uri = 404;
      include fastcgi_params;
      fastcgi_pass  unix:/run/php/php7.3-fpm.sock;
      fastcgi_index /demo.php;
    
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
      }
    
      location / {
            try_files $uri $uri/ /demo.php$is_args$args;
      }
    
      location ~ ^/assets/.*\.php$ {
      deny all;
      }
      }

 