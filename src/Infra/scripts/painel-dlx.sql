-- Usuários
CREATE TABLE dlx_reset_senha (
    reset_senha_id int not null primary key auto_increment,
    usuario_id int not null,
    data datetime not null default current_timestamp,
    hash varchar(50) not null,
    utilizado bool not null default 0,
    constraint FK_dlx_reset_senha_usuario_id foreign key (usuario_id) references dlx_usuarios (usuario_id) on delete cascade
) ENGINE=INNODB;

-- Permissões de usuários
CREATE TABLE dlx_permissoes_usuario (
    permissao_usuario_id int not null primary key auto_increment,
    alias varchar (50) not null,
    descricao varchar(150) not null,
    deletado bool not null default 0
) ENGINE=INNODB;

CREATE TABLE dlx_permissoes_x_grupos (
    grupo_usuario_id int not null,
    permissao_usuario_id int not null,
    primary key (grupo_usuario_id, permissao_usuario_id),
    constraint FK_dlx_permissoes_x_grupos_grupo_usuario_id foreign key (grupo_usuario_id)
        references dlx_grupos_usuarios (grupo_usuario_id) on delete cascade,
    constraint FK_dlx_permissoes_x_grupos_permissao_usuario_id foreign key (permissao_usuario_id)
        references dlx_permissoes_usuario (permissao_usuario_id) on delete cascade
) ENGINE=INNODB;

INSERT INTO dlx_permissoes_usuario (alias, descricao) VALUES
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

-- Envio de emails
CREATE TABLE dlx_config_smtp (
    config_smtp_id int not null primary key auto_increment,
    nome varchar(30) not null,
    servidor varchar(30) not null default 'localhost',
    porta int not null default 25,
    cripto varchar(3),
    requer_autent bool not null default 0,
    conta varchar(100),
    senha varchar(20),
    de_nome varchar(100),
    responder_para varchar(100),
    corpo_html bool not null default 0,
    deletado bool not null default 1,
    unique key (nome),
    constraint CK_dlx_config_smtp_cripto_valido check (cripto in (null, 'tls', 'ssl'))
) ENGINE=INNODB;

CREATE TABLE dlx_envio_email_log (
    envio_email_log_id int not null primary key auto_increment,
    data datetime not null default current_timestamp,
    config_smtp_id int not null,
    para text not null,
    cc text,
    cco text,
    assunto varchar(30),
    corpo longtext,
    constraint FK_dlx_envio_email_log_config_smtp_id foreign key (config_smtp_id)
        references dlx_config_smtp (config_smtp_id)
) ENGINE=INNODB;

-- Menu
CREATE TABLE dlx_menu (
    menu_id int not null primary key auto_increment,
    nome varchar(50) not null,
    deletado bool not null default 0
) ENGINE=INNODB;

CREATE TABLE dlx_menu_item (
    menu_item_id int not null primary key auto_increment,
    menu_id int not null,
    nome varchar(50) not null,
    link varchar(50) not null,
    deletado bool not null default 0
) ENGINE=INNODB;

CREATE TABLE dlx_menu_item_x_permissao (
    menu_item_id int not null,
    permissao_usuario_id int not null,
    constraint FK_dlx_menu_item_x_permissao_menu_item_id foreign key (menu_item_id) references dlx_menu_item (menu_item_id) on delete cascade,
    constraint FK_dlx_menu_item_x_permissao_permissao_usuario_id foreign key (permissao_usuario_id) references dlx_permissoes_usuario (permissao_usuario_id) on delete cascade
) ENGINE=INNODB;

-- DECLARE menu_id INT;
INSERT INTO dlx_menu (nome) VALUES ('Admin');

SET @menu_id = LAST_INSERT_ID();

INSERT INTO dlx_menu_item (menu_id, nome, link) VALUES
    (@menu_id, 'Usuários', '/painel-dlx/usuarios'),
    (@menu_id, 'Grupos de Usuários', '/painel-dlx/grupos-de-usuarios'),
    (@menu_id, 'Permissões', '/painel-dlx/permissoes'),
    (@menu_id, 'Configurações SMTP', '/painel-dlx/config-smtp');

INSERT INTO dlx_menu_item_x_permissao
    SELECT i.menu_item_id, p.permissao_usuario_id FROM dlx_menu_item i, dlx_permissoes_usuario p WHERE i.link = '/painel-dlx/usuarios' AND p.alias = 'ACESSAR_CADASTRO_USUARIOS'
    UNION
    SELECT i.menu_item_id, p.permissao_usuario_id FROM dlx_menu_item i, dlx_permissoes_usuario p WHERE i.link = '/painel-dlx/grupos-de-usuarios' AND p.alias = 'VISUALIZAR_GRUPOS_USUARIOS'
    UNION
    SELECT i.menu_item_id, p.permissao_usuario_id FROM dlx_menu_item i, dlx_permissoes_usuario p WHERE i.link = '/painel-dlx/permissoes' AND p.alias = 'CRIAR_PERMISSOES_USUARIO'
    UNION
    SELECT i.menu_item_id, p.permissao_usuario_id FROM dlx_menu_item i, dlx_permissoes_usuario p WHERE i.link = '/painel-dlx/config-smtp' AND p.alias = 'VER_CONFIGURACOES_SMTP'

-- Widget para a página inicial
CREATE TABLE dlx_widgets (
    widget_id int not null  primary key auto_increment,
    titulo varchar(30) not null,
    url_conteudo varchar(50) not null,
    ativo bool not null default 1
) ENGINE=INNODB;

INSERT INTO dlx_widgets (titulo, url_conteudo) VALUES ('Meus Dados', '/painel-dlx/resumo-usuario-logado');