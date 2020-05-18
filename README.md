

### Namespacing

    {% include 'web/' ~ 'view/film/index/index.twig' with {
        films: []
    } %}

Separate include strings to break out namespacing of Vue class name.
TwigToVue will use the last string, so the example above will look for
ViewFilmIndex.


### Duplicate Names

Duplicate names are automatically stripped from includes. In the
above example, "index/index.twig" becomes `ViewFilmIndex`.
