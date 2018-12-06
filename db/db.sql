drop table if exists category;
create table category(
  id int auto_increment primary key ,
  name varchar(32) not null default '',
  create_time datetime not null default NOW(),
  update_time datetime not null default NOW()
)default  charset=utf8 comment='Category';

insert into category(name) values('Apple'),('Huawei'),('Xiaomi'),('Oppo');