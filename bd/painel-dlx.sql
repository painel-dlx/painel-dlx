-- Permissões de usuários
CREATE TABLE dlx_permissoes_usuario (
    permissao_usuario_id int not null primary key auto_increment,
    alias varchar (50) not null,
    descricao varchar(150) not null,
    deletado bool not null default 0
) ENGINE=INNDOB;

CREATE TABLE dlx_permissoes_x_grupos (
    grupo_usuario_id int not null,
    permissao_usuario_id int not null,
    primary key (grupo_usuario_id, permissao_usuario_id),
    constraint FK_dlx_permissoes_x_grupos_grupo_usuario_id foreign key (grupo_usuario_id)
        references dlx_grupos_usuarios (grupo_usuario_id) on delete cascade
    constraint FK_dlx_permissoes_x_grupos_permissao_usuario_id foreign key (permissao_usuario_id)
        references dlx_permissoes_usuario (permissao_usuario_id) on delete cascade
)