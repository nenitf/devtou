# DevToU

[![emojicom](https://img.shields.io/badge/emojicom-%F0%9F%90%9B%20%F0%9F%86%95%20%F0%9F%92%AF%20%F0%9F%91%AE%20%F0%9F%86%98%20%F0%9F%92%A4-%23fff)](https://gist.github.com/nenitf/1cf5182bff009974bf436f978eea1996#emojicom)

Script para fazer backup de posts do [dev.to](https://dev.to), pois os textos são seus e isso o site **deve a você**.

> Reimplementação da ideia do [@CoDeRgAnEsh](https://github.com/CoDeRgAnEsh) com [dev-to-posts-to-markdown](https://github.com/marketplace/actions/dev-to-posts-to-markdown)

## Instruções

> É necessário possuir php 7

1. Crie uma pasta vazia onde pretende manter o script e os posts.

2. Salve o arquivo `app/DevToU.php`.

> Para atualizar o script basta utilizar o mesmo comando

```sh
curl https://raw.githubusercontent.com/nenitf/devtou/main/app/DevToU.php -o DevToU.php
```

3. No [dev.to](https://dev.to) acesse `settings -> account -> DEV API Keys` e crie uma chave com o nome que for para ser usado no script.

4. Copie o hash da chave e salve em um novo arquivo `token`.

> Caso tenha pretensão de salvar no github, **coloque o o arquivo no .gitignore!**

5. Crie o arquivo `bot-artigos.csv`, cujo irá conter todos os artigos encontrados.

```sh
php DevToU.php
```

6. Renomeie o arquivo `bot-artigos.csv` para `artigos.csv`.
7. Preencha a primeira coluna com o nome desejado dos arquivos sem `.md` ao final.

```csv
nome-arquivo-1;46845;Titulo do Devto
nome-arquivo-2;45247;Outro titulo do Devto
```

8. Execute o código para criar/atualizar os arquivos especificados.

```sh
php index.php
```

## Testes

```sh
composer test
composer test:cover
```

## Contribuindo

Veja o [CONTRIBUTING.md](CONTRIBUTING.md)
