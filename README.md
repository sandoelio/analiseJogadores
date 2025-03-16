<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


Passo a passo como hospedar no raillway

1 - hospedar no git e upar atraves dele
2 - Coloque o projeto no laragon, no larago vai precisar rodar o composer update se for rodar local mudar o .env para local e se for rodar no raillway mudar o .env para production

3 - mudar o env. 
    * APP_ENV=production

    * APP_KEY=base64:7xiLvEIhUFxusFDs7EW4+ThAKb33KsNOyyJz4Z46Ywk=

    * APP_URL=https://analisejogadores-production.up.railway.app

    *DB_CONNECTION=mysql
     DB_HOST=caboose.proxy.rlwy.net //se encontra em setings/networking/Connect to your...
     DB_PORT=13826  //se encontra em setings/networking/Connect to your...
     DB_DATABASE=railway //se encontra em variables
     DB_USERNAME=root   //se encontra em variables
     DB_PASSWORD=SyqQcrlKRXfHvjNLiMacoqRsmxPsYPDG  //se encontra em variables

4 - serviceProvider
        public function boot(): void
        {
            if ($this->app->environment('production')) {
                URL::forceScheme('https');
            }
        }
5 - colocar no form
    * action="{{ route('aluno.store', [], true) }}" 

6 - criar o banco e no site que ja vem com as variaveis clica em criar conexao publica com a porta 3306
    * MYSQL_DATABASE  railway
    * MYSQL_PUBLIC_URL mysql://root:VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR@interchange.proxy.rlwy.net:31139/railway
    * MYSQL_ROOT_PASSWORD VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR
    * MYSQL_URL mysql://root:VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR@
    * MYSQLDATABASE railway
    * MYSQLHOST mysql.railway.internal
    * MYSQLPASSWORD VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR
    * MYSQLPORT 3306
    * MYSQLUSER root

7 - criar o projeto e configurar as variaveis com a do banco e gerar a url em Setings/Networking/Access your application over HTTP porta 8080

    * MYSQL_DATABASE  railway
    * MYSQL_PUBLIC_URL mysql://root:VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR@interchange.proxy.rlwy.net:31139/railway
    * MYSQL_ROOT_PASSWORD VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR
    * MYSQL_URL mysql://root:VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR@
    * MYSQLDATABASE railway
    * MYSQLHOST mysql.railway.internal
    * MYSQLPASSWORD VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR
    * MYSQLPORT 3306
    * MYSQLUSER root
    * PORT 8000