FROM redis:4.0

ENV TZ=Asia/Shanghai

RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo ${TZ} > /etc/timezone

VOLUME ["/data"]

EXPOSE 6379