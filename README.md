# U-Get Backend API

## Códigos de retorno

Qualquer endpoint pode retornar os seguintes códigos de erro no Header de Status do HTTP:

|           HTTP Code           | Description                                                                        |
|:-----------------------------:|------------------------------------------------------------------------------------|
| 20x (OK, Created, No Content) | Sua requisição foi processada com sucesso.                                         |
|        400 Bad Request        | Algo está errado na sua requisição (JSON inválido, campo faltando ou inválido etc) |
|        401 Unauthorized       | O token de sessão/usuário não foi informado e é obrigatório para este endpoint     |
|   500 Internal Server Error   | Algo está errado no servidor :-(                                                   |

## Requisição Base

Todas as requisições devem enviar o header `auth`. Ele apenas não é obrigatório no endpoint de `/social` (onde o mesmo é gerado no response).

O response enviado pelo backend pode incluir um objeto de `error` com um atributo `code`, identificando qual é motivo do request ter falhado. Em caso de sucesso, o objeto será retornado diretamente no corpo do request.

* Response HTTP (**application/json**):

```javascript
{
    /* em caso de erro */
    "error": {
        "code": <codigo de erro>
        "reason": "<string descritiva opcional do erro>"
    }
}
```

## Endpoints

### Cadastro

* Descrição

Realiza o login utilizando uma rede social. Retornará um token de sessão que será utilizado em todos os próximos requests do app. Este é endpoint não exige autenticação.

* Endpoint
```
POST /login
```

* Params:
```javascript
{
    "push_token": "<token de push notification>",
    "platform": "ios|android",
    "type": "facebook|google",
    "id": "<id do usuário na rede social>",
    "token": "<token da rede social>"
}
```

* Result:
```javascript
{
    "auth": "<token de sessao>"
}
```

* Códigos de erro 

**10** : Token ou ID de rede social inválido

### Customer

* Descrição

Realiza o cadastro ou atualização do customer. Caso já exista um customer para esta sessão, apenas os atributos enviados serão atualizados. Nenhum atributo é obrigatório.

* Endpoint
```
POST /customer
```

* Params:
```javascript
{
	"customer": {
        "name": "<nome>",
        "phone": "<phone>",
        "birth": "YYYY-MM-DD",
        "id": {
            "type": "RG|PASSPORT|CNH",
            "number": "123"
        }
        "address": {
          "street": "<street>",
          "number": "<number>",
          "complement": "<complement>",
          "neighborhood": "<neighborhood>",
          "city": "<city>",
          "country": "<country>",
          "zipcode": "<zipcode>",
        },
        "billing": {
	        "name": "<nome como está no cartão>",
            "number": "<número do cartão>",
            "cvv": "<código de segurança>",
            "expiration": "<MM/YY>"
        }
    }
}
```

### Cadastrar foto

* Descrição

Realiza o cadastro da foto do cliente na Fullface.

* Endpoint
```
POST /customer/picture
```

* Params:
```javascript
{
	"pictures": ["base64", "base64", "base64"]
}
```

* Result:

Este retorno não será encapsulado pelo response padrão, ou seja, seremos apenas um proxy para a API da Fullface. Porém, o retorno da Fullface virá no body, por exemplo:

```javascript
{ 
    "status_code": 453, 
    "body": {"Message":"Não foi possível criar ICAO com as imagens enviadas."}
}
```

Observar o documento disponibilizado na seção "Cadastro de Usuário" e todos os códigos de erro que podem ser retornados.

### Cadastrar foto de documento

* Descrição

Realiza o cadastro do documento do cliente. O backend analisa o documento detectando o tipo (PASSPORT, RG, CNH), o número e a data de nascimento.

* Endpoint
```
POST /customer/id
```

* Params:
```javascript
{
	"picture": "base64"
}
```

* Result:

201 - Documento cadastrado com sucesso

### Verificar autenticidade de foto (autenticação)

* Descrição

Verifica a autenticidade de um cliente pré-cadastrado utilizando a API da Fullface. Este endpoint não exige autenticação.

* Endpoint
```
POST /customer/check_picture
```

* Params:
```javascript
{
	"pictures": ["base64", "base64", "base64"]
}
```

* Result:

Este retorno não será encapsulado pelo response padrão, ou seja, seremos apenas um proxy para a API da Fullface. Porém, o retorno da Fullface virá no body, por exemplo:

```javascript
{ 
    "status_code": 200, 
    "body": {"id_usuario":112,"similaridade":"10","chaves":[{"chave":"id","valor":"1"}],"ativo":true} 
}
```

Observar o documento disponibilizado na seção "Autenticação de usuário" e todos os códigos de erro que podem ser retornados.

### Status do Customer

* Descrição

Checa se o customer estará preparado para transacionar ou não, isto é, seu cadastro foi aceito e verificado.

* Endpoint
```
POST /customer/status
```

* Result:

```javascript
{ 
    "status": "AUTHORIZED|IN_ANALISYS|DENIED"
}
```

### Comprar

* Descrição

Endpoint utilizado pela geladeira, quando o processamento for finalizado, indicando quais itens foram consumidos. É enviado um push notification para o device (com o `BUY_ID`), que deverá utilizar o endpoint `GET /buy/:ID` para obter os detalhes da compra.

Futuramente este endpoint se comunicará com alguma empresa de pagamentos e realizará a transação.

* Endpoint
```
POST /buy
```

* Params:
```javascript
{
	"machine": "<ID da máquina>",
	"items": [
    	{ 
        	"code":"<code>", 
            "qtt": INTEGER,
        },
        {...}
	],
    "videos": ["link1", "link2"]
}
```

### Ajuste de compra / disputa

* Descrição

Se o usuário encontrar alguma inconsistência na transação, ele pode iniciar uma disputa enviando os itens que consumiu. Será enviado um e-mail para `tirateima@uget.express`, que verificará a transação.

* Endpoint
```
POST /buy/:ID/contest
```

* Params:
```javascript
{
	"items": [
    	{ 
        	"code": "<code>", 
            "qtt": INTEGER,
        },
        {...}
	],
}
```

### Detalhes da compra

* Descrição

Deve ser utilizado para obter detalhes de uma compra já finalizada, para mostrar um recibo no app.

* Endpoint
```
GET /buy/:ID
```

* Result

```
{
	"machine": "endereço da máquina",
    "cc_last_4": "últimos quatro dígitos",
	"items": [
    	{ 
        	"name":"nome do produto",
            "desc":"descrição do produto (opcional)",
            "quantity": INTEGER,
            "value":"valor por item, ex.: 4.50"
        },
        {...}
	]
    "total": "valor total da compra, por exemplo: 99.50"
}
```

### Finaliza transação 

* Descrição

Este endpoint é utilizado apenas para alertar que a geladeira foi fechada e o processamento dos itens que foram consumidos está ocorrendo (de 5 a 120 segundos). O backend deve enviar um push notification para o device, que deve mostrar uma barra de progresso.

* Endpoint
```
POST /finish
```

### Envia comentário/sugestão

* Descrição

Este endpoint pode ser utilizado por qualquer usuário logado para enviar sugestões ou comentários para o Uget.

* Endpoint
```
POST /comment
```

* Params:
```javascript
{	
    "subject": "", 
    "body": ""
}
```