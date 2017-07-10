use DBs1511427;

drop table if exists images;
drop table if exists coupon_targets;
drop table if exists owned_coupons;
drop table if exists products;
drop table if exists coupons;
drop table if exists members;
drop table if exists dealers;
drop table if exists delivery_types;

create table members(
  id integer primary key auto_increment,
  name varchar(255) not null unique,
  password text not null,
  admin boolean not null default false
) default charset=utf8;

create table dealers(
  id integer primary key auto_increment,
  name varchar(255) not null unique,
  password text not null
) default charset=utf8;

create table delivery_types(
  id integer primary key auto_increment,
  name text not null,
  charge integer check(charge >= 0)
) default charset=utf8;

create table coupons(
  id integer primary key auto_increment,
  term_type char(8) not null check(term_type = 'period' || term_type='date'),
  term datetime,
  discount_type char(8) check(discount_type = 'parcent' || discount_type = 'yen'),
  discount_value integer check(discount_value >= 0)
) default charset=utf8;

create table products(
  id integer primary key auto_increment,
  name text not null,
  condition_type char(8) not null check(condition_type='new' || condition_type='used'),
  stock integer not null check(stock >= 0),
  price integer not null check(price >= 0),
  description text,
  created_date timestamp default current_timestamp,
  suspention boolean default false,
  dealer_id integer,
  delivery_type_id integer,
  coupon_id integer,
  foreign key (dealer_id) references dealers(id),
  foreign key (delivery_type_id) references delivery_types(id),
  foreign key (coupon_id) references coupons(id)
) default charset=utf8;

create table images(
  id integer primary key auto_increment,
  file varchar(255) not null unique,
  product_id integer,
  foreign key (product_id) references products(id)
) default charset=utf8;

create table coupon_targets(
  coupon_id integer,
  product_id integer,
  primary key (coupon_id, product_id),
  foreign key (coupon_id) references coupons(id),
  foreign key (product_id) references products(id)
) default charset=utf8;

create table owned_coupons(
  id integer primary key auto_increment,
  owned_date timestamp default current_timestamp,
  invalidate_date datetime,
  used boolean default false,
  member_id integer,
  coupon_id integer,
  foreign key (member_id) references members(id),
  foreign key (coupon_id) references coupons(id)
) default charset=utf8;

create table searched_products(
  id integer primary key auto_increment,
  searched_date timestamp default current_timestamp,
  member_id integer not null,
  product_id integer not null,
  foreign key (member_id) references members(id),
  foreign key (product_id) references products(id)
) default charset=utf8;

create table purchased_products(
  id integer primary key auto_increment,
  purchased_date timestamp default current_timestamp,
  member_id integer not null,
  product_id integer not null,
  foreign key (member_id) references members(id),
  foreign key (product_id) references products(id)
) default charset=utf8;
