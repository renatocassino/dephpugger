Front Matter
============

Front matter parser and dumper for PHP.

Installation
------------

This project can be installed via Composer:

    composer require webuni/front-matter

Usage
-----

```php
$frontMatter = new Webuni\FrontMatter\FrontMatter();

$document = $frontMatter->parse($str)

$data = $document->getData();
$content = $document->getContent();
```

Alternatives
------------

- https://github.com/mnapoli/FrontYAML
- https://github.com/Modularr/YAML-FrontMatter
- https://github.com/vkbansal/FrontMatter
- https://github.com/kzykhys/YamlFrontMatter
- https://github.com/orchestral/kurenai
