FROM mysql:5.6

ENV TZ=Asia/Shanghai

RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo ${TZ} > /etc/timezone

VOLUME  ["/usr/lib/mysql", "/var/log/mysql"]

EXPOSE 3306