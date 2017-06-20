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
  password varchar(255) not null,
  admin boolean not null default false
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
insert into members (name, password) values("山田花子", "$2y$10$Nj68UrJnqii4Hw08wuyOsepRLc.zpLlH/2cWLkg9oxCDNwe9J/T9a");
insert into members (name, password) values("小川太郎","$2y$10$W0zZXZYx42o/fM4vHWgo4u8erkPqc.v4XFLoOBcpLO7hKQS1Zz65q");
insert into members (name, password) values("太田二郎","$2y$10$zlCg.LXn0AakgIT7ov9bFOu/AYg/LRfVpbBlqAEvCsdIw06NZWiPa");
insert into members (name, password) values("大川翔子","$2y$10$Y0IWIZpFFLSkZmmX4XUeke4/S6N.3vx0XnJA0.6RmPS5nAesWzFby");
insert into members (name, password) values("浅田舞佳","$2y$10$n4yXzYjt7m0sHB0uFzZz7uqFL.toCBlAKygYNfAl8w3MGOwQp9J6q");
insert into members (name, password) values("戸田シンジ","$2y$10$NGqxk30W/vK3KdxIdDqjUOhrHQVXCTYNmm/Sg4yAutzptnU9blarG");
insert into members (name, password) values("堀川兵五郎","$2y$10$nubnv42JJHqteCdS8RBSF.dVvtW/GLLBTloXlwK29pe1Y6LcfDRkO");
insert into members (name, password) values("Aran Hartl","$2y$10$WURq9nbjC.z7FmqVwQ/SROk.qKxHiJLEy6RUJNx3tddb54xlDOHN2");
insert into members (name, password) values("Bob Carpenter","$2y$10$OvupZAhDXiO6BEwwPD/Xe.hOvevrvYAMcPpBUYFnxoJX8pOyS6gpi");
insert into members (name, password) values("顾 伟德","$2y$10$DnxLkl9nDeS93hZaINSJ0.QfmZa15lWVlDMMJQAfiqjJjoBT2Ekiy");

-- insert an admin member test data (assert_success)
insert into members (name, password, admin) values("Administrator", "$2y$10$YwN9r/xmgHNFdBAm7i2oS.qrtbeytgleNMncVscxFUmJnnQuNKyOe", true);

-- insert dealers test data (assert_success)
insert into dealers (name, password) values("山田花子","$2y$10$VBcafJzncRDQZfTlOXzd6OwekFVRgg9XiKXz1QjjdqEih4Tsvkz1W");
insert into dealers (name, password) values("小川太郎","$2y$10$zo6AWiRFmGAnB46u1lzno.Q21XbapnFeaKcS0XRVBCCIUuygTEg7C");
insert into dealers (name, password) values("太田二郎","$2y$10$Wxy3BRB9soLzwEqlLAFi2uCbRmX3gvyvub70emQMA/DEzN.IEcZ.e");
insert into dealers (name, password) values("大川翔子","$2y$10$qzEATp98i8rRH8A0zo.I5uB44orVPIAP5RgUrBswp1FLkDDDmQl4u");
insert into dealers (name, password) values("浅田舞佳","$2y$10$NzTkERyFkw23Y2.f7q7paubGb4hy/QIu0fVqPorO6Pbq8jvtspizC");
insert into dealers (name, password) values("戸田シンジ","$2y$10$y1PeWTERpCnHNLMIhf6wfOT93x1rLZLpZgDmqA3o038W2DLoDneVW");
insert into dealers (name, password) values("堀川兵五郎","$2y$10$ujOjsBq5i.rl6XshHwCRD.hTBhRUfUI02tuFDT7AtcX954/lMwM1S");
insert into dealers (name, password) values("Aran Hartl","$2y$10$O80w77XpNRBjHajsYplqLeVZebqQkxObfaS4hXuxnwngrYSwlN3Oi");
insert into dealers (name, password) values("Bob Carpenter","$2y$10$8qmjgNnI1OV7DQjrrW5PV.41vTDi1ncL2eg7Xwy9xSOjOCwIZxXKm");
insert into dealers (name, password) values("顾 伟德","$2y$10$7kdiQox5fcZD4uu76A.mTeoY5KLyrtyu98nNU6ums222LrS2nDVri");

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
