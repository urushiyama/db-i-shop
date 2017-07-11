use DBs1511427;

drop table if exists searched_products;
drop table if exists purchased_products;
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
insert into members (name, password) values("山田花子", "$2y$10$rjkauaCr7GR3F8J8Zzu2/uvq/CmEqgLa0dwLg7mc9GTAs5CLMEXr.");
insert into members (name, password) values("小川太郎","$2y$10$5rEgDT/356C7vzQ/6UwSEOS/kcHG1GPN3Y4Cr9xi8IAONCVwJBwzS");
insert into members (name, password) values("太田二郎","$2y$10$ZhjiR0Ljn2pTr/8YFJf4AueBT2.xbyxdTLRGEFaha18pKZzGw6xHS");
insert into members (name, password) values("大川翔子","$2y$10$vs3WTZqltzuGDPaI8gv1tuOTMTukBDwx7UhB78cCux5F09PvsN7BW");
insert into members (name, password) values("浅田舞佳","$2y$10$vttfjEC/WfKP7oipt4G7QejeZvgdXZcpxcb7EudohFPMTXhDpu4YW");
insert into members (name, password) values("戸田シンジ","$2y$10$2V11v/.6DvKY7t32I93iveHKvuvmju.nxonlYll1EqX7NmRRjNI.G");
insert into members (name, password) values("堀川兵五郎","$2y$10$mXahkC42bb4VOQraZtYbMu5VLDZbdl2dA7yfQ1evxqN55NJNAyZ4W");
insert into members (name, password) values("Aran Hartl","$2y$10$qM.Fr3lXf8Za9nw2h49yL.5lBQ7Ll4b8uVPJMpvmc/lCCtfPm4gZm");
insert into members (name, password) values("Bob Carpenter","$2y$10$FLso1RuG34az8OR8iNcJzOz8BFK1bbXy9JF41Wk1FVpNoVcwz9EmC");
insert into members (name, password) values("顾 伟德","$2y$10$//ullTmhIzNoz/5Bq55WHOr/Yw/xCiQJEy3dMYW1ChquhTXlcmoZ.");

-- insert an admin member test data (assert_success)
insert into members (name, password, admin) values("Administrator", "$2y$10$8v2CW7gICJChujycCT3vouBG4z0hxf9ONm62hbcXvNczYarTet9ye", true);

-- insert dealers test data (assert_success)
insert into dealers (name, password) values("山田花子","$2y$10$rdOGnBBl8azg6ufXz65yc.fkGxBV6gArbV6l1QXUEUDqm2/gnCCaq");
insert into dealers (name, password) values("小川太郎","$2y$10$MRJ49uZ1U97C3NpbVT5fFOaXm1U1R28JmFrBU9fnSfeebpbYf/vEO");
insert into dealers (name, password) values("太田二郎","$2y$10$wpBpLd3r2ZeWZCs.ygtPT.Ne0lETfosa5qn/eqGncC5CAEp1dcMv6");
insert into dealers (name, password) values("大川翔子","$2y$10$dL7FR8XI64/Ftz/55aBveOhKcCJGBAX3ho2M/85JJ56QpO/lvyYfq");
insert into dealers (name, password) values("浅田舞佳","$2y$10$OHMKDZA/Q2Ye.X/GZS7cUe6YEACUJ6R2pAI1mnMpt1q9tqly.36mm");
insert into dealers (name, password) values("戸田シンジ","$2y$10$AlJi06aQP4bhDdYgLiF7w.YCBK80BVuXy6XZ/pU/EvnbJW9WXSduu");
insert into dealers (name, password) values("堀川兵五郎","$2y$10$wbjlZBx/c60w73jcV5T5oubJg6nwJJBfGAwwBv8tB/jILn5c8yclS");
insert into dealers (name, password) values("Aran Hartl","$2y$10$izDXmqG4AWXEL0R72inPEOPCSGfeFe2FwCSbvyEHhqaxFdrewetDm");
insert into dealers (name, password) values("Bob Carpenter","$2y$10$BArN4ZoRLPj0.spfvuNRc.rbUfwPL5vhoDyzaLNp7r6qnsj1nPA92");
insert into dealers (name, password) values("顾 伟德","$2y$10$L9nlBzkIdNY53fqNKFStr.gilVoQHYummh9NGwP.3QpFAQgcX6Poe");

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
insert into purchased_products (purchased_date, member_id, product_id, purchased_units) select ADDTIME(CONCAT_WS(' ','2014-07-01' + INTERVAL RAND() * 31 DAY, '00:00:00'), SEC_TO_TIME(FLOOR(0 + (RAND() * 86401)))), members.id, products.id, rand()*100 from members, products order by rand() limit 10;
