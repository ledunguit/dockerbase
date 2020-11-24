#### Copyright © Lê Đăng Dũng - Đại học Công nghệ thông tin - Đại học Quốc gia thành phố Hồ Chí Minh ####

```diff
- Facebook: https://www.facebook.com/LEDUNGUIT/
@@ Email: ledung.itsme@gmail.com @@
```
![layout](https://user-images.githubusercontent.com/64201705/99981474-03847700-2ddc-11eb-8eb3-d35b2ff48c9c.png)

## Tạo thư mục chứa toàn bộ những thứ cần thiết để deploy một application: ##

##### File Dockerfile

```Dockerfile
FROM php:7.4-fpm
RUN docker-php-ext-install mysqli # Cài đặt extension mysqli cho php
RUN docker-php-ext-install pdo_mysql # Cài đặt extension pdo cho php

WORKDIR /home/sites/testdocker // thư mục làm việc của container này sẽ nằm ở /home/sites/testdocker
```

##### File docker-compose.yml

```YAML
version: "3"

#NETWORK: Define network driver for container

networks:
  my-network:
    driver: bridge #network connection type: bridge

#VOLUMES

volumes:
  dir-site:
    driver_opts:
      type: none
      device: /home/backupdocker/app/ # dir that we want to mount to the container
      o: bind

services:
  #CONTAINER PHP: Container that run php7.4-fpm will handle PHP code for our application
  my-php:
    container_name: php-product
    build:
      dockerfile: Dockerfile # Container này sẽ được build bởi Dockerfile
      context: ./ # thư mục chứa file Dockerfile tính từ thư mục chứa file docker-compose.yml hiện tại.
    hostname: php
    restart: always
    networks:
      - my-network # dùng driver mạng mà chúng ta đã khai báo trước đó
    volumes:
      - dir-site:/home/sites/testdocker # Mount project code vào trong /home/sites/testdocker của container.

  #CONTAINER HTTPD
  my-httpd:
    container_name: c-httpd01
    image: "httpd:latest"
    hostname: httpd
    restart: always
    networks:
      - my-network
    volumes:
      - dir-site:/home/sites/testdocker
      - ./httpd.conf:/usr/local/apache2/conf/httpd.conf # Nạp file config của apache2 vào (Ở đây ta dùng ánh xạ thư mục cho nên file ở máy host sẽ đồng bộ với file ở container).
    ports:
      - "9999:80" # Port 9999 ở máy host và port 80 ở container (port mặc định của httpd - apache2).
      - "443:443" # Port dùng cho SSL HTTPS

  #CONTAINER MYSQL
  my-mysql:
    container_name: mysql-product
    image: "mysql:latest"
    hostname: mysql
    restart: always # always restart container if it run failed or crash
    networks:
      - my-network
    volumes:
      - ./database:/var/lib/mysql # lưu trữ lại database ngay trên máy host và đồng bộ với container database để tránh trường hợp mất mát dữ liệu.
      - ./my.cnf:/etc/mysql/my.cnf # nạp config cho mysql server bằng việc ánh xạ my.cnf ở máy host vào /etc/mysql.my.cnf trên container.
      - ./app/data.sql:/home/data.sql # ánh xạ file ./app/data.sql qua thư mục /home/data.sql trong container mysql-product
    environment:
      - MYSQL_ROOT_PASSWORD=root
      - MYSQL_DATABASE=nt208
      - MYSQL_USER=devuser
      - MYSQL_PASSWORD=devpass
```
##### Thư mục APP
```Diff
- Code PHP APP đã được viết sẵn từ trước.
```

## Copy config ra ngoài trước khi khởi tạo image bằng docker-compose ##
Trong khi tạo môi trường để chạy một web application thì ta cần cấu hình lại configuration của các services</br>
Copy các file config ra và chỉnh lại để lúc chạy lệnh ```docker-compose up``` thì những file config này sẽ được mount vào và load lên server.

##### Copy file config apache2 (httpd) ##
```Smali
docker run --rm -v [dir-host]:[dir-httpd-container] httpd cp /usr/local/apache2/conf/httpd.conf [dir-httpd-container]
tại project này: docker run --rm -v /home/backupdocker/:/home/ httpd /usr/local/apache2/httpd.conf /home/
```
Chỉnh sửa file conf: Enable các proxy, proxy-fcgi, rewrite.
```
LoadModule proxy_module modules/mod_proxy.so
LoadModule proxy_fcgi_module modules/mod_proxy_fcgi.so
LoadModule rewrite_module modules/mod_rewrite.so
```
Để kết nối tới container php-fpm, ta phải thêm cấu hình máy handle cho code php của chúng ta </br>
Thêm vào cuối file config httpd:
```
AddHanller "proxy:fcgi://[php-container-name]" .php
```
##### Current project: php7.4-fpm được cài đặt trong container có tên là php-product:
```
AddHandler "proxy:fcgi://php-product:9000" .php
```

##### Copy file config for mysql server ##
```Smali
docker run --rm -v [dir-host]:[dir-mysql-container] mysql cp /etc/mysql/my.cnf [dir-mysql-container]
tại project này: docker run --rm -v /home/backupdocker/:/home/ mysql cp /etc/mysql/my.cnf /home/
```
Chỉnh sửa file conf: Cấu hình authentication mặc định cho mysql là native password. Thêm dòng này vào cuối file config:
```
default-authentication-plugin=mysql_native_password
```
#### Nạp file database đã ánh xạ ở container mysql: ./app/data.sql:/home/data.sql
Mở terminal lên chạy lệnh: ```docker exec -it [mysql-container-id] /bin/bash```</br>
Chạy tiếp lệnh để nạp database: ```mysql -uroot -p [database-name] < /home/data.sql```</br>
Nhập pass

#### RUN:
```
docker-compose up
```

## Một số lệnh cơ bản ##

<b>Dừng</b> toàn bộ container: <b>docker</b> container stop $(<b>docker</b> ps -a -q)</br>
<b>Xóa</b> toàn bộ volumes đã define: <b>docker</b> volume rm $(<b>docker</b> volume ls -q)</br>
<b>Xóa</b> toàn bộ containers: <b>docker</b> rm $(<b>docker</b> ps -aq)</br>
<b>Xóa</b> toàn bộ images: <b>docker</b> rmi $(<b>docker</b> images -q)</br>
