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

create table searched_products(
  id integer primary key auto_increment,
  searched_date timestamp default current_timestamp,
  member_id integer not null,
  product_id integer not null,
  foreign key (member_id) references members(id),
  foreign key (product_id) references products(id)
);

create table purchased_products(
  id integer primary key auto_increment,
  purchased_date timestamp default current_timestamp,
  member_id integer not null,
  product_id integer not null,
  foreign key (member_id) references members(id),
  foreign key (product_id) references products(id)
);

-- insert members test data (assert_success)
insert into members (name, password) values("山田花子", "$2y$10$xKut674eXxqOvYi3MKtpLuGDRsS8j4XpqOcsbO0MEQYbZZpUSzdWC");
insert into members (name, password) values("小川太郎","$2y$10$p3CJIMZuCMM/6eb73txylOddaJbQCjh09oqEjWhfIy/h.ydjbyQp2");
insert into members (name, password) values("太田二郎","$2y$10$.8bvdq7mPUOG1AUgHs5zgOG1Be8UKxIydhea3EMg2U43GEpgcs2ey");
insert into members (name, password) values("大川翔子","$2y$10$p4NFG6gnPzEHDrsQAPiIreUq7OQKEP4Y4pn9Mn/j5yNxBbGXstSm.");
insert into members (name, password) values("浅田舞佳","$2y$10$iJ0LucY0UKT9CGT3lW42fuxwic42yHi9EgQAGYo06UC4Tu4Fwo3vq");
insert into members (name, password) values("戸田シンジ","$2y$10$FNjk/U88oNrVd9oha1fs4.HBaQIqqmZnh/PcO2tjxROTQmIApsYQm");
insert into members (name, password) values("堀川兵五郎","$2y$10$EGAWNRTwxatcXANSiCcM2OhlNxi8KmXWuB6YAlK8cQ.7tFktwF8xu");
insert into members (name, password) values("Aran Hartl","$2y$10$6zQS8/VDOjzm5ynp27PFBu1TlX2FB9aH8yZs026f8Nk/GAlfo.AOm");
insert into members (name, password) values("Bob Carpenter","$2y$10$BbND/2h6LaUsMYUsY6Jtyup5GC9.aFlIUItz/cab1kRF3nsbNR3Lm");
insert into members (name, password) values("顾 伟德","$2y$10$ukg0IbtOw3KUWiZiJaLjb.APDOdOEGdcAC8uzL/YYtL.gYhf4SrTK");

-- insert an admin member test data (assert_success)
insert into members (name, password, admin) values("Administrator", "$2y$10$tSCnq7DxTJFaNSp4ynJhJuUgJgHI9ozIvWRwyC2CZFGE1lcfFG6Se", true);

-- insert dealers test data (assert_success)
insert into dealers (name, password) values("山田花子","$2y$10$paytu8DP6MvP7jCWxKloje/wpi94WZ7vHg7dJcXQqyQfItY7AJxky");
insert into dealers (name, password) values("小川太郎","$2y$10$ulXYdu0f2BPJDSUprslk/uuLTKj4BKfjNhIbk.YDm6OoWZqUV/YiW");
insert into dealers (name, password) values("太田二郎","$2y$10$YhPs5LBIgZc24pXaWbjonelIFAEI282OoNb/1oH.AGvsJpGtqTDVy");
insert into dealers (name, password) values("大川翔子","$2y$10$noTZaCPOgXSOgFjvdUgplezZUG.16c44gKtTfTWib8StuJzlnc/Nq");
insert into dealers (name, password) values("浅田舞佳","$2y$10$lqnWcZvGQSdcj8KV4kDlwOnSgW.xK4Azowaycd5FGqnmsF4UGzlI2");
insert into dealers (name, password) values("戸田シンジ","$2y$10$iPK96UoPh56p0Dk26XqTqO5JtlyPu.oE2ZAyM2OLreA/L4Hzjj6vW");
insert into dealers (name, password) values("堀川兵五郎","$2y$10$mFshyJoTpxezpTAJoeOWc.0W7mi66.F6mhnFlT5wthvKR8HYgp4le");
insert into dealers (name, password) values("Aran Hartl","$2y$10$X9ayOT7GCUf.8cMy1kbvB.RqNAec2Cxr0l/MzWHDJIDY12qGukz3e");
insert into dealers (name, password) values("Bob Carpenter","$2y$10$NlQnFxGmVl5go2cmQ2gbEetfZTMH01RxZVPOke48vHNqkqks8qzm2");
insert into dealers (name, password) values("顾 伟德","$2y$10$KWGkosOfuDj8s4df/LOO4.2ckxUbj5/Rb1AebIRA4bfQY414iPtqO");

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

-- insert searched_products test data (assert_success)
insert into searched_products (searched_date, member_id, product_id) select ADDTIME(CONCAT_WS(' ','2014-07-01' + INTERVAL RAND() * 31 DAY, '00:00:00'), SEC_TO_TIME(FLOOR(0 + (RAND() * 86401)))), members.id, products.id from members, products order by rand() limit 10;

-- insert purchased_products test data (assert_success)
insert into purchased_products (purchased_date, member_id, product_id) select ADDTIME(CONCAT_WS(' ','2014-07-01' + INTERVAL RAND() * 31 DAY, '00:00:00'), SEC_TO_TIME(FLOOR(0 + (RAND() * 86401)))), members.id, products.id from members, products order by rand() limit 10;
