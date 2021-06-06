# GamersClub API

GamersClub API es una librería en PHP que permite obtener información de partidos, torneos, players y teams de la plataforma Gamers Club.

Gamers Club al no proporcionar una API, la librería lo que hace es scrapear directamente la web, parsear la información y devolverla en un formato que se pueda trabajar.

#### ATENCIÓN: Actualmente GC tiene implementado CloudFare, con lo cual todas las peticiones vía cURL las rechaza y bloquea. 

#### Para evitar el bloqueo tienen que usar la librería en NodeJS que utiliza Puppeteer: https://github.com/rodrigorioo/gamersclub-api 

## Instalación

La librería se instala vía composer

```sh
composer require rodrigorioo/gamersclub-api
```

## Uso

Para empezar a utilizarla tenemos que iniciarla con la cookie de sesión de Gamers Club (ver sección "Obtener cookie de Gamers Club")

```sh
use GamersClubAPI\GC;

$gamersClubAPI = new GC('<token_de_gamers_club>');
```

Con esto ya podemos llamar a los métodos

## Obtener cookie de Gamers Club

Una vez logueados en Gamers Club con nuestra cuenta de Steam, en Chrome realizamos lo siguiente:

1. Apretamos F12
2. Vamos a la pestaña "Application"
3. En la sección "Storage" a la izquierda, apretamos en donde dice "Cookies" y luego apretamos "https://gamersclub.com.br"
4. Ahi nos va a mostrar una tabla, a nosotros nos importa la que dice "gclubsess". Cuando la encontramos, vamos a copiar toda la cadena que está en la columna "Value" de esa fila
5. Una vez copiado eso, lo ponemos en la librería y listo! :)


## Métodos

| Nombre | Retorna |
| ------ | ------ |
| getMatch(tournamentId, matchId) | Match() |
| getTeam(teamId) | Team() |
| getTeamMatches(teamId) | Array ( Match() ) |

## Clases

#### Match

- String id - ID del match
- Tournament tournament - Torneo al que pertenece el match
- Bool live - Si el partido está en vivo o no
- String team1 - Nombre del equipo 1
- String team2 - Nombre del equipo 2
- Integer score1 - Score del equipo 1
- Integer score2 - Score del equipo 2
- Bool finished - Si el partido ya finalizó
- String best_of - Tipo de match (BO1, BO3, etc.)
- String date - Fecha del partido
- String hour - Hora del partido
- String maps - Mapas del match (Si son más de 1 se separan por comas)

#### Player

- String id - ID del player
- String name - Nombre del player
- String level - Nivel del player
- String avatar - Avatar que tiene en Gamers Club
- String role - Rol que juega dentro del equipo

#### Team

- String id - ID del team
- String logo - Logo del team
- String name - Nombre del team
- String tag - Tag del team
- Array[Player] players - Players que pertenecen al team
- Array[Match] matches - Partidos del equipo

#### Tournament

- String id - ID del torneo
- String name - Nombre del torneo

## Excepciones

| Nombre | Descripción |
| ------ | ------ |
| GamersClubAPI\Exceptions\Curl\Curl |  |
| GamersClubAPI\Exceptions\ParseHTML\ParseHTML |  |
| GamersClubAPI\Exceptions\ParseHTML\InvalidHTMLResponse | Hereda de GamersClubAPI\Exceptions\ParseHTML\ParseHTML | |
| GamersClubAPI\Exceptions\ParseHTML\ResponseHeaderEmpty | Hereda de GamersClubAPI\Exceptions\ParseHTML\ParseHTML |

## Tests

En la carpeta tests hay algunos tests básicos para que vean como se utiliza la librería al mismo tiempo que tienen incluídas las capturas de las excepciones.

Para poder utilizarlas, tienen que crear un archivo init.php con el mismo formato que tiene el init_example.php en donde van a setear la cookie de GamersClub

## License

MIT