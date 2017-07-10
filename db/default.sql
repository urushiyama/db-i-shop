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

-- insert members test data (assert_success)
insert into members (name, password) values("山田花子", "$2y$10$MIIk1Up9ejD/9Dniq8tf/OYWCqMT1WV274AV/t.8uuWifhmqabE6a");
insert into members (name, password) values("小川太郎","$2y$10$FHeDZhtb4reSMXh8IICiv.r0Sm86xo2SkDAQmDrNnAU4vQEJPPGPW");
insert into members (name, password) values("太田二郎","$2y$10$7lWZQHm2QcHpGNb58hygF.cDlf7CHp5esmUZjD8pi7OBGa0Uq1.Nm");
insert into members (name, password) values("大川翔子","$2y$10$ST6.Gum7PRs.y0UV7ksKGuDzQnloUriPUE24FLfCmj5m5jzAJyli6");
insert into members (name, password) values("浅田舞佳","$2y$10$uwy.coOroPQxZX3eaOk/yuSNNuLwoblc5LjiSEgly9zPzP4S6bNfq");
insert into members (name, password) values("戸田シンジ","$2y$10$SfuFn9tvRm2YVEE3ONF04uEu6a9XgpAHmYqTO/03452htaDysOO4a");
insert into members (name, password) values("堀川兵五郎","$2y$10$3k64ZPAZQVn1C5jhdoM9/u65H9qPcFtM9iiXEsrBdHsYPU7uUA6X6");
insert into members (name, password) values("Aran Hartl","$2y$10$6ZCT9EbbnqX8rqwv0mVwKuE8Ij47XhwyTtuJ.SM01UhlaFuGLrIlW");
insert into members (name, password) values("Bob Carpenter","$2y$10$MyF2A40QQeVkjR1.yH4/Q.9740IMsEN2vXqrPlzOuVr7vX3wyyC6i");
insert into members (name, password) values("顾 伟德","$2y$10$S48CtSSucjr2.4I1A/eC8e4543iIGsBpWHeYTHjYTKDLzgrvz8MRu");

-- insert an admin member test data (assert_success)
insert into members (name, password, admin) values("Administrator", "$2y$10$o/gfz/uSajSVw9wbowZZJOm5.akGO9euNdm//LV9bJu/TujBZYDia", true);

-- insert dealers test data (assert_success)
insert into dealers (name, password) values("山田花子","$2y$10$jVdgTTZZZ63jGTCD.aIRn.cU7w49W4za1ayxRkTgt35nxzwvv9Hq2");
insert into dealers (name, password) values("小川太郎","$2y$10$ioumlA.s/7Me1cI6q4x8xuQnsF2WGzTT0x0yirQ.Q/wJPlIHaZTtC");
insert into dealers (name, password) values("太田二郎","$2y$10$HxsAIhrPBNNw3DvLDA6KsuSVklIhY//A33nG.RYRKotfMS5OuWlYG");
insert into dealers (name, password) values("大川翔子","$2y$10$nVyNhIPlJV.teGoxGlga7OPqyquB/p58mDt00WJHn9J4oBki43JUe");
insert into dealers (name, password) values("浅田舞佳","$2y$10$NE7lNndZscz52OtNbwClQOb1j6mimvFuB17Fy6fhyy0sD0sHrri8e");
insert into dealers (name, password) values("戸田シンジ","$2y$10$sGnYTWf2ykeqHWqMGIJSjej9bfn.tfGes7X6fGDVlR56nU4uTnM.6");
insert into dealers (name, password) values("堀川兵五郎","$2y$10$5mewz7osvU0Q9ySB7YP97.jl27eAR1f5VelAe39g/bU9vG/iSYAj6");
insert into dealers (name, password) values("Aran Hartl","$2y$10$FfZcpTqKBFyNnqTdpLM2o.stWu4ruYlijOd4gOlYJQbzFVvbBJ5.K");
insert into dealers (name, password) values("Bob Carpenter","$2y$10$Tky41rq9h2c/DPDk620OqePSZd5f9ttxKmxUt.UdAQabZURp4muYq");
insert into dealers (name, password) values("顾 伟德","$2y$10$MwD5FufurD9E5iHG6HDyqucNBXyH5H9YvKgZ4sDc3ywHJM9CO1vHK");

-- insert delivery_types test data (assert_success)
insert into delivery_types (name, charge) values("宅急便",864);
insert into delivery_types (name, charge) values("メール便",320);
insert into delivery_types (name, charge) values("UPS",3480);
insert into delivery_types (name, charge) values("翌日配送 宅急便",1230);

-- insert coupons test data (assert_success)
insert into coupons (term_type, term, discount_type, discount_value) values('period', '1001-01-01 00:00:00', 'yen', 800);
insert into coupons (term_type, term, discount_type, discount_value) values('period', '1000-02-01 00:00:00', 'parcent', 10);
insert into coupons (term_type, term, discount_type, discount_value) values('date', '2018-02-15 00:00:00', 'yen', 3000);
insert into coupons (term_type, term, discount_type, discount_value) values('date', '2017-11-01 12:00:00', 'parcent', 50);

-- insert products test data (assert_success)
insert into products (name, condition_type, stock, price, description, created_date, dealer_id, delivery_type_id, coupon_id) select '愛媛県産みかん1箱', 'new', 200, 1620, 'たっぷり入ってこの価格！安いのにはワケがあります！', '2016-07-20 12:34:41', dealers.id, delivery_types.id, coupons.id from dealers, delivery_types, coupons order by rand() limit 1;
insert into products (name, condition_type, stock, price, description, created_date, dealer_id, delivery_type_id, coupon_id) select '耐衝撃ポータブルHDD 3TB USB 3.0 Micro-B to Aケーブル付属', 'new', 1000, 12800, 'たっぷり3TBでこの価格！耐衝撃だから落下にも強い！', '2016-03-14 15:23:40', dealers.id, delivery_types.id, coupons.id from dealers, delivery_types, coupons order by rand() limit 1;
insert into products (name, condition_type, stock, price, description, created_date, dealer_id, delivery_type_id, coupon_id) select '最新OS搭載 11インチノートパソコン 内部レストア済', 'used', 4, 39800, '最新OS搭載でこの価格！ネットも文書作成もこれ一台で始められます！', '2016-11-05 02:00:00', dealers.id, delivery_types.id, coupons.id from dealers, delivery_types, coupons order by rand() limit 1;
insert into products (name, condition_type, stock, price, description, created_date, dealer_id, delivery_type_id, coupon_id) select '液晶保護フィルム 新しいOranePadに最適 アンチグレア', 'new', 20, 2200, '液晶保護フィルムで傷を避ける、日常の使用に通用、反射は目の疲れを除去する。', '2014-05-22 09:30:00', dealers.id, delivery_types.id, null from dealers, delivery_types order by rand() limit 1;

insert into products (name, condition_type, stock, price, description, created_date, dealer_id, delivery_type_id, coupon_id) select 'Product hoge hoge', 'new', 200, 210, 'foo bar null hoge fuga baz boo', '2013-09-01 23:31:56', dealers.id, delivery_types.id, null from dealers, delivery_types order by rand() limit 26;

-- insert images test data (assert_success)
insert into images (file, product_id) select '/images/ionr24swgnj.png', products.id from products order by rand() limit 1;
insert into images (file, product_id) select '/images/2ht45s5zdfb.png', products.id from products order by rand() limit 1;
insert into images (file, product_id) select '/images/f153haernz4.png', products.id from products order by rand() limit 1;
insert into images (file, product_id) select '/images/rq346jarger.png', products.id from products order by rand() limit 1;

-- insert coupon_targets test data (assert_success)
insert into coupon_targets (coupon_id, product_id) select coupons.id, products.id from coupons, products order by rand() limit 20;

-- insert owned_coupons test data (assert_success)
insert into owned_coupons (invalidate_date, member_id, coupon_id) select '2020-05-28 00:00:00', members.id, coupons.id from members, coupons order by rand() limit 1;
insert into owned_coupons (invalidate_date, member_id, coupon_id) select '2018-12-25 00:00:00', members.id, coupons.id from members, coupons order by rand() limit 1;
insert into owned_coupons (invalidate_date, member_id, coupon_id) select '2019-08-11 00:00:00', members.id, coupons.id from members, coupons order by rand() limit 1;
insert into owned_coupons (invalidate_date, member_id, coupon_id) select '2020-01-01 00:00:00', members.id, coupons.id from members, coupons order by rand() limit 1;
insert into owned_coupons (invalidate_date, member_id, coupon_id) select '2017-05-01 00:00:00', members.id, coupons.id from members, coupons order by rand() limit 1;
insert into owned_coupons (invalidate_date, used, member_id, coupon_id) select '2001-01-19 00:00:00', true, members.id, coupons.id from members, coupons order by rand() limit 1;

-- insert searched_products test data (assert_success)
insert into searched_products (searched_date, member_id, product_id) select ADDTIME(CONCAT_WS(' ','2014-07-01' + INTERVAL RAND() * 31 DAY, '00:00:00'), SEC_TO_TIME(FLOOR(0 + (RAND() * 86401)))), members.id, products.id from members, products order by rand() limit 10;

-- insert purchased_products test data (assert_success)
insert into purchased_products (purchased_date, member_id, product_id) select ADDTIME(CONCAT_WS(' ','2014-07-01' + INTERVAL RAND() * 31 DAY, '00:00:00'), SEC_TO_TIME(FLOOR(0 + (RAND() * 86401)))), members.id, products.id from members, products order by rand() limit 10;
