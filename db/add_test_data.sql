set @assertions = 0;
set @successes = 0;
set @errors = 0;

-- insert members test data (assert_success)
insert into members (name, password) values("山田花子","lzctaqgsruhfxjip");
insert into members (name, password) values("小川太郎","lfyxnpdaekzubj");
insert into members (name, password) values("太田二郎","avyoznlbpfeuxd");
insert into members (name, password) values("大川翔子","saegmlznufvo");
insert into members (name, password) values("浅田舞佳","jlembnwficvyhdxr");
insert into members (name, password) values("戸田シンジ","");
insert into members (name, password) values("堀川兵五郎","lzctaqgsruhfxjip");
insert into members (name, password) values("Aran Hartl","ifcnjqgzuyltwopk");
insert into members (name, password) values("Bob Carpenter","lcabyuhxfzsevmog");
insert into members (name, password) values("顾 伟德","wpybz");

-- assert_error: password is null
drop procedure if exists assert_error_password;
delimiter //
create procedure assert_error_password()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into members (name, password) values("吕 芯",null);
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'members.password should not be null!';
end//
delimiter ;
call assert_error_password();

-- assert_error: password too long
drop procedure if exists assert_error_password;
delimiter //
create procedure assert_error_password()
begin
declare exit handler for sqlstate '22001' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into members (name, password) values("吕 芯","fsnpdxklgvqzmycjthruwoaebfdwjhqgvkainsxytreomlbczunjrexkvohufymlcqsgwpazbid");
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'members.password should not be longer than its limit!';
end//
delimiter ;
call assert_error_password();

-- assert_error: name is null
drop procedure if exists assert_error_name;
delimiter //
create procedure assert_error_name()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into members (name, password) values(null, "hogehoge");
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'members.name should not be null!';
end//
delimiter ;
call assert_error_name();

-- assert_error: name is not unique
drop procedure if exists assert_error_name;
delimiter //
create procedure assert_error_name()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into members (name, password) select name, "egmlabyuhz" from members order by id limit 1;
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'members.name should be unique!';
end//
delimiter ;
call assert_error_name();

-- insert dealers test data (assert_success)
insert into dealers (name, password) values("山田花子","lzctaqgsruhfxjip");
insert into dealers (name, password) values("小川太郎","lfyxnpdaekzubj");
insert into dealers (name, password) values("太田二郎","avyoznlbpfeuxd");
insert into dealers (name, password) values("大川翔子","saegmlznufvo");
insert into dealers (name, password) values("浅田舞佳","jlembnwficvyhdxr");
insert into dealers (name, password) values("戸田シンジ","");
insert into dealers (name, password) values("堀川兵五郎","lzctaqgsruhfxjip");
insert into dealers (name, password) values("Aran Hartl","ifcnjqgzuyltwopk");
insert into dealers (name, password) values("Bob Carpenter","lcabyuhxfzsevmog");
insert into dealers (name, password) values("顾 伟德","wpybz");

-- assert_error: password is null
drop procedure if exists assert_error_password;
delimiter //
create procedure assert_error_password()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into dealers (name, password) values("吕 芯", null);
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'dealers.password should not be null!';
end//
delimiter ;
call assert_error_password();

-- assert_error: password too long
drop procedure if exists assert_error_password;
delimiter //
create procedure assert_error_password()
begin
declare exit handler for sqlstate '22001' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into dealers (name, password) values("吕 芯","fsnpdxklgvqzmycjthruwoaebfdwjhqgvkainsxytreomlbczunjrexkvohufymlcqsgwpazbid");
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'dealers.password should not be longer than its limit!';
end//
delimiter ;
call assert_error_password();

-- assert_error: name is null
drop procedure if exists assert_error_name;
delimiter //
create procedure assert_error_name()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into dealers (name, password) values(null, "hogehoge");
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'dealers.name should not be null!';
end//
delimiter ;
call assert_error_name();

-- assert_error: name is not unique
drop procedure if exists assert_error_name;
delimiter //
create procedure assert_error_name()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into dealers (name, password) select name, "egmlabyuhz" from dealers order by id limit 1;
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'dealers.name should be unique!';
end//
delimiter ;
call assert_error_name();

-- insert delivery_types test data (assert_success)
insert into delivery_types (name, charge) values("宅急便",864);
insert into delivery_types (name, charge) values("メール便",320);
insert into delivery_types (name, charge) values("UPS",3480);
insert into delivery_types (name, charge) values("翌日配送 宅急便",1230);

-- assert_error: charge has minus value
drop procedure if exists assert_error_charge;
delimiter //
create procedure assert_error_charge()
begin
declare exit handler for sqlstate '22001' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into delivery_types (name, charge) values("3000円引",-3000);
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'delivery_types.charge should be a positive number!';
end//
delimiter ;
call assert_error_charge();

-- assert_error: name is null
drop procedure if exists assert_error_name;
delimiter //
create procedure assert_error_name()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into delivery_types (name, charge) values(null, 8000);
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'delivery_types.name should not be null!';
end//
delimiter ;
call assert_error_name();

-- insert coupons test data (assert_success)
insert into coupons (term_type, term, discount_type, discount_value) values('period', '1001-01-01 00:00:00', 'yen', 800);
insert into coupons (term_type, term, discount_type, discount_value) values('period', '1000-02-01 00:00:00', 'parcent', 10);
insert into coupons (term_type, term, discount_type, discount_value) values('date', '2018-02-15 00:00:00', 'yen', 3000);
insert into coupons (term_type, term, discount_type, discount_value) values('date', '2017-11-01 12:00:00', 'parcent', 50);

-- assert_error: term_type is null
drop procedure if exists assert_error;
delimiter //
create procedure assert_error()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into coupons (term_type, term, discount_type, discount_value) values(null,'2010-07-21 00:00:00', 'yen', 300);
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'coupons.term_type should not be null!';
end//
delimiter ;
call assert_error();

-- assert_error: term_type is undefined value
drop procedure if exists assert_error;
delimiter //
create procedure assert_error()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into coupons (term_type, term, discount_type, discount_value) values('foo','2010-07-21 00:00:00', 'yen', 300);
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'coupons.term_type should be "period" or "date"!';
end//
delimiter ;
call assert_error();

-- assert_error: discount_type is undefined value
drop procedure if exists assert_error;
delimiter //
create procedure assert_error()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into coupons (term_type, term, discount_type, discount_value) values('period','2010-07-21 00:00:00', 'dollar', 300);
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'coupons.discount_type should be "yen" or "parcent"!';
end//
delimiter ;
call assert_error();

-- assert_error: discount_value has minus value
drop procedure if exists assert_error;
delimiter //
create procedure assert_error()
begin
declare exit handler for sqlstate '22001' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into coupons (term_type, term, discount_type, discount_value) values("date",'2018-05-10 00:00:00', 'yen', -1000);
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'coupons.discount_value should be a positive number!';
end//
delimiter ;
call assert_error();

-- insert products test data (assert_success)
insert into products (name, condition_type, stock, price, description, created_date, dealer_id, delivery_type_id, coupon_id) select '愛媛県産みかん1箱', 'new', 200, 1620, 'たっぷり入ってこの価格！安いのにはワケがあります！', '2016-07-20 12:34:41', dealers.id, delivery_types.id, coupons.id from dealers, delivery_types, coupons order by rand() limit 1;
insert into products (name, condition_type, stock, price, description, created_date, dealer_id, delivery_type_id, coupon_id) select '耐衝撃ポータブルHDD 3TB USB 3.0 Micro-B to Aケーブル付属', 'new', 1000, 12800, 'たっぷり3TBでこの価格！耐衝撃だから落下にも強い！', '2016-03-14 15:23:40', dealers.id, delivery_types.id, coupons.id from dealers, delivery_types, coupons order by rand() limit 1;
insert into products (name, condition_type, stock, price, description, created_date, dealer_id, delivery_type_id, coupon_id) select '最新OS搭載 11インチノートパソコン 内部レストア済', 'used', 4, 39800, '最新OS搭載でこの価格！ネットも文書作成もこれ一台で始められます！', '2016-11-05 02:00:00', dealers.id, delivery_types.id, coupons.id from dealers, delivery_types, coupons order by rand() limit 1;
insert into products (name, condition_type, stock, price, description, created_date, dealer_id, delivery_type_id, coupon_id) select '液晶保護フィルム 新しいOranePadに最適 アンチグレア', 'new', 20, 2200, '液晶保護フィルムで傷を避ける、日常の使用に通用、反射は目の疲れを除去する。', '2014-05-22 09:30:00', dealers.id, delivery_types.id, null from dealers, delivery_types order by rand() limit 1;

-- assert_error: condition_type is undefined value
drop procedure if exists assert_error;
delimiter //
create procedure assert_error()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into products (name, condition_type, stock, price, description, created_date, dealer_id, delivery_type_id, coupon_id) select '未使用品 データSIM 30days', 'mishiyou', 5, 100, '商品説明', '2011-08-26 00:36:00', dealers.id, delivery_types.id, null from dealers, delivery_types order by rand() limit 1;
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'products.condition_type should be "new" or "used"!';
end//
delimiter ;
call assert_error();

-- insert images test data (assert_success)
insert into images (file, product_id) select '/images/ionr24swgnj.png', products.id from products order by rand() limit 1;
insert into images (file, product_id) select '/images/2ht45s5zdfb.png', products.id from products order by rand() limit 1;
insert into images (file, product_id) select '/images/f153haernz4.png', products.id from products order by rand() limit 1;
insert into images (file, product_id) select '/images/rq346jarger.png', products.id from products order by rand() limit 1;

-- assert_error: file is null
drop procedure if exists assert_error;
delimiter //
create procedure assert_error()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into images (file, product_id) select null, products.id from products order by rand() limit 1;
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'images.file should not be null!';
end//
delimiter ;
call assert_error();

-- assert_error: file is not unique
drop procedure if exists assert_error;
delimiter //
create procedure assert_error()
begin
declare exit handler for sqlstate '23000' begin set @successes = @successes + 1; rollback; end;
set @assertions = @assertions + 1;
  start transaction;
  insert into images (file, product_id) select images.file, products.id from images, products order by images.id, rand() limit 1;
  rollback;
  set @errors = @errors + 1;
  signal sqlstate '45000' set message_text = 'dealers.name should be unique!';
end//
delimiter ;
call assert_error();

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

select @assertions, @successes, @errors;
