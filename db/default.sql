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
  foreign key (delivery_type_id) references delivery_types(id)
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
  purchased_units integer not null,
  member_id integer not null,
  product_id integer not null,
  foreign key (member_id) references members(id),
  foreign key (product_id) references products(id)
) default charset=utf8;

-- insert members test data (assert_success)
insert into members (name, password) values("山田花子", "$2y$10$K6UVx2./LNsWNLIRTDPr3u.BN5BaQRI7cHwHmBTeAY.CFUCVRMrgG");
insert into members (name, password) values("小川太郎","$2y$10$qs71VjJvYZFXUBCbvnSsC.kRgKCIclGrbrZ1lR8VCwCtiouInvh.S");
insert into members (name, password) values("太田二郎","$2y$10$FE7PVkzYR8YuY/tmInl02.8Ku998Aj7F/cTAp/SQYHT2hLN9KQ0m.");
insert into members (name, password) values("大川翔子","$2y$10$mk9NiDkG/5vrfrw7.OBjkOFkG9pHJ2c9pQn8WJoAGnJYYlravkHp6");
insert into members (name, password) values("浅田舞佳","$2y$10$pNsN0qK1GJGCi6vMAuw5I.x2pi4GcIiOti2gE/F6BhUBTWbqMB/Qy");
insert into members (name, password) values("戸田シンジ","$2y$10$gfyy9fyYQw6DBPzBec6Kf.rVNcYIeQ2XJGp8xJ5AD5xtgGmmYUqN2");
insert into members (name, password) values("堀川兵五郎","$2y$10$l50Inxh9tcrljnYWta4J0ONURrkboPRVJjNSNiIF48Xa3DLcisb66");
insert into members (name, password) values("Aran Hartl","$2y$10$OU0KBFqd/mFdPptmdVp3xuo5VNZjt3OPzP31/6W/IXA6XEwTK5fWK");
insert into members (name, password) values("Bob Carpenter","$2y$10$j1754wbXB6JUW1nHdkWiL.SDfyNjZhPlx2Nrz.77lTJwA4nIv6NLu");
insert into members (name, password) values("顾 伟德","$2y$10$fz.zNmeqbkYR.EwQ1pPPveL6BtdchYKdhR.2kraXHFKIBQPxdcfUC");

-- insert an admin member test data (assert_success)
insert into members (name, password, admin) values("Administrator", "$2y$10$dCkoXhzVdd/h2p.2fNMDruMbarBdMa.AlJaE3sFCHnx5X.LcNxfZu", true);

-- insert dealers test data (assert_success)
insert into dealers (name, password) values("山田花子","$2y$10$waZaOyMgcxgTSo.aI.esNe3mnH0lWdaagOYmpat5jhZsHPy8sPa9O");
insert into dealers (name, password) values("小川太郎","$2y$10$QRPAceL3TPeGBLe.OtS1KurMqchlRqI4Byy8tgKhqbHVhQ8igIkS.");
insert into dealers (name, password) values("太田二郎","$2y$10$/T/vflYtKNY62L57b6tCIOJm1D7z6nWWzKbY7XRKG8s6uGhPK/yy6");
insert into dealers (name, password) values("大川翔子","$2y$10$eryaztWCu.G4dvokxR7/pOqIJzCCgUxHS.oqRjv.GyUSkFB2u6H8a");
insert into dealers (name, password) values("浅田舞佳","$2y$10$HZjMES2xIk19pVlOQDmnT.v3szafxt8Pvx7Ivp6ANu3DyKyPG372S");
insert into dealers (name, password) values("戸田シンジ","$2y$10$hQIQ9ssw3ujS5Sv9U2QGpekRf2ig.Z/7lo9i9GMwscCGkYe.Ornm2");
insert into dealers (name, password) values("堀川兵五郎","$2y$10$ihjt5K9YDlwAT9sy/jBoeuo1ZI/hOC371xMEfo7cLaTzwC7ti00JS");
insert into dealers (name, password) values("Aran Hartl","$2y$10$Guw31KvV4d6BA5GNiWvIkODDOm5EbMHhK3xpeWmAaeeC38GNoaRFy");
insert into dealers (name, password) values("Bob Carpenter","$2y$10$pTg2z/SmtbCwIq3rjiPe3uBjjM3NSX089x/6gWzhQ8rPJwZhUtcHC");
insert into dealers (name, password) values("顾 伟德","$2y$10$J8/qW8.ik2TCxk3dWWPrguLrWSZA5CoT8tfbh0JHQ1yFCdozBGkf6");

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
