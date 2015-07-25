FROM wnameless/oracle-xe-11g:latest
MAINTAINER georges.alkhouri@stud.htwk-leipzig.de

ENV dbpwd ""
ENV dbuser ""

RUN apt-get install -y nano
RUN echo "HTWK = (DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = ora10glv.imn.htwk-leipzig.de)(PORT = 1521)) (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = ORA10GLV) ) )" >> /u01/app/oracle/product/11.2.0/xe/network/admin/tnsnames.ora

ADD ./ /

RUN chmod +x /run.sh

CMD ["/run.sh"]
