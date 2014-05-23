[![Build Status](https://travis-ci.org/KnpLabs/KnpRevealBundle.svg?branch=master)](https://travis-ci.org/KnpLabs/KnpRevealBundle)

KnpRevealBundle
========================

This bundle aims to simplify slides maintenance by keeping them in Markdown or HTML templates. If you have very few slides, you might be better using only [Reveal.js](http://lab.hakim.se/reveal-js), but in case you have hundreds of slides it could be useful.

[Reveal.js](http://lab.hakim.se/reveal-js) is a javascript library to impress your new audience with nifty transitions and stuff.

* [Setup](#setup)
* [Usage](#usage)
  * [Creating a presentation](#creating-a-presentation)
  * [Adding slides](#adding-slides)
      * [HTML helper](#html-helper)
      * [Markdown helper](#markdown-helper)
  * [More customization](#more-customization)
      * [Section attributes](#section-attributes)
  * [Using reveal](#using-reveal)

## Setup

Just add the bundle to your project

    composer require knplabs/knp-reveal-bundle

Register the bundle:

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Knp\RevealBundle\KnpRevealBundle(),
        );
        // ...
    }
 
Dump assets:

    app/console assets:install


## Usage

### Creating a presentation

Just add a new Controller using `SlideTrait` and define a directory where you will store the slides.

```php
<?php

namespace Knp\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knp\RevealBundle\Controller\SlideTrait;

class FooController extends Controller
{
    use SlideTrait;

    public function getSlidesDirectory()
    {
        return "@KnpAppBundle/Resources/views/Foo";
    }
}
```

And corresponding route :

```yml
knp_app_foo_slides:
    pattern:  /foo
    defaults: { _controller: KnpAppBundle:Foo:slides }
```

### Adding slides

Just add some templates in your presentation's template directory, for example `@KnpAppBundle/Resources/views/Foo/01.title.html.twig` :

```html
<section>
    <h1>My super Foobar presentation</h1>
    <h2>Offered to you by KNPLabs</h2>
</section>
```

#### Html helper

You can extends the html base slide to use various helpers.

```Django
{% extends 'KnpRevealBundle:Slide:slide.html.twig' %}

{% block content %}
    <h1>My super Foobar presentation</h1>
    <h2>Offered to you by KNPLabs</h2>
{% endblock %}
```

#### Markdown helper

In the same way, you can extends the md base slide.

```Django
{% extends 'KnpRevealBundle:Slide:slide.md.twig' %}

{% block content %}
# My super Foobar presentation

## Offered to you by KNPLabs
{% endblock %}
```

### More customization

#### Section attributes

If you want to set section's attributes, like background or transition, do it with the `section_attributes` block.

```Django
{% extends 'KnpRevealBundle:Slide:slide.md.twig' %}

{% block section_attributes %}
    data-background="{{ asset('/bundles/knpapp/fromnovicetoninja/images/002_edgar_city.png') }}"
    data-background-color="#ffe8e8"
    data-background-size="50%"
    data-background-position="bottom right"
    data-background-transition="slide"
{% endblock %}

{% block content %}
# My super Foobar presentation

## Offered to you by KNPLabs
{% endblock %}
```

### Using reveal

Best way to understand reveal is to [see the reveal presentation](http://lab.hakim.se/reveal-js/#/).