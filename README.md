# Painel DLX
Painel para adiministração de sites e base para sistemas com base no Framework DLX.

## Configuração
O arquivo de configuração deve ser nomeado com o nome do ambiente a ser carregado e deve retornar um array com as opções
necessárias.

### Tipos de ambiente
O **Painel DLX** entende 3 tipos de ambiente: desenvolvimento (dev), homologação (homol) e produção (prod).

#### Definindo um tipo de ambiente
Para facilitar a configuração, a classe `Configure` possui 3 constantes.

```php
<?php

class Configure
{
    const PRODUCAO = 'prod';
    const HOMOLOGACAO = 'homol';
    const DEV = 'dev';    
}
```

Para definir uma determinada configuração como um tipo específico de ambiente, basta definir o valor correspondente na
chave 'tipo-ambiente' da configuração.

```php
<?php

return [
    'tipo-configuracao' => \DLX\Core\Configure::DEV  
];
```
