# Avança Café

> O projeto usa Artisan.


##### Qualquer dúvida ou problema: chamar o Henrique!

> Caso ja tenha o lumen, composer e o php instalado vá para "Rodando as Migrations", caso contrário, mais abaixo há um guia prático de instalação.

### Instalando as dependências
>esse processo pode demorar

``` composer install```

### Rodando as migrations
###### Verifique na pasta BACK existe o arquivo .env

###### Se não existir crie um e copie os dados do arquivo que está nessa pasta chamado .env.example e troque os valores como diz o arquivo.

###### Crie a base manualmente com o nome que foi colocado no arquivo .env
```create database procafe;```

###### Entre na pasta "BACK" e no console execute a seguinte linha:
```php artisan migrate```


### Caso não tenha o PHP ou o Composer instalado na maquina (Windows)

##### Abra o powershell como administrador e execute:
```Get-ExecutionPolicy```

##### se retornar 'Restricted' Execute:
```Set-ExecutionPolicy Bypass -Scope Process```

##### Depois disso execute o seguinte comando:
> Isso irá instalar o Chocolatey, gerenciador de pacote para windows

```Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://chocolatey.org/install.ps1'))```

##### Após concluir, feche o powershell e abra novamente como administrador e execute:
>Isso irá instalar o composer e o php7

```choco install composer```

##### Após concluir, feche o powershell e abra novamente.
>
```php -v``` retorna a versao do php instalada
```composer -V``` retorna a versao do composer instalada

### Erros Comuns
##### could not find driver  
> Será preciso habilitar o driver do mysql no php.ini
###### Abra o powershell em modo administrador e execute:
```php.ini```
###### Após abrir o arquivo procure por 'extension=pdo_mysql' (sem as aspas), provavelmente ele estará assim: ';extension=pdo_mysql', neste caso é só retirar o ponto e virgula ';' antes e salvar.

###### Caso o problema continue, abra novamente o powershell em modo administrador e execute:
```php -i | find /i "Configuration File```
###### Ele retornará um caminho, que você terá de copiar e colar no explorador de arquivos e realizar a mesma alteração feita anteriormente.

### Mais informações:
>  CHOCOLATEY
    https://chocolatey.org/

>  COMPOSER (utilizado para baixar as dependencias)
    https://getcomposer.org/

>  LUMEN (framework usado)
    https://lumen.laravel.com/
    
>  LARAVEL (O lumen utiliza)
    https://laravel.com/
  
>  ARTISAN
    https://laravel.com/docs/7.x/migrations
  

  
