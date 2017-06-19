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
  name varchar(32) not null unique,
  password varchar(255) not null
);

create table dealers(
  id integer primary key auto_increment,
  name varchar(32) not null unique,
  password varchar(255) not null
);

create table delivery_types(
  id integer primary key auto_increment,
  name varchar(16) not null,
  charge integer check(charge >= 0)
);

create table coupons(
  id integer primary key auto_increment,
  term_type char(8) not null check(term_type = 'period' || term_type='date'),
  term datetime,
  discount_type char(8) check(discount_type = 'parcent' || discount_type = 'yen'),
  discount_value integer check(discount_value >= 0)
);

create table products(
  id integer primary key auto_increment,
  name varchar(128) not null,
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
);

create table images(
  id integer primary key auto_increment,
  file varchar(64) not null unique,
  product_id integer,
  foreign key (product_id) references products(id)
);

create table coupon_targets(
  coupon_id integer,
  product_id integer,
  primary key (coupon_id, product_id),
  foreign key (coupon_id) references coupons(id),
  foreign key (product_id) references products(id)
);

create table owned_coupons(
  id integer primary key auto_increment,
  owned_date timestamp default current_timestamp,
  invalidate_date datetime,
  used boolean default false,
  member_id integer,
  coupon_id integer,
  foreign key (member_id) references members(id),
  foreign key (coupon_id) references coupons(id)
);

-- insert members test data (assert_success)
insert into members (name, password) values("山田花子", "$2y$10$ZIapuX84/BroB5P2hpaz2.j5HzxRYiZugDLvJR2fUTeHlbwQkFe3O");
insert into members (name, password) values("小川太郎","$2y$10$5sXkCvDZE1LwQyJpyc5phud3Cnqvbp/yf1dGThyvTmIsof7Ivqsae");
insert into members (name, password) values("太田二郎","$2y$10$Fpr20ZptFDE4KRP54d1Bi.gg2t4sF.SNavvFf.lnFFl..vjbLVaRW");
insert into members (name, password) values("大川翔子","$2y$10$WhLKKTz6IH79zGBG8z9/dei3m72mMpqp.hjrvZ7l9NSLCd5TyPCgC");
insert into members (name, password) values("浅田舞佳","$2y$10$rwnstnBspQ.wMTj4K2JOW.hjqqORLZs180l502lm/iijskP/sHc9.");
insert into members (name, password) values("戸田シンジ","$2y$10$/qdkubcKZaT523W9lWuWO.h6BnN5lGQMTGfC4TI.CC9HcI.NMlJe.");
insert into members (name, password) values("堀川兵五郎","$2y$10$bmX4P9cMbsGY2WYhz9WwSeTiTKoYSbPLw6qlIUwINWyLlvXrGt4JC");
insert into members (name, password) values("Aran Hartl","$2y$10$xCYLV/mZ79DA6l2QTpKIseMu932/QRcrbMBQk2S9Dcumy29Rx5cDe");
insert into members (name, password) values("Bob Carpenter","$2y$10$m/2Qzh9Zf.VO5x/qxHtP.uK7wCHAx3lKwWrWV07O5PSxeKo8S62RK");
insert into members (name, password) values("顾 伟德","$2y$10$iFcroZw7hFvt1oCJ0H2vp.P.51xAT23pt9rR0yp5Iwi0.J9CiS0C6");

-- insert dealers test data (assert_success)
insert into dealers (name, password) values("山田花子","$2y$10$WCVCH6Na4iHq4dR1osaMkO4HDy7lysOnkA2EL9LtZKuH0VtaWN/Da");
insert into dealers (name, password) values("小川太郎","$2y$10$QC1W1oW5Y9gGq/vi5JiIhO9KRnbEY.02brhXZJxT3kglImBGR7WHm");
insert into dealers (name, password) values("太田二郎","$2y$10$kW43pxAHhva4WwvBKPdTguTVhxiYoWE0Ks0EPjUqccIV9aWPTSlKq");
insert into dealers (name, password) values("大川翔子","$2y$10$eiEdBmcmxxDmUyFQoae5Wu/TCaOf1ASIkEsac54/cFPBwOBt04HE.");
insert into dealers (name, password) values("浅田舞佳","$2y$10$jYR0a.BXdri78CXtk/D8ReBw5ky0AwahH/9uSdQwepk.ZICmVwyfi");
insert into dealers (name, password) values("戸田シンジ","$2y$10$cAKIdfHRM2aeVra6iFF7UOZ3be2haagE.FxIqAXrexCKYx./wtNeK");
insert into dealers (name, password) values("堀川兵五郎","$2y$10$gBrFfTSaujjWV97iENUsbegwphiCotgF2M4LAMj/hAogzhut.6Wk.");
insert into dealers (name, password) values("Aran Hartl","$2y$10$B5y2PbjBY.X5jCvHKrO3eu.E8rD9T6o7MxBd05yViAvTWE/mcbnZi");
insert into dealers (name, password) values("Bob Carpenter","$2y$10$dmU4D6vNaiyxKQU2aB5uP.mtcjC7nl8uPflJwquf6yb6e2Ggn3uLq");
insert into dealers (name, password) values("顾 伟德","$2y$10$OMStwrtYXZpHBDCMXUEfveVDqbH7n.LN8CK6W0FQ/aCjlv5w1EckK");

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

-- insert images test data (assert_success)
insert into images (file, product_id) select '/images/ionr24swgnj.png', products.id from products order by rand() limit 1;
insert into images (file, product_id) select '/images/2ht45s5zdfb.png', products.id from products order by rand() limit 1;
insert into images (file, product_id) select '/images/f153haernz4.png', products.id from products order by rand() limit 1;
insert into images (file, product_id) select '/images/rq346jarger.png', products.id from products order by rand() limit 1;

-- insert coupon_targets test data (assert_success)
insert into coupon_targets (coupon_id, product_id) select coupons.id, products.id from coupons, products order by rand() limit 1;
insert into coupon_targets (coupon_id, product_id) select coupons.id, products.id from coupons, products order by rand() limit 1;
insert into coupon_targets (coupon_id, product_id) select coupons.id, products.id from coupons, products order by rand() limit 1;

-- insert owned_coupons test data (assert_success)
insert into owned_coupons (invalidate_date, member_id, coupon_id) select '2020-05-28 00:00:00', members.id, coupons.id from members, coupons order by rand() limit 1;
insert into owned_coupons (invalidate_date, member_id, coupon_id) select '2018-12-25 00:00:00', members.id, coupons.id from members, coupons order by rand() limit 1;
insert into owned_coupons (invalidate_date, member_id, coupon_id) select '2019-08-11 00:00:00', members.id, coupons.id from members, coupons order by rand() limit 1;
insert into owned_coupons (invalidate_date, member_id, coupon_id) select '2020-01-01 00:00:00', members.id, coupons.id from members, coupons order by rand() limit 1;
insert into owned_coupons (invalidate_date, member_id, coupon_id) select '2017-05-01 00:00:00', members.id, coupons.id from members, coupons order by rand() limit 1;
insert into owned_coupons (invalidate_date, used, member_id, coupon_id) select '2001-01-19 00:00:00', true, members.id, coupons.id from members, coupons order by rand() limit 1;
