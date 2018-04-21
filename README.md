# Sufel

[![Travis-CI](https://travis-ci.org/giansalex/sufel-rest.svg?branch=master)](https://travis-ci.org/giansalex/sufel-rest)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/e70674827113495b83d1c79b1affb427)](https://www.codacy.com/app/giansalex/sufel-rest?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=giansalex/sufel-rest&amp;utm_campaign=Badge_Grade)  

Api de consultas para receptores de Facturación Electrónica en Perú según normativa de Superintendencia Nacional de Aduanas y de Administración Tributaria (SUNAT).  
Basado en [Sufel](https://github.com/giansalex/sufel) package.

## Características
- Publicar el xml y pdf .
- Es Multi-Empresa
- Consulta individual de comprobantes empleando datos como el ruc del emisor, tipo, serie, correlativo, fecha y total del comprobante.
- Descarga del comrobante en formato xml y pdf.
- Permite que el receptor pueda registrarse (actualmente solo para receptores con RUC)
- Consulta de todos los comprobantes de un receptor registrado

## UI Client
Una implementación basada en Angular 5 [SUFEL Angular](https://github.com/giansalex/sufel-angular)  

## API Docs
- [Swagger Docs Full](http://petstore.swagger.io/?url=https://raw.githubusercontent.com/giansalex/sufel-rest/master/src/data/swagger.json)  
- [Swagger for Company](http://editor.swagger.io/?url=https://raw.githubusercontent.com/giansalex/sufel-rest/master/src/data/swagger.company.json)
- [Swagger for Consult](http://editor.swagger.io/?url=https://raw.githubusercontent.com/giansalex/sufel-rest/master/src/data/swagger.receiver.json)
## Docker

Disponible en [Docker Hub](https://hub.docker.com/r/giansalex/sufel/)

```bash
docker pull giansalex/sufel
```
