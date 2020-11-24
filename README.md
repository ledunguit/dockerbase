#### Copyright © Lê Đăng Dũng - Đại học Công nghệ thông tin - Đại học Quốc gia thành phố Hồ Chí Minh ####

```diff
- Facebook: https://www.facebook.com/LEDUNGUIT/
@@ Email: ledung.itsme@gmail.com @@
```
![layout](https://user-images.githubusercontent.com/64201705/99981474-03847700-2ddc-11eb-8eb3-d35b2ff48c9c.png)

## Tạo thư mục chứa toàn bộ những thứ cần thiết để deploy một application: ##

##### Dockerfile

```Dockerfile
FROM php:7.4-fpm
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql

WORKDIR /home/sites/testdocker
```

##### docker-compose.yml

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
      dockerfile: Dockerfile
      context: ./
    hostname: php
    restart: always
    networks:
      - my-network
    volumes:
      - dir-site:/home/sites/testdocker

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
      - ./httpd.conf:/usr/local/apache2/conf/httpd.conf
    ports:
      - "9999:80"
      - "443:443"

  #CONTAINER MYSQL
  my-mysql:
    container_name: mysql-product
    image: "mysql:latest"
    hostname: mysql
    restart: always # always restart container if it run failed or crash
    networks:
      - my-network
    volumes:
      - ./database:/var/lib/mysql
      - ./my.cnf:/etc/mysql/my.cnf
      - ./app/data.sql:/home/data.sql # ánh xạ file ./app/data.sql qua thư mục /home/data.sql trong container mysql-product
    environment:
      - MYSQL_ROOT_PASSWORD=root
      - MYSQL_DATABASE=nt208
      - MYSQL_USER=devuser
      - MYSQL_PASSWORD=devpass
```
##### APP
```Diff
- Code PHP APP
```

## Copy config ra ngoài trước khi khởi tạo image bằng docker-compose ##
Trong khi tạo môi trường để chạy một web application thì ta cần cấu hình lại configuration của các services
Copy các file config ra và chỉnh lại để lúc chạy lệnh ```docker-compose up``` thì những file config này sẽ được mount vào và load lên server.

###### Copy file config apache2 (httpd) ##
```Smali
docker run --rm -v [dir-host]:[dir-httpd-container] httpd cp /usr/local/apache2/conf/httpd.conf [dir-httpd-container]
tại project này: docker run --rm -v /home/backupdocker/:/home/ httpd /usr/local/apache2/httpd.conf /home/
```

###### Copy file config for mysql server ##
```Smali
docker run --rm -v [dir-host]:[dir-mysql-container] mysql cp /etc/mysql/my.cnf [dir-mysql-container]
tại project này: docker run --rm -v /home/backupdocker/:/home/ mysql cp /etc/mysql/my.cnf /home/
```

## Một số lệnh cơ bản ##

<b>Dừng</b> toàn bộ container: <b>docker</b> container stop $(<b>docker</b> ps -a -q)</br>
<b>Xóa</b> toàn bộ volumes đã define: <b>docker</b> volume rm $(<b>docker</b> volume ls -q)</br>
<b>Xóa</b> toàn bộ containers: <b>docker</b> rm $(<b>docker</b> ps -aq)</br>
<b>Xóa</b> toàn bộ images: <b>docker</b> rmi $(<b>docker</b> images -q)</br>

