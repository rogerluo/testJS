use checkit;
show tables;
select * from component;
select * from host;
select * from region;

select c.name as Product, r.Name as Region, h.Name as ServerName, h.IP, h.User, h.Password, h.CreateDate, h.UPdatedate
from host h
join component c
on h.componentid = c.id
join region r
on h.regionid = r.id