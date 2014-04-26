# PDF Thumbnail Generator Plugin for Craft CMS

**Currently requires php5-imagick or Imagemagick**

More detailed read me on the way.

## Installation

0. Ensure php5-imagick is installed.
1. Upload this directory to `craft/plugins/pdfthumbnails/` on your server.
2. Enable the plugin under Craft Admin > Settings > Plugins

## Usage

Use it in a template like this:

```twig
<ul class="list-unstyled row press">

{% for file in craft.assets.folderId(2) %}

    <li>
        <a href="{{ file.url }}" class="thumbnail">
        
		{% if file.kind == 'pdf' %}
			<img src="{{ resourceUrl('pdfthumb/'~file.id) }}" alt="{{ file.filename }}" />
			{% else %}

{% set params = {
width: 250,
height: 250,
mode: 'crop'
} %}
			<img src="{{ file.getUrl(params) }}" width="{{ file.getWidth(params) }}" height="{{ file.getHeight(params) }}" alt="{{ file.title }}">
			{% endif %}

        </a>
    </li>
{% endfor %}
</ul>
```

## License

This work is licenced under the MIT license.

## TODOs

- [x] Make something that works!
- [ ] Better readme docs
- [ ] Setup composer.json
- [ ] Make variables editable via CMS?
- [ ] assume default if relevant parts aren't numbers
- [ ] add error handling if none found
- [ ] add default PDF icon thumbnail if can't process? 
- [ ] More robust way to get CRAFT_BASE_PATH