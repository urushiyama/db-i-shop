-- insert members test data (assert_success)
insert into members (name, password) values("山田花子", "<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into members (name, password) values("小川太郎","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into members (name, password) values("太田二郎","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into members (name, password) values("大川翔子","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into members (name, password) values("浅田舞佳","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into members (name, password) values("戸田シンジ","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into members (name, password) values("堀川兵五郎","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into members (name, password) values("Aran Hartl","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into members (name, password) values("Bob Carpenter","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into members (name, password) values("顾 伟德","<?= password_hash('password', PASSWORD_DEFAULT) ?>");

-- insert an admin member test data (assert_success)
insert into members (name, password, admin) values("Administrator", "<?= password_hash('admin', PASSWORD_DEFAULT) ?>", true);

-- insert dealers test data (assert_success)
insert into dealers (name, password) values("山田花子","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into dealers (name, password) values("小川太郎","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into dealers (name, password) values("太田二郎","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into dealers (name, password) values("大川翔子","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into dealers (name, password) values("浅田舞佳","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into dealers (name, password) values("戸田シンジ","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into dealers (name, password) values("堀川兵五郎","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into dealers (name, password) values("Aran Hartl","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into dealers (name, password) values("Bob Carpenter","<?= password_hash('password', PASSWORD_DEFAULT) ?>");
insert into dealers (name, password) values("顾 伟德","<?= password_hash('password', PASSWORD_DEFAULT) ?>");

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
