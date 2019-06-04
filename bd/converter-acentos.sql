
start transaction;
update
	dlx_menu_item
set
	nome = replace(replace(replace(nome, 'á', 'Ã¡'), 'ç', 'Ã§'), 'õ', 'Ãµ');
rollback;
-- commit;