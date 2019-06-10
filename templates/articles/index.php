{% extends 'index.html' %}
{% use 'articles.html' %}
{% block link %}
<p class="text"><a href="/articles/{{ articles.id_article }}" class="link">Просмотреть статью?</a></p>
{% endblock %}
{% block content %}
    {{ block('articles') }}
{% endblock %}
