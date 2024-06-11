## Teste do sistema hospedado
Esse sistema pode ser acessado online através da url: http://davimatos.tech 
Ao entrar no site, procure na parte superior um botão chamado "Testar"

## Instalação/Configuração do Ambiente
*após clonar o repositório, rode o seguinte comando dentro do diretorio market-system
npm install @vue/cli-service --save-dev

*após a instalação, rode: npm run serve - Isso irá startar a aplicação frontend

*para startar o backend, abra um novo terminar, vá para pasta backend que se encontra dentro do diretorio market-system e rode o comando
php -S localhost:8080

*Após isso basta entrar no navegador e digitar a url localhost:8081.
## Configuração do Banco de Dados
*Coloque em seu .env
DB_CONNECTION=mysql
DB_PORT=3306
DB_HOST=localhost
DB_NAME=market_on
DB_USER=root
DB_PASS=1234

O dump do banco encontra-se na raiz do projeto - backup.sql

## Utilização do sistema 
Você pode testar a API utilizando ferramentas tipo Postman para fazer requisições para os endpoints. 
Para testar requisições no ambiente hospedado, basta substituir localhost:8080 por davimatos.tech/product/view (por exemplo)
## Endpoints
Adicionar Tipo de Produto

URL: http://localhost:8080/product-type/add
Method: POST
Headers:
Content-Type: application/json
Body:
json
Copiar código
{
  "type_name": "Eletrônicos",
  "tax_rate": 15.5
}
Response:
json
Copiar código
{
  "status": "success",
  "message": "Tipo de produto criado com sucesso",
  "data": {
    "id": 4,
    "type_name": "Eletrônicos",
    "tax_rate": 15.5,
    "created_at": null,
    "deleted_at": null
  }
}

Editar Tipo de Produto

URL: http://localhost:8080/product-type/edit?id=2
Method: PUT
Headers:
Content-Type: application/json
Body:
json
Copiar código
{
  "type_name": "Eletrônicosssss",
  "tax_rate": 15.5
}
Response:
json
Copiar código
{
  "status": "success",
  "message": "Tipo de produto atualizado com sucesso",
  "data": {
    "id": 4,
    "type_name": "Eletrônicosssss",
    "tax_rate": 15.5,
    "created_at": null,
    "deleted_at": null
  }
}

Adicionar Produto
URL: POST /product/add
Body:
json
Copiar código
{
  "name": "Arroz",
  "price": 20.11,
  "stock": 15,
  "type_id": 6
}
Visualizar Produtos
URL: GET /product/view
Editar Produto
URL: PUT /product/edit?id=4
Body:
json
Copiar código
{
  "name": "Arroz",
  "price": 20.11,
  "stock": 15,
  "type_id": 6
}
Deletar Produto
URL: DELETE /product/delete?id=8


Esses são alguns endpoints para testar a API. 

## Testes Unitários

Este projeto inclui uma suíte de testes unitários para validar a funcionalidade dos modelos e serviços. Segue como executar os testes e uma breve descrição de cada teste.
- Para rodar os testes, navegue até a pasta do backend e execute o comando de testes do PHPUnit:
cd market-system/backend./vendor/bin/phpunit

## Descrição dos Testes
ProductServiceTest

testCreateProductTypeSuccess: Testa a criação de um novo tipo de produto e verifica se o tipo é criado com os dados corretos.
testCreateProductSuccess: Testa a criação de um novo produto e verifica se o produto é criado com os dados corretos.

SaleServiceTest

testCreateSaleSuccess: Testa a criação de uma venda com múltiplos itens e verifica se a venda é registrada corretamente com todos os itens e cálculos de impostos.

## Build

Destaca-se por fim que o build do vuejs encontra-se na raiz do projeto dentro da pasta build

