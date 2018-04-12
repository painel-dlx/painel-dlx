-- CRIAR ESTRUTURA DE LOGS
CREATE TABLE dlx_paineldlx_registros_logs(
    log_registro_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    log_registro_tabela VARCHAR(50) NOT NULL,
    log_registro_regpk VARCHAR(30) NOT NULL,
    log_registro_acao CHAR(1) DEFAULT 'A' NOT NULL,
    log_registro_data DATETIME NOT NULL,
    log_registro_usuario INT,
    log_registro_nome VARCHAR(50),
    log_registro_ip VARCHAR(15) NOT NULL,
    log_registro_agente VARCHAR(200) NOT NULL
) ENGINE = INNODB;


CREATE TABLE dlx_paineldlx_emails_logs (
    log_email_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    log_email_classe VARCHAR(80),
    log_email_assunto VARCHAR(100) NOT NULL,
    log_email_para LONGTEXT NOT NULL,
    log_email_copia LONGTEXT,
    log_email_copia_oculta LONGTEXT,
    log_email_corpo LONGTEXT,
    log_email_status LONGTEXT
) ENGINE=INNODB;


-- CRIAR ESTRUTURA DE IDIOMAS
CREATE TABLE dlx_paineldlx_idiomas (
    idioma_sigla VARCHAR(5) NOT NULL PRIMARY KEY,
    idioma_nome VARCHAR(50) NOT NULL,
    idioma_padrao BOOL NOT NULL DEFAULT 0,
    idioma_publicar BOOL NOT NULL DEFAULT 1,
    idioma_delete BOOL NOT NULL DEFAULT 0,
    UNIQUE KEY(idioma_sigla)
) ENGINE = INNODB;


-- CRIAR ESTRUTURA DE FORMATOS DE DATAS
CREATE TABLE dlx_paineldlx_formatos_datas (
    formato_data_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    formato_data_descr VARCHAR(20) NOT NULL,
    formato_data_completo VARCHAR(20) NOT NULL,
    formato_data_data VARCHAR(10) NOT NULL,
    formato_data_hora VARCHAR(10) NOT NULL,
    formato_data_padrao BOOL NOT NULL DEFAULT 0,
    formato_data_publicar BOOL DEFAULT 1 NOT NULL,
    formato_data_delete BOOL DEFAULT 0 NOT NULL
) ENGINE = INNODB;


-- CRIAR ESTRUTURA DE SERVIDORES DE DOMÍNIO
CREATE TABLE dlx_paineldlx_servidores_dominio (
    servidor_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    servidor_dominio VARCHAR(30) NOT NULL,
    servidor_host VARCHAR(100) NOT NULL,
    servidor_porta INT DEFAULT '389' NOT NULL,
    servidor_ativo BOOL NOT NULL DEFAULT 1,
    servidor_delete BOOL DEFAULT 0 NOT NULL
) ENGINE = INNODB;


-- CRIAR ESTRUTURA DE GRUPOS DE USUÁRIOS
CREATE TABLE dlx_paineldlx_grupos_usuarios (
    grupo_usuario_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    grupo_usuario_nome VARCHAR(50) NOT NULL,
    grupo_usuario_padrao BOOL NOT NULL DEFAULT 0,
    grupo_usuario_autoperm BOOL NOT NULL DEFAULT 0,
    grupo_usuario_publicar BOOL NOT NULL DEFAULT 1,
    grupo_usuario_delete BOOL NOT NULL DEFAULT 0
) ENGINE = INNODB;


-- CRIAR CADASTRO DE USUÁRIOS
CREATE TABLE dlx_paineldlx_usuarios (
    usuario_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario_grupo INT NOT NULL,
    usuario_nome VARCHAR(255) NOT NULL,
    usuario_email VARCHAR(255) NOT NULL,
    usuario_login VARCHAR(255) NOT NULL,
    usuario_senha VARCHAR(32),
    usuario_pref_idioma VARCHAR(5) NOT NULL DEFAULT 'br',
    usuario_tema VARCHAR(50) NOT NULL DEFAULT 'padrao',
    usuario_formato_data INT NOT NULL,
    usuario_bloqueado BOOL NOT NULL DEFAULT 0,
    usuario_reset_senha BOOL NOT NULL DEFAULT 0,
    usuario_delete BOOL NOT NULL DEFAULT 0,
    UNIQUE KEY(usuario_email),
    UNIQUE KEY(usuario_login),
    CONSTRAINT FK_usuario_grupo FOREIGN KEY(usuario_grupo) REFERENCES dlx_paineldlx_grupos_usuarios (grupo_usuario_id),
    CONSTRAINT FK_usuario_idioma FOREIGN KEY(usuario_idioma) REFERENCES dlx_paineldlx_idiomas (idioma_id),
    CONSTRAINT FK_usuario_formato_data FOREIGN KEY(usuario_formato_data) REFERENCES dlx_paineldlx_formatos_datas (formato_data_id)
) ENGINE = INNODB;


-- CRIAR ESTRUTURA DE MÓDULOS
CREATE TABLE dlx_paineldlx_modulos (
    modulo_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    modulo_pai INT,
    modulo_aplicativo VARCHAR(50) NOT NULL DEFAULT 'painel-dlx',
    modulo_nome VARCHAR(100) NOT NULL,
    modulo_descr TEXT,
    modulo_exibir_menu BOOL NOT NULL DEFAULT 1,
    modulo_link VARCHAR(200),
    modulo_ordem INT NOT NULL DEFAULT 0,
    modulo_publicar BOOL NOT NULL DEFAULT 1,
    modulo_delete BOOL NOT NULL DEFAULT 0,
    CONSTRAINT FK_modulo_pai FOREIGN KEY (modulo_pai) REFERENCES dlx_paineldlx_modulos (modulo_id) ON DELETE SET NULL
) ENGINE = INNODB;


-- PERMISSÕES
CREATE TABLE dlx_paineldlx_modulos_acoes (
    acao_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    acao_modulo INT NOT NULL,
    acao_descr VARCHAR(100) NOT NULL,
    acao_classe VARCHAR(100) NOT NULL,
    acao_metodos TEXT NOT NULL,
    acao_delete BOOL NOT NULL DEFAULT 0,
    CONSTRAINT FK_acao_modulo FOREIGN KEY (acao_modulo) REFERENCES dlx_paineldlx_modulos (modulo_id) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE dlx_paineldlx_permissoes (
    permissao_acao INT NOT NULL,
    permissao_grupo INT NOT NULL,
    CONSTRAINT FK_permissao_acao FOREIGN KEY (permissao_acao) REFERENCES dlx_paineldlx_modulos_acoes (acao_id) ON DELETE CASCADE,
    CONSTRAINT FK_permissao_grupo FOREIGN KEY (permissao_grupo) REFERENCES dlx_paineldlx_grupos_usuarios (grupo_usuario_id) ON DELETE CASCADE
) ENGINE = INNODB;


-- RECUPERAÇÕES DE SENHAS
CREATE TABLE dlx_paineldlx_usuarios_recuperacoes (
    recuperacao_usuario INT NOT NULL,
    recuperacao_hash VARCHAR(32) NOT NULL,
    PRIMARY KEY (recuperacao_usuario),
    UNIQUE KEY (recuperacao_hash),
    CONSTRAINT FK_recuperacao_usuario FOREIGN KEY (recuperacao_usuario) REFERENCES dlx_paineldlx_usuarios (usuario_id) ON DELETE CASCADE
) ENGINE=INNODB;


-- CONFIGURAÇÕES DO SISTEMA
CREATE TABLE dlx_paineldlx_configuracoes (
    config_email_servidor VARCHAR(100) NOT NULL DEFAULT 'localhost',
    config_email_porta INT NOT NULL DEFAULT 25,
    config_email_autent BOOL NOT NULL DEFAULT 0,
    config_email_cripto VARCHAR(3),
    config_email_conta VARCHAR(200),
    config_email_senha VARCHAR(100),
    config_email_responder_para VARCHAR(200),
    config_email_de_nome VARCHAR(200),
    config_email_html BOOL NOT NULL DEFAULT 0,
    CONSTRAINT CK_config_email_cripto CHECK (config_email_cripto IN (NULL, 'tsl', 'ssl'))
) ENGINE = INNODB;

INSERT INTO dlx_paineldlx_configuracoes (config_email_servidor) VALUES ('localhost');
