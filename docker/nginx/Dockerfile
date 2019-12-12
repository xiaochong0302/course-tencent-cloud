FROM nginx:1.14

ENV TZ=Asia/Shanghai

RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo ${TZ} > /etc/timezone

VOLUME ["/etc/nginx/conf.d", "/var/www/html", "/var/log/nginx"]

EXPOSE 6379