<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


Passo a passo como hospedar no raillway

1 - hospedar no git e upar atraves dele
2 - mudar o dockerfile
3 - mudar o env. 
    * APP_ENV=production
    * APP_KEY=base64:7xiLvEIhUFxusFDs7EW4+ThAKb33KsNOyyJz4Z46Ywk=
    * APP_URL=https://analisejogadores-production.up.railway.app
    * DB_CONNECTION=mysql
      DB_HOST=interchange.proxy.rlwy.net
      DB_PORT=31139
      DB_DATABASE=railway
      DB_USERNAME=root
      DB_PASSWORD=VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR
4 - serviceProvider
        public function boot(): void
        {
            if ($this->app->environment('production')) {
                URL::forceScheme('https');
            }
        }
5 - colocar no form
    * action="{{ route('aluno.store', [], true) }}" 

6 - criar o banco e no terminal rodar as migrates e configurar as variaveis
    * MYSQL_DATABASE  railway
    * MYSQL_PUBLIC_URL mysql://root:VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR@interchange.proxy.rlwy.net:31139/railway
    * MYSQL_ROOT_PASSWORD VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR
    * MYSQL_URL mysql://root:VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR@
    * MYSQLDATABASE railway
    * MYSQLHOST mysql.railway.internal
    * MYSQLPASSWORD VbgdOhwAPTIbvOtIfHDRVZOomMkkJLOR
    * MYSQLPORT 3306
    * MYSQLUSER root

7 - criar o projeto e configurar as variaveis 
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

8 - gerar a liberar a url das portas 8000 em settings/networking gerar e colocar porta 8000