ARG MYSQL_VERSION
FROM ${MYSQL_VERSION}

COPY ./mysql.cnf /etc/mysql/conf.d/mysql.cnf

RUN chmod 644 /etc/mysql/conf.d/mysql.cnf && usermod -u 1000 mysql && groupmod -g 1000 mysql