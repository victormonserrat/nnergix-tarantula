<div align="center">
<img alt="Tarantula" src="./docs/_images/logo.svg" width=150 />
</div>

# Tarantula · [![GitHub license](https://img.shields.io/badge/license-MIT-brightgreen.svg)](./LICENSE) [![Coverage Status](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](https://coveralls.io/github/victormonserrat/donut) [![Build Status](https://travis-ci.org/victormonserrat/donut.svg)](https://img.shields.io/badge/build-passing-brightgreen.svg)

Tarantula is a Full Stack Developer Nnergix test.

#### Objetivos
* Demostrar experiencia en programación web para “Back-end” (preferiblemente con framework Symfony) y “Front-end”.
  
#### Descripción
Desarrollar una aplicación que cumpla con los siguientes requisitos:

* Navegar como un Spider/Crawler a partir de una URL inicial configurable.
* No visitar la misma URL dos veces en una misma ejecución.
* En sucesivas ejecuciones, añadir las nuevas URLs encontradas a las ya guardadas.
* Dar un resumen del proceso realizado (URLs nuevas encontradas, por ejemplo).
* Para cada URL visitada, guardar ciertos Headers HTTP (máximo 5), configurables.
* Cuando cambie un Header respecto a la última visita, avisar y guardar su nuevo valor.
* Desarrollar un Front-end sencillo donde poder introducir la URL inicial y profundidad y donde se visualicen los datos 
obtenidos de la búsqueda.

Tener en cuenta los siguientes aspectos:

* Se puede emplear el método de almacenamiento que prefiera (por ejemplo, MySQL).
* Se espera que emplee un Framework tanto para el Back end como para el Front end.
* Evitar hacer over-engineering. Se valorará la simplicidad de la solución.
* Se recomienda listar los temas que se hayan quedado sin resolver por falta de tiempo.

#### Entrega
* Entregar el código en un repositorio GIT (Nnergix puede proveer un repositorio).
* Si se desea, también puede alojarlo en un servidor y entregarnos los datos de acceso.

## Installation

* Install dependencies with composer dependency manager: `composer install`.
* [Optional] Create a file named `.env.local` in the project root and copy the content of the `.env` file. You can also 
customize parameters as you consider.
* [Optional] Modify the `config/services.yaml` file to personalize parameters as you want.
* Create the database with `bin/console doctrine:database:create` and the schema with 
`bin/console doctrine:schema:create`
* Serve the application locally with the web server bundle `bin/console server:run` or with a php server if you prefer 
`php -S localhost:8000 -t public/`.
* Open [the application](http://localhost:8000/api) with your favourite web browser.

## License

Tarantula is [MIT licensed](./LICENSE).
