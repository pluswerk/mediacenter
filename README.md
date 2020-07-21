# What does it do

This extension adds a dynamic media center with a download-function and detail-view to your project.

Features:
* Media records with title, assets, categories and descriptions
* Media categories
* Filtering for categories, search, date and media-type (e.g. only images / videos)
* Extendable (bootstrapped) fluid-templates
* Media assets can be downloaded as '.zip' (configurable)

# How to

Add the mediacenter-plugin to your page. Select a storage folder inside the plugin options.
Put all your media-records and media-category-records inside this storage folder.

# Routing Example for speaking URLs

```yaml
  Mediacenter:
    type: Extbase
    extension: Mediacenter
    plugin: Mediacenter
    defaultController: 'Mediacenter::list'
    routes:
      - { routePath: '/', _controller: 'Mediacenter::list' }
      - { routePath: '/filter/{mediaType}', _controller: 'Mediacenter::filterList' }
      - { routePath: '/detail/{media}', _controller: 'Mediacenter::show' }
    defaults:
      mediaType: 0
    aspects:
      mediaType:
        type: StaticValueMapper
        map:
          all: 0
          image: 2
          audio: 3
          video: 4
        localeMap:
          - locale: 'de_.*'
            map:
              alle: 0
              bild: 2
              audio: 3
              video: 4
      media:
        type: PersistedAliasMapper
        tableName: 'tx_mediacenter_domain_model_media'
        routeFieldName: 'slug'

```
