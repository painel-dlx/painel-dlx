-- DESABILITAR A VERIFICAÇÃO DE CONSTRAINTS
SET FOREIGN_KEY_CHECKS=0;

-- EXCLUIR A FK _usuarios => _idiomas
ALTER TABLE dlx_paineldlx_usuarios DROP FOREIGN KEY FK_usuario_idioma;

-- AJUSTAR A ESTRUTURA DA TABELA DE IDIOMAS
ALTER TABLE dlx_paineldlx_idiomas DROP idioma_id;
ALTER TABLE dlx_paineldlx_idiomas ADD PRIMARY KEY (idioma_sigla);

-- AJUSTAR A ESTRUTURA DA TABELA DE USUÁRIOS
ALTER TABLE dlx_paineldlx_usuarios CHANGE usuario_idioma usuario_pref_idioma VARCHAR(5) NOT NULL DEFAULT 'br';

-- ATUALIZAR AS INFORMAÇÕES DA TABELA DE USUÁRIOS
UPDATE dlx_paineldlx_usuarios SET usuario_pref_idioma = 'br';

-- REATIVAR A FK _usuarios => _idiomas
ALTER TABLE dlx_paineldlx_usuarios ADD CONSTRAINT FK_usuario_pref_idioma FOREIGN KEY (usuario_pref_idioma) REFERENCES dlx_paineldlx_idiomas (idioma_sigla);

-- REABILITAR A VERIFICAÇÃO DE CONSTRAINTS
SET FOREIGN_KEY_CHECKS=1;