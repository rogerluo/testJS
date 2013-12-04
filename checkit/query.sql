use cbrtest;

#drop table ricpattern;
#create table ricpattern (pattern char(40), constraint pk_pattern primary key (pattern));
#delete from ricpattern where 1=1;
#insert into ricpattern values ('US44984WAA53=___%');
#insert into ricpattern values ('US44984WAA53=____');
#insert into ricpattern values ('.DU%');
drop procedure ps_filter;
delimiter //
create procedure ps_filter() 
begin
	declare ptn char(40) default '';
	declare cmd char(128) default '';
	declare mth int default 0;
	declare total int default 0;
	declare l_sqlstr char(128) default '';
	declare done tinyint default 0;
	declare cur cursor for select pattern from ricpattern;
	declare continue handler for not found set done = 1;
	open cur;
read_loop:loop
	fetch from cur into ptn;
	if done then leave read_loop; end if;
	set l_sqlstr=concat('select count(ricname) into @mth from rictable where ricname like ''', ptn, '''');
	set @sqlstr=l_sqlstr;
	prepare s1 from @sqlstr;
	set @arg = mnt;
	execute s1 using @arg;
	deallocate prepare s1;
	set mth = @mth;
	set total = total + mth;
end loop;
close cur;
select total;
end
//
delimiter ;