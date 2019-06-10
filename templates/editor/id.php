{% extends 'index.html' %}
    {% use 'articles.html' %}
        {% block link %}
            <p class="text"><a href="/editor/edit/{{ articles.id_article }}" class="link">Изменить статью?</a></p>
        {% endblock %}
        {% block content %}
            <h1 class="text_bold_italic">Здесь статьи редактора: {{ content_data.0.name }}, {{ content_data.0.surname }}</h1>
            {{ block('articles') }}
        {% endblock %}