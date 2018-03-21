-- INSERIR ALGUNS FORMATOS DE DATA
INSERT INTO dlx_paineldlx_formatos_datas (formato_data_descr, formato_data_completo, formato_data_data, formato_data_hora) VALUES
    ('dd/mm/aaaa hh:mm', 'd/m/Y H:i', 'd/m/Y', 'H:i'),
    ('aaaa-dd-mm hh:mm:ss', 'Y-m-d H:i:s', 'Y-m-d', 'H:i:s');

-- INSERIR GRUPOS DE USUÁRIOS
INSERT INTO dlx_paineldlx_grupos_usuarios (grupo_usuario_nome, grupo_usuario_autoperm) VALUES
    ('Desenvolvedores', 1), ('Administradores', 0), ('Usuários', 0);

-- MÓDULOS
-- DESENVOLVEDOR
INSERT INTO dlx_paineldlx_modulos (modulo_nome, modulo_descr, modulo_link, modulo_exibir_menu, modulo_ordem) VALUES
    ('Desenvolvedor', NULL, 'desenvolvedor', 1, 99);

SET @ID_MOD = LAST_INSERT_ID();

INSERT INTO dlx_paineldlx_modulos (modulo_pai, modulo_nome, modulo_descr, modulo_link, modulo_exibir_menu) VALUES
    (@ID_MOD, 'Módulos', 'Gerenciar os módulos do sistema e as ações que eles executam em determinados aplicativos.', 'desenvolvedor/modulos', 1),
    (@ID_MOD, 'Temas', 'Gerenciar os temas instalados no sistema.', 'desenvolvedor/temas', 1),
    (@ID_MOD, 'Idiomas', 'Gerenciar os idiomas instalados no sistema.', 'desenvolvedor/temas', 1),
    (@ID_MOD, 'Formatos de data', 'Gerenciar os formatos de data aceitos pelo sistema.', 'desenvolvedor/formatos-de-datas', 1),
    (@ID_MOD, 'Servidores de Domínio', 'Configurar conexões com servidores de domínio (AD e LDAP) para login no Painel-DLX via domínio.', 'desenvolvedor/servidores-de-dominio', 1);

-- ADMIN
INSERT INTO dlx_paineldlx_modulos (modulo_nome, modulo_descr, modulo_link, modulo_exibir_menu, modulo_ordem) VALUES
    ('Admin', NULL, 'admin', 1, 98);

SET @ID_MOD = LAST_INSERT_ID();

INSERT INTO dlx_paineldlx_modulos (modulo_pai, modulo_nome, modulo_descr, modulo_link, modulo_exibir_menu) VALUES
    (@ID_MOD, 'Grupos de Usuários', 'Gerenciar grupos de usuários e suas permissões dentro do Painel-DLX.', 'admin/grupos-de-usuarios', 1),
    (@ID_MOD, 'Usuários', 'Gerenciar as contas de usuários do sistema.', 'admin/usuarios', 1),
    (@ID_MOD, 'Servidores SMTP', 'Configurações de envio de emails via SMTP.', 'admin/envio-de-emails', 1);
