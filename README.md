## Como configurar o projecto

### Download do projecto

<ul>
<li>Copiar o link: https://github.com/eugeniocachiombo/api_test1.git </li>
<li>Abrir o terminar ou cmd</li>
<li>Selecionar uma pasta apartir do terminal</li>
    <ul type="none">
    <li>Exemplo: cd C:\Meus_projectos</li>
    </ul>
<li>Executar o comando <b>"git clone <link copiado>"</b> </li>
    <ul type="none">
    <li>Criará uma cópia do projecto na máquina local</li>
    </ul>
<li>Executar o comando <b>"cd api_test1"</b></li>
    <ul type="none">
    <li>Irá selecionar a pasta principal do projecto</li>
    </ul>
<li>Executar o comando <b>"composer install"</b> ou <b>"composer update"</b></li>
    <ul type="none">
    <li>Este comando vai ajudar a importar todas as dependências necessárias para rodar o projecto</li>
    </ul>
<li>Dentro da pasta api_test1, clonar o arquivo <b>".env example"</b></li>
<li>Renomear o arquivo <b>".env example"</b> para simplesmente <b>".env"</b></li>
<li>Executar o comando <b>"php artisan key:generate"</b></li>
</ul>

### Como rodar migrations

<p>
Com o <b>cmd</b> ou <b>terminal</b> aberto, selecionar o caminho do diretório do projecto api_test1 e executar o comando <b>"php artisan migrate"</b>.
</p>

<p>
Se existir uma base de dados com o nome apitest1, todas informações serão importadas. Caso não existir aparecerá a seguinte questão:

<strong>
    WARN  The database 'apitest1' does not exist on the 'mysql' connection.  

  Would you like to create it? (yes/no) [yes]
</strong> 

Escrever simplesmente a palavra <b>"yes"</b>, que a base de dados será criada automáticamente e todas migrations serão importadas 
</p>

#### Nota:
<p>
Para a execução deste projecto, após a migration ser executada, é recomendado executar o comando <b>"php artisan db:seed"</b>  para criar falsos utilizadores para facilitar na criação de tarefas.
</p>

### Como executar o servidor local

<p>
Com o <b>cmd</b> ou <b>terminal</b> aberto, selecionar o caminho do diretório do projecto api_test1 e executar o comando <b>"php artisan serve"</b>.
</p>

<p>
Após a execução deste comando será emitido um endereço: <b>127.0.0.1:8000</b> 
</p>

<p>
Com o aparecimento deste endereço no terminal, o projecto está em execução. 
</p>