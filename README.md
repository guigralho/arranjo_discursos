# Arranjo de discursos

## Instalação

```bash
$ cd api

$ ./sail-reinstall.sh | bash 

$ cp .env.example .env

# adicione um alias para o comando sail
$ alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'

$ sail up -d
$ sail artisan storage:link
$ sail artisan migrate
$ sail npm install
$ sail npm run dev
```
