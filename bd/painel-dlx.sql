start transaction;

-- Usuários
create table if not exists dlx.Usuario (
    usuario_id int not null auto_increment primary key,
    nome varchar(100) not null,
    email varchar(200) not null,
    senha varchar(32) not null,
    deletado bool not null default 0,
    unique key (email)
) engine = innodb;

-- Grupo de Usuário
create table dlx.GrupoUsuario (
    grupo_usuario_id int not null primary key auto_increment,
    alias varchar(30) not null,
    nome varchar(100) not null,
    deletado bool not null default 0,
    unique key (alias)
) engine = innodb;

create table dlx.GrupoUsuario_x_Usuario (
    grupo_usuario_id int not null references dlx.GrupoUsuario (grupo_usuario_id) on delete cascade,
    usuario_id int not null references dlx.Usuario (usuario_id) on delete cascade,
    primary key (grupo_usuario_id, usuario_id)
) engine = innodb;

CREATE TABLE dlx.ResetSenha (
    reset_senha_id int not null primary key auto_increment,
    usuario_id int not null references dlx.Usuario (usuario_id) on delete cascade,
    data datetime not null default current_timestamp,
    hash varchar(50) not null,
    utilizado bool not null default 0
) ENGINE = INNODB;

insert into dlx.GrupoUsuario (alias, nome) values
    ('ADMIN', 'Administradores'),
    ('USUARIO', 'Usuários');

insert into dlx.Usuario (nome, email, senha) values ('Administrador', 'admin@gmail.com', '64eedda5e60fdb52fc29aa903ce9002a');
insert into dlx.GrupoUsuario_x_Usuario
    select
        g.grupo_usuario_id,
        u.usuario_id
    from
        dlx.Usuario u,
        dlx.GrupoUsuario g;

-- Permissões de usuários
CREATE TABLE dlx.PermissaoUsuario (
    permissao_usuario_id int not null primary key auto_increment,
    alias varchar (50) not null,
    descricao varchar(150) not null,
    deletado bool not null default 0,
    unique key (alias)
) ENGINE = INNODB;

CREATE TABLE dlx.PermissaoUsuario_x_GrupoUsuario (
    grupo_usuario_id int not null references dlx.GrupoUsuario (grupo_usuario_id),
    permissao_usuario_id int not null references dlx.PermissaoUsuario (permissao_usuario_id),
    primary key (grupo_usuario_id, permissao_usuario_id)
) ENGINE = INNODB;

INSERT INTO dlx.PermissaoUsuario (alias, descricao) VALUES
	-- Cadastro de usuário
    ('ACESSAR_CADASTRO_USUARIOS', 'Acessar o cadastro de usuários'),
    ('CADASTRAR_NOVO_USUARIO', 'Cadastrar um novo usuario'),
    ('EDITAR_CADASTRO_USUARIO', 'Editar cadastro de usuário'),
    ('EXCLUIR_CADASTRO_USUARIO', 'Excluir o cadastro de um usuário'),

    -- Grupos de usuários
    ('VISUALIZAR_GRUPOS_USUARIOS', 'Visualizar os grupos de usuários'),
    ('CADASTRAR_GRUPO_USUARIO', 'Cadastrar um novo grupo de usuário'),
    ('EDITAR_GRUPO_USUARIO', 'Editar as informações dos grupos de usuário'),
    ('EXCLUIR_GRUPO_USUARIO', 'Excluir um grupo de usuário'),
    ('GERENCIAR_PERMISSOES_GRUPOS', 'Gerenciar as permissões do grupo de usuário'),

    -- Permissões de usuários
    ('CRIAR_PERMISSOES_USUARIO', 'Criar permissões de usuário'),
    ('EDITAR_PERMISSOES_USUARIO', 'Editar permissões de usuário'),
    ('EXCLUIR_PERMISSOES_USUARIO', 'Excluir uma permissão de usuário'),

    -- Configurações SMTP
    ('VER_CONFIGURACOES_SMTP', 'Ver as configurações SMTP do sistema'),
    ('CRIAR_CONFIGURACAO_SMTP', 'Criar uma nova configuração SMTP'),
    ('EDITAR_CONFIGURACAO_SMTP', 'Editar configuração SMTP'),
    ('EXCLUIR_CONFIGURACAO_SMTP', 'Excluir configuração SMTP');

insert into dlx.PermissaoUsuario_x_GrupoUsuario (grupo_usuario_id, permissao_usuario_id)
    select
        g.grupo_usuario_id,
        p.permissao_usuario_id
    from
        dlx.PermissaoUsuario p,
        dlx.GrupoUsuario g
    where
        g.alias = 'ADMIN';

-- Envio de emails
CREATE TABLE dlx.ConfiguracaoSmtp (
    config_smtp_id int not null primary key auto_increment,
    nome varchar(30) not null,
    servidor varchar(30) not null default 'localhost',
    porta int not null default 25,
    cripto varchar(3) check (cripto in (null, 'tls', 'ssl')),
    requer_autent bool not null default 0,
    conta varchar(100),
    senha varchar(20),
    de_nome varchar(100),
    responder_para varchar(100),
    corpo_html bool not null default 0,
    deletado bool not null default 0,
    unique key (nome)
) ENGINE = INNODB;

# CREATE TABLE dlx_envio_email_log (
#     envio_email_log_id int not null primary key auto_increment,
#     data datetime not null default current_timestamp,
#     config_smtp_id int not null,
#     para text not null,
#     cc text,
#     cco text,
#     assunto varchar(30),
#     corpo longtext,
#     constraint FK_dlx_envio_email_log_config_smtp_id foreign key (config_smtp_id)
#         references dlx_config_smtp (config_smtp_id)
# ) ENGINE=INNODB;

-- Menu
CREATE TABLE dlx.Menu (
    menu_id int not null primary key auto_increment,
    nome varchar(50) not null,
    deletado bool not null default 0
) ENGINE = INNODB;

CREATE TABLE dlx.MenuItem (
    menu_item_id int not null primary key auto_increment,
    menu_id int not null,
    nome varchar(50) not null,
    link varchar(50) not null,
    deletado bool not null default 0
) ENGINE = INNODB;

CREATE TABLE dlx.MenuItem_x_PermissaoUsuario (
    menu_item_id int not null references dlx.MenuItem (menu_item_id) on delete cascade,
    permissao_usuario_id int not null references dlx.PermissaoUsuario (permissao_usuario_id) on delete cascade
) ENGINE = INNODB;

-- DECLARE menu_id INT;
INSERT INTO dlx.Menu (nome) VALUES ('Admin');

SET @menu_id = LAST_INSERT_ID();

INSERT INTO dlx.MenuItem (menu_id, nome, link) VALUES
    (@menu_id, 'Usuários', '/painel-dlx/usuarios'),
    (@menu_id, 'Grupos de Usuários', '/painel-dlx/grupos-de-usuarios'),
    (@menu_id, 'Permissões', '/painel-dlx/permissoes'),
    (@menu_id, 'Configurações SMTP', '/painel-dlx/config-smtp');

INSERT INTO dlx.MenuItem_x_PermissaoUsuario
    SELECT i.menu_item_id, p.permissao_usuario_id FROM dlx.MenuItem i, dlx.PermissaoUsuario p WHERE i.link = '/painel-dlx/usuarios' AND p.alias = 'ACESSAR_CADASTRO_USUARIOS'
    UNION
    SELECT i.menu_item_id, p.permissao_usuario_id FROM dlx.MenuItem i, dlx.PermissaoUsuario p WHERE i.link = '/painel-dlx/grupos-de-usuarios' AND p.alias = 'VISUALIZAR_GRUPOS_USUARIOS'
    UNION
    SELECT i.menu_item_id, p.permissao_usuario_id FROM dlx.MenuItem i, dlx.PermissaoUsuario p WHERE i.link = '/painel-dlx/permissoes' AND p.alias = 'CRIAR_PERMISSOES_USUARIO'
    UNION
    SELECT i.menu_item_id, p.permissao_usuario_id FROM dlx.MenuItem i, dlx.PermissaoUsuario p WHERE i.link = '/painel-dlx/config-smtp' AND p.alias = 'VER_CONFIGURACOES_SMTP';

-- Widget para a página inicial
CREATE TABLE dlx.Widget (
    widget_id int not null  primary key auto_increment,
    titulo varchar(30) not null,
    url_conteudo varchar(255) not null,
    ativo bool not null default 1
) ENGINE = INNODB;

INSERT INTO dlx.Widget (titulo, url_conteudo) VALUES ('Meus Dados', '/painel-dlx/resumo-usuario-logado');

-- Logs de registros
CREATE TABLE dlx.LogRegistro (
    log_registro_id bigint not null auto_increment primary key,
    tabela varchar(50) not null,
    registro_id varchar(100) not null,
    data datetime not null,
    acao char(1) not null default 'I',
    usuario_id int references dlx.Usuario (usuario_id)
) ENGINE = INNODB;

rollback;
commit;