-- Usuários
CREATE TABLE dlx_reset_senha (
    reset_senha_id int not null primary key auto_increment,
    usuario_id int not null,
    data datetime not null default current_timestamp,
    hash varchar(50) not null,
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
        references dlx_config_smtp (config_email_id)
) ENGINE=INNODB;