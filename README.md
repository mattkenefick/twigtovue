
<img src="./assets/repo/logo-hero.jpg"
    alt="TwigToVue"
    align="center"
    height="240"
    />

![Last version](https://img.shields.io/github/tag/mattkenefick/twigtovue.svg?style=flat-square)
[![Dependency status](https://img.shields.io/david/mattkenefick/twigtovue.svg?style=flat-square)](https://david-dm.org/mattkenefick/twigtovue)
[![Dev Dependencies Status](https://img.shields.io/david/dev/mattkenefick/twigtovue.svg?style=flat-square)](https://david-dm.org/mattkenefick/twigtovue#info=devDependencies)
[![Donate](https://img.shields.io/badge/donate-paypal-blue.svg?style=flat-square)](https://paypal.me/polymermallard)

<a href="https://twitter.com/intent/follow?screen_name=mattkenefick">
    <img src="https://img.shields.io/twitter/follow/mattkenefick.svg?style=social&logo=twitter" alt="follow on Twitter"></a>
</a>


## Install

    $ composer require twigtovue


## Test

    $ composer test


## Configure

    $ composer install


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


## License

**twigtovue** © [polymer mallard](https://polymermallard.com), released under the [ISC](https://github.com/mattkenefick/twigtovue/blob/master/LICENSE.md) License.<br>
Authored and maintained by Polymer Mallard with help from [contributors](https://github.com/mattkenefick/twigtovue/contributors).

> [polymer mallard](https://www.polymermallard.com) · GitHub [@mattkenefick](https://github.com/mattkenefick) · Twitter [@mattkenefick](https://twitter.com/mattkenefick)
