create database if not exists curso_angular;
use curso_angular;

create table productos(
     id             int(255) auto_increment not null
    ,nombre         varchar(255)
    ,descripcion    text
    ,precio         varchar(255)
    ,imagen         varchar(255)
    ,constraint pk_productos primary key(id)
) ENGINE=InnoDb;

